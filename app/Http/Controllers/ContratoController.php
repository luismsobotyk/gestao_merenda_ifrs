<?php

namespace App\Http\Controllers;

use App\Models\FornecedorResponsavel;
use App\Models\FornecedorResponsavelContato;
use \App\Models\ItemContrato;
use Illuminate\Http\Request;
use App\Models\Contrato;
use App\Models\Fornecedor;
use Carbon\Carbon;

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

    public function visualizaContrato($id){
        $contrato = Contrato::with([
            'fornecedor.responsaveis.contatos',

            'itens.unidade',
            'itens.itensEmpenho.itensPedido',

            'empenhos.pedidos.itensPedido.itemEmpenho.itemContrato'
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
}
