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

        $diasMapa = [1 => 'Segunda', 2 => 'Terça', 3 => 'Quarta', 4 => 'Quinta', 5 => 'Sexta'];

        return view('dashboard.cardapio.cardapio_cadastro', compact('cardapio', 'itensDisponiveis', 'diasMapa'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nome_cardapio' => 'required|string|max:255',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ], [
            'data_fim.after_or_equal' => 'A data de término não pode ser anterior à data de início.',
        ]);

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

        $cardapio = \App\Models\Cardapio::create([
            'nome' => $request->nome_cardapio,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
        ]);

        return redirect()->route('cardapio.editar', $cardapio->id)
            ->with('success', 'Cardápio iniciado! Agora defina os horários e alimentos.');
    }

    public function syncAll(Request $request, $id)
    {
        $cardapio = Cardapio::findOrFail($id);

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

        DB::beginTransaction();

        try {
            $cardapio->update([
                'nome' => $request->nome_cardapio,
                'data_inicio' => $request->data_inicio,
                'data_fim' => $request->data_fim,
            ]);

            $cardapio->horarios()->delete();
            $cardapio->excecoes()->delete();

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
    public function destroy($id)
    {
        $cardapio = Cardapio::findOrFail($id);
        $cardapio->delete();
        return redirect()->route('cardapio')->with('success', 'Cardápio excluído permanentemente.');
    }
}
