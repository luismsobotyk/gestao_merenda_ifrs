<?php

namespace App\Http\Controllers;

use App\Models\Cardapio;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        return view('dashboard.cardapio.cardapio_cadastro', compact('cardapio', 'itensDisponiveis'));
    }

    // NOVOS MÉTODOS PARA A GRID E HORÁRIOS
    public function destroyHorario($id)
    {
        $horario = \App\Models\CardapioHorario::findOrFail($id);
        $horario->delete(); // O 'cascadeOnDelete' da migration vai apagar os itens da grid automaticamente!
        return redirect()->back()->with('success', 'Horário removido com sucesso!');
    }

    public function storeItemPadrao(Request $request, $cardapio_id)
    {
        $request->validate([
            'cardapio_horario_id' => 'required|uuid',
            'item_contrato_uuid' => 'required|uuid',
            'dia_semana' => 'required|integer|between:1,5',
        ]);

        \App\Models\CardapioItemPadrao::create([
            'cardapio_horario_id' => $request->cardapio_horario_id,
            'item_contrato_uuid' => $request->item_contrato_uuid,
            'dia_semana' => $request->dia_semana,
        ]);

        return redirect()->back()->with('success', 'Alimento adicionado à grade!');
    }

    public function destroyItemPadrao($id)
    {
        $item = \App\Models\CardapioItemPadrao::findOrFail($id);
        $item->delete();
        return redirect()->back()->with('success', 'Alimento removido da grade!');
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

        // 2. Criação do registro mestre
        $cardapio = Cardapio::create([
            'nome' => $request->nome_cardapio,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
        ]);

        // Por enquanto, redirecionamos para ele mesmo para continuarmos a lógica depois
        return redirect()->route('cardapio.editar', $cardapio->id)
            ->with('success', 'Cardápio iniciado! Agora defina os horários e alimentos.');
    }

    public function storeHorario(Request $request, $id)
    {
        $cardapio = Cardapio::findOrFail($id);

        // 1. Validação
        $request->validate([
            'nome' => 'required|string|max:255',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fim' => 'required|date_format:H:i|after:hora_inicio',
            'descricao_publico' => 'nullable|string|max:255',
        ], [
            'hora_fim.after' => 'A hora final não pode ser anterior à hora de início.',
        ]);

        // 2. Salva o Horário vinculado ao Cardápio
        $cardapio->horarios()->create([
            'nome' => $request->nome,
            'hora_inicio' => $request->hora_inicio,
            'hora_fim' => $request->hora_fim,
            'descricao_publico' => $request->descricao_publico,
        ]);

        return redirect()->back()->with('success', 'Horário adicionado com sucesso!');
    }
    public function update(Request $request, $id)
    {
        $cardapio = Cardapio::findOrFail($id);

        // Valida se o usuário mudou as datas corretamente
        $request->validate([
            'nome_cardapio' => 'required|string|max:255',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ], [
            'data_fim.after_or_equal' => 'A data de término não pode ser anterior à data de início.',
        ]);

        // Atualiza as informações do Card 1
        $cardapio->update([
            'nome' => $request->nome_cardapio,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
        ]);

        return redirect()->back()->with('success', 'Informações gerais do cardápio atualizadas com sucesso!');
    }

    // ==========================================
    // MÉTODOS DE EXCEÇÃO
    // ==========================================
    public function storeExcecao(Request $request, $cardapio_id)
    {
        $request->validate([
            'data_exata' => 'required|date',
            'tipo_excecao' => 'required|in:inclusao,substituicao,supressao',
            'horario_id' => 'required|uuid',
        ]);

        \App\Models\CardapioExcecao::create([
            'cardapio_id' => $cardapio_id,
            'data_exata' => $request->data_exata,
            'tipo' => $request->tipo_excecao,
            'cardapio_horario_id' => $request->horario_id,
        ]);

        return redirect()->back()->with('success', 'Exceção adicionada! Agora adicione os alimentos a ela.');
    }

    public function destroyExcecao($id)
    {
        \App\Models\CardapioExcecao::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Exceção removida!');
    }

    public function storeItemExcecao(Request $request, $excecao_id)
    {
        $request->validate(['item_contrato_uuid' => 'required|uuid']);

        \App\Models\CardapioExcecaoItem::create([
            'cardapio_excecao_id' => $excecao_id,
            'item_contrato_uuid' => $request->item_contrato_uuid,
        ]);

        return redirect()->back()->with('success', 'Alimento adicionado ao dia especial!');
    }

    public function destroyItemExcecao($id)
    {
        \App\Models\CardapioExcecaoItem::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Alimento removido do dia especial!');
    }
}
