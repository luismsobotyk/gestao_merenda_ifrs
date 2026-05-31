<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GraficoController extends Controller
{
    public function tiposMerenda()
    {
        // Puxa as retiradas que possuem horário definido
        $retiradas = DB::table('retiradas')
            ->join('cardapio_horarios', 'retiradas.cardapio_horario_id', '=', 'cardapio_horarios.id')
            ->select(
                'retiradas.data_retirada',
                'cardapio_horarios.id as horario_id',
                // O turno_nome não é mais necessário para a label final, mas mantemos o agrupamento limpo
                DB::raw('COUNT(retiradas.id) as total')
            )
            ->whereNotNull('retiradas.cardapio_horario_id')
            ->groupBy('retiradas.data_retirada', 'cardapio_horarios.id')
            ->get();

        $consumoPorLanche = [];

        foreach ($retiradas as $retirada) {
            $data = $retirada->data_retirada;
            $horarioId = $retirada->horario_id;
            $diaSemana = Carbon::parse($data)->dayOfWeekIso;

            // 1. Verifica EXCEÇÃO
            $itensExcecao = DB::table('cardapio_excecoes')
                ->join('cardapio_excecao_itens', 'cardapio_excecoes.id', '=', 'cardapio_excecao_itens.cardapio_excecao_id')
                ->join('item_contrato', 'cardapio_excecao_itens.item_contrato_uuid', '=', 'item_contrato.id')
                ->where('cardapio_excecoes.data_exata', $data)
                ->where('cardapio_excecoes.cardapio_horario_id', $horarioId)
                ->pluck('item_contrato.nome');

            if ($itensExcecao->isNotEmpty()) {
                // Mantemos a flag de Exceção para o gestor saber que foi algo fora do padrão, mas removemos o turno
                $nomeLanche = $itensExcecao->unique()->implode(' + ') . " (Exceção)";
            } else {
                // 2. Se não teve exceção, busca o PADRÃO do turno correspondente
                $itensPadrao = DB::table('cardapio_itens_padrao')
                    ->join('item_contrato', 'cardapio_itens_padrao.item_contrato_uuid', '=', 'item_contrato.id')
                    ->where('cardapio_itens_padrao.cardapio_horario_id', $horarioId)
                    ->where('cardapio_itens_padrao.dia_semana', $diaSemana)
                    ->pluck('item_contrato.nome');

                // A MÁGICA: A string gerada agora é apenas os nomes dos alimentos unidos
                $nomeLanche = $itensPadrao->isNotEmpty() ? $itensPadrao->unique()->implode(' + ') : "Sem Cardápio Registrado";
            }

            // O PHP olha para o nomeLanche gerado. Se o prato for igualzinho ao de outro turno,
            // ele cai no mesmo índice do array e apenas incrementa o total!
            if (!isset($consumoPorLanche[$nomeLanche])) {
                $consumoPorLanche[$nomeLanche] = 0;
            }
            $consumoPorLanche[$nomeLanche] += $retirada->total;
        }

        $labels = array_keys($consumoPorLanche);
        $valores = array_values($consumoPorLanche);

        // Caso o banco não tenha cruzamentos válidos ainda, exibe o Mock
        if (empty($labels)) {
            $labels = ['Frutas Variadas', 'Pão de Queijo e Chá', 'Cachorro Quente', 'Pão de Batata e Suco', 'Iogurte e Banana (Exceção)'];
            $valores = [80, 150, 290, 310, 420];
        }

        return view('dashboard.graficos.tipos-merenda', compact('labels', 'valores'));
    }
}
