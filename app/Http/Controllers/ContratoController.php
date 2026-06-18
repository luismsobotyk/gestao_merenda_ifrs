<?php

namespace App\Http\Controllers;

use App\Models\FornecedorResponsavel;
use App\Models\FornecedorResponsavelContato;
use \App\Models\ItemContrato;
use Illuminate\Http\Request;
use App\Models\Contrato;
use App\Models\Fornecedor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ContratoController extends Controller
{
    public function listaContratos(Request $request)
    {
        $query = Contrato::with('fornecedor');
        if ($request->filled('fornecedor')) {
            $query->whereHas('fornecedor', function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->fornecedor . '%');
            });
        }
        if ($request->filled('ano') && $request->ano !== 'Todos') {
            $query->where('pregao', 'like', '%/' . $request->ano);
        }
        if ($request->filled('status') && $request->status !== 'Todos') {
            $query->where('status', $request->status);
        }
        $contratos = $query->orderBy('inicio_vigencia', 'desc')->paginate(10);
        $contratos->appends($request->all());

        return view('dashboard.listaContratos', compact('contratos'));
    }

    public function visualizaContrato($id)
    {
        $contrato = Contrato::with([
            'fornecedor.responsaveis.contatos',
            'itens.unidade',
            'itens.itensEmpenho.itensPedido',
            'pedidos.itensPedido.itemEmpenho.itemContrato.unidade',

            'empenhos.itensEmpenho.itensPedido.pedido'
        ])->findOrFail($id);

        return view('dashboard.contrato', compact('contrato'));
    }

    public function criaContrato(){
        $fornecedores = Fornecedor::orderBy('nome')->get();
        $unidades = \App\Models\Unidade::orderBy('descricao')->get();

        return view('dashboard.criaContrato', compact('fornecedores', 'unidades'));
    }
    public function salvaContrato(Request $request){
        if ($request->has('valor_global')) {
            $valorLimpo = str_replace('.', '', $request->valor_global);
            $valorLimpo = str_replace(',', '.', $valorLimpo);
            $request->merge(['valor_global' => $valorLimpo]);
        }

        $validated = $request->validate([
            'cnpj' => 'required|string|max:18',
            'nome_fornecedor' => 'required|string|max:255',
            'sigla_fornecedor' => 'nullable|string|max:20',
            'email_contato' => 'required|email|max:255',
            'processo' => 'required|string|max:255',
            'pregao' => 'required|string|max:255',
            'inicio_vigencia' => 'required|date',
            'fim_vigencia' => 'required|date|after_or_equal:inicio_vigencia',
            'valor_global' => 'required|numeric',

            'itens' => 'required|array|min:1',
            'itens.*.nome' => 'required|string|max:255',
            'itens.*.unidade_uuid' => 'required|uuid',
            'itens.*.quantidade' => 'required|string',
            'itens.*.valor_unitario' => 'required|string',
        ]);

        $hoje = now()->endOfDay();
        $fimVigencia = Carbon::parse($request->fim_vigencia)->endOfDay();
        $statusCalculado = $hoje->lte($fimVigencia) ? 'Vigente' : 'Encerrado';

        $fornecedor = Fornecedor::firstOrCreate(
            ['cnpj' => $request->cnpj],
            ['nome' => $request->nome_fornecedor, 'sigla' => $request->sigla_fornecedor]
        );

        $responsavel = FornecedorResponsavel::firstOrCreate(
            ['fornecedor_uuid' => $fornecedor->id, 'is_principal' => true],
            ['nome' => 'Contato Principal']
        );

        FornecedorResponsavelContato::updateOrCreate(
            ['fornecedor_responsavel_id' => $responsavel->id, 'tipo' => 'Email'],
            ['valor' => $request->email_contato]
        );

        $contrato = Contrato::create([
            'fornecedor_id' => $fornecedor->id,
            'processo' => $request->processo,
            'pregao' => $request->pregao,
            'inicio_vigencia' => $request->inicio_vigencia,
            'fim_vigencia' => $request->fim_vigencia,
            'valor_global' => $request->valor_global,
            'status' => $statusCalculado,
        ]);

        foreach ($request->itens as $itemForm) {
            $qtdLimpa = str_replace(['.', ','], ['', '.'], $itemForm['quantidade']);
            $valorLimpo = str_replace(['.', ','], ['', '.'], $itemForm['valor_unitario']);

            ItemContrato::create([
                'contrato_uuid' => $contrato->id, // Usa o ID do contrato que acabou de nascer
                'unidade_uuid' => $itemForm['unidade_uuid'],
                'nome' => $itemForm['nome'],
                'quantidade' => $qtdLimpa,
                'valor_unitario' => $valorLimpo,
            ]);
        }

        return redirect()->route('contratos')->with('success', 'Contrato e itens cadastrados com sucesso!');
    }

    public function editar($id)
    {
        $contrato = Contrato::with(['fornecedor.responsaveis.contatos', 'itens'])->findOrFail($id);

        $unidades = DB::table('unidade')->orderBy('descricao')->get();

        return view('dashboard.criaContrato', compact('contrato', 'unidades'));
    }

    public function atualizar(Request $request, $id)
    {
        $contrato = Contrato::findOrFail($id);

        $contrato->update([
            'processo' => $request->processo,
            'pregao' => $request->pregao,
            'inicio_vigencia' => $request->inicio_vigencia,
            'fim_vigencia' => $request->fim_vigencia,
            'valor_global' => $this->converterMoeda($request->valor_global),
        ]);

        if ($request->filled('email_contato') && $contrato->fornecedor) {
            $responsavelPrincipal = $contrato->fornecedor->responsaveis()->firstOrCreate(
                ['is_principal' => true],
                ['nome' => 'Responsável Legal']
            );

            $responsavelPrincipal->contatos()->updateOrCreate(
                ['tipo' => 'Email'],
                ['valor' => $request->email_contato]
            );
        }

        $itensRecebidos = $request->input('itens', []);

        $idsRecebidos = collect($itensRecebidos)->pluck('id')->filter()->toArray();

        try {
            $contrato->itens()->whereNotIn('id', $idsRecebidos)->delete();
        } catch (\Exception $e) {
            return back()->withErrors('Não é possível remover um alimento que já possui Notas de Empenho vinculadas.')->withInput();
        }

        foreach ($itensRecebidos as $itemData) {
            $contrato->itens()->updateOrCreate(
                ['id' => $itemData['id'] ?? null],
                [
                    'nome' => $itemData['nome'],
                    'unidade_uuid' => $itemData['unidade_uuid'],
                    'quantidade' => $this->converterMoeda($itemData['quantidade']),
                    'valor_unitario' => $this->converterMoeda($itemData['valor_unitario']),
                ]
            );
        }

        return redirect()->route('contrato.visualizar', $contrato->id)
            ->with('success', 'Contrato atualizado com sucesso!');
    }

    /**
     * Função auxiliar para converter o formato brasileiro em decimal de banco de dados.
     */
    private function converterMoeda($valor)
    {
        if (!$valor) return 0;
        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);
        return (float) $valor;
    }

    public function salvaEmpenho(Request $request, $id)
    {
        $validated = $request->validate([
            'item_contrato_uuid' => 'required|uuid',
            'numero_empenho' => 'required|string|max:255',
            'quantidade_empenhada' => 'required|numeric|min:0.01',
            'valor_total_real' => 'required|numeric|min:0.01',
            'data_emissao' => 'nullable|date',
        ]);

        $contrato = Contrato::findOrFail($id);

        $empenho = \App\Models\Empenho::create([
            'contrato_uuid' => $contrato->id,
            'numero_empenho' => $request->numero_empenho,
            'valor_total' => $request->valor_total_real,
            'valor_utilizado' => 0,
        ]);

        \App\Models\ItemEmpenho::create([
            'empenho_uuid' => $empenho->id,
            'item_contrato_uuid' => $request->item_contrato_uuid,
            'quantidade_empenhada' => $request->quantidade_empenhada,
        ]);

        return redirect()->route('contrato.visualizaradd .', $contrato->id)->with('success', 'Nota de Empenho registrada com sucesso!');
    }
    public function salvaPedido(Request $request, $id)
    {
        $validated = $request->validate([
            'data_pedido' => 'required|date',
            'hora_pedido' => 'nullable|date_format:H:i',
            'ja_recebido' => 'nullable|boolean',
            'itens' => 'required|array|min:1',
            'itens.*.item_contrato_id' => 'required|uuid',
            'itens.*.quantidade_pedida' => 'required|numeric|min:0.01',
        ]);

        $contrato = Contrato::findOrFail($id);
        $dataSelecionada = Carbon::parse($request->data_pedido);

        if ($dataSelecionada->isFuture()) {
            $statusCalculado = 'Programado';
        } elseif ($dataSelecionada->isToday()) {
            $statusCalculado = 'Solicitado';
        } else {
            $statusCalculado = $request->boolean('ja_recebido') ? 'Recebido' : 'Solicitado';
        }

        if ($dataSelecionada->isToday()) {
            $dataHoraFinal = now();
        } else {
            $hora = $request->hora_pedido ?? '00:00';
            $dataHoraFinal = Carbon::parse($request->data_pedido . ' ' . $hora);
        }

        $pedidoMaster = \App\Models\Pedido::create([
            'contrato_uuid' => $contrato->id,
            'data_pedido' => $dataHoraFinal,
            'status' => $statusCalculado,
            'data_prevista_entrega' => $statusCalculado === 'Recebido' ? $dataHoraFinal : null,
        ]);

        foreach ($request->itens as $linhaPedido) {
            $qtdRestanteParaAbater = $linhaPedido['quantidade_pedida'];

            $itensEmpenho = \App\Models\ItemEmpenho::where('item_contrato_uuid', $linhaPedido['item_contrato_id'])
                ->orderBy('created_at', 'asc')
                ->get();

            foreach ($itensEmpenho as $itemEmpenho) {
                if ($qtdRestanteParaAbater <= 0) {
                    break;
                }

                $empenhada = $itemEmpenho->quantidade_empenhada;
                $consumida = $itemEmpenho->itensPedido()->sum('quantidade');
                $saldoDesteEmpenho = $empenhada - $consumida;

                if ($saldoDesteEmpenho > 0) {
                    $qtdSendoAbatidaAgora = min($qtdRestanteParaAbater, $saldoDesteEmpenho);

                    \App\Models\ItemPedido::create([
                        'pedido_uuid' => $pedidoMaster->id,
                        'item_empenho_uuid' => $itemEmpenho->id,
                        'quantidade' => $qtdSendoAbatidaAgora,
                    ]);

                    $empenhoPai = $itemEmpenho->empenho;
                    $valorFinanceiroConsumido = $qtdSendoAbatidaAgora * $itemEmpenho->itemContrato->valor_unitario;
                    $empenhoPai->valor_utilizado += $valorFinanceiroConsumido;
                    $empenhoPai->save();

                    $qtdRestanteParaAbater -= $qtdSendoAbatidaAgora;
                }
            }
        }

        return redirect()->back()->with('success', 'Pedido registrado e abatido dos empenhos com sucesso!');
    }
    public function receberPedido($id)
    {
        $pedido = \App\Models\Pedido::findOrFail($id);

        $pedido->status = 'Recebido';

        $pedido->data_prevista_entrega = now();

        $pedido->save();

        return redirect()->back()->with('success', 'Pedido confirmado como recebido com sucesso!');
    }
}
