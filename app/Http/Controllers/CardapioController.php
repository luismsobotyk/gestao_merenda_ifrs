<?php

namespace App\Http\Controllers;

use App\Models\Cardapio;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CardapioController extends Controller
{
    public function index()
    {
        $cardapios = Cardapio::orderBy('created_at', 'desc')->get();
        return view('dashboard.cardapio.cardapio_lista', compact('cardapios'));
    }

    public function create()
    {
        return view('dashboard.cardapio.cardapio_cadastro');
    }

    public function edit($id)
    {
        $cardapio = Cardapio::with(['horarios.itensPadrao.itemContrato', 'excecoes.horario', 'excecoes.itens.itemContrato'])->findOrFail($id);

        $itensContrato = \App\Models\ItemContrato::with(['itensEmpenho.itensPedido', 'unidade'])->get();

        $itensDisponiveis = $itensContrato->filter(function($item) {
            $empenhada = $item->itensEmpenho->sum('quantidade_empenhada');
            $consumida = $item->itensEmpenho->flatMap->itensPedido->sum('quantidade');
            return ($empenhada - $consumida) > 0;
        });

        // DEFINA A VARIÁVEL AQUI
        $diasMapa = [1 => 'Segunda', 2 => 'Terça', 3 => 'Quarta', 4 => 'Quinta', 5 => 'Sexta'];

        return view('dashboard.cardapio.cardapio_cadastro', compact('cardapio', 'itensDisponiveis', 'diasMapa'));
    }
    public function store(Request $request)
    {
        // 1. Validação dos dados base
        $request->validate([
            'nome_cardapio' => 'required|string|max:255',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ], [
            'data_fim.after_or_equal' => 'A data de término não pode ser anterior à data de início.',
        ]);

        // 2. VERIFICAÇÃO DE SOBREPOSIÇÃO DE DATAS
        $conflito = \App\Models\Cardapio::where('data_inicio', '<=', $request->data_fim)
            ->where('data_fim', '>=', $request->data_inicio)
            ->first();

        if ($conflito) {
            $inicioConflito = Carbon::parse($conflito->data_inicio)->format('d/m/Y');
            $fimConflito = Carbon::parse($conflito->data_fim)->format('d/m/Y');

            return back()->withErrors([
                'datas' => "Já existe um cardápio cadastrado neste período: '{$conflito->nome}' ({$inicioConflito} a {$fimConflito})."
            ])->withInput();
        }

        // 3. Criação do registro mestre se não houver conflitos
        $cardapio = \App\Models\Cardapio::create([
            'nome' => $request->nome_cardapio,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
        ]);

        // 4. Redireciona para a tela de edição
        return redirect()->route('cardapio.editar', $cardapio->id)
            ->with('success', 'Cardápio iniciado! Agora defina os horários e alimentos.');
    }

    public function syncAll(Request $request, $id)
    {
        $cardapio = Cardapio::findOrFail($id);

        // 1. VERIFICAÇÃO DE SOBREPOSIÇÃO NA EDIÇÃO (Ignorando o cardápio atual)
        $conflito = \App\Models\Cardapio::where('id', '!=', $id)
            ->where('data_inicio', '<=', $request->data_fim)
            ->where('data_fim', '>=', $request->data_inicio)
            ->first();

        if ($conflito) {
            $inicioConflito = Carbon::parse($conflito->data_inicio)->format('d/m/Y');
            $fimConflito = Carbon::parse($conflito->data_fim)->format('d/m/Y');
            return response()->json([
                'success' => false,
                'error' => "Choque de datas com o cardápio '{$conflito->nome}' ({$inicioConflito} a {$fimConflito})."
            ]);
        }

        // Inicia a transação com o Banco de Dados
        DB::beginTransaction();

        try {
            // 2. Atualiza as Informações Básicas
            $cardapio->update([
                'nome' => $request->nome_cardapio,
                'data_inicio' => $request->data_inicio,
                'data_fim' => $request->data_fim,
            ]);

            // 3. Limpeza Cirúrgica
            $cardapio->horarios()->delete();
            $cardapio->excecoes()->delete();

            // 4. Reconstrói os Horários e Itens da Grid
            $mapaHorarios = [];
            foreach ($request->horarios ?? [] as $hIndex => $hData) {
                $horario = $cardapio->horarios()->create([
                    'nome' => $hData['nome'],
                    'hora_inicio' => $hData['hora_inicio'],
                    'hora_fim' => $hData['hora_fim'],
                    'descricao_publico' => $hData['descricao_publico'] ?? null,
                ]);

                $mapaHorarios[$hIndex] = $horario->id;

                foreach ($hData['itens'] ?? [] as $itemData) {
                    $horario->itensPadrao()->create([
                        'dia_semana' => $itemData['dia_semana'],
                        'item_contrato_uuid' => $itemData['item_contrato_uuid'],
                    ]);
                }
            }

            // 5. Reconstrói as Exceções
            foreach ($request->excecoes ?? [] as $excData) {
                $horarioRealId = $mapaHorarios[$excData['horario_index']];

                $excecao = $cardapio->excecoes()->create([
                    'data_exata' => $excData['data_exata'],
                    'tipo' => $excData['tipo'],
                    'cardapio_horario_id' => $horarioRealId,
                ]);

                foreach ($excData['itens'] ?? [] as $itemExcData) {
                    $excecao->itens()->create([
                        'item_contrato_uuid' => $itemExcData['item_contrato_uuid'],
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Cardápio salvo com sucesso!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
