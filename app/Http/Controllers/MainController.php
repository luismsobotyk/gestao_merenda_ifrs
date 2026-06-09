<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cardapio;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MainController extends Controller
{
    public function index()
    {
        $hoje = Carbon::today();

        /*
         * Janela exibida no carrossel:
         * - hoje
         * - 9 dias seguintes
         */
        $dataInicial = $hoje->copy();
        $dataFinal = $hoje->copy()->addDays(9);

        $cardapios = Cardapio::with([
            'horarios.itensPadrao.itemContrato',
            'horarios.excecoes.itens.itemContrato',
        ])->get();

        $dias = collect();

        for ($data = $dataInicial->copy(); $data->lte($dataFinal); $data->addDay()) {
            $dias->push($this->montarCardapioDoDia($data->copy(), $cardapios));
        }

        /*
         * Define qual slide ficará ativo ao carregar a página:
         * Como solicitado, carrega sempre o dia atual (índice 0 da lista)
         */
        $indiceAtivo = 0;

        return view('index', [
            'dias' => $dias,
            'indiceAtivo' => $indiceAtivo,
            'hoje' => $hoje,
        ]);
    }

    private function montarCardapioDoDia(Carbon $data, Collection $cardapios): array
    {
        $diaSemana = $data->dayOfWeekIso; // 1 = segunda, 7 = domingo

        $horariosDoDia = collect();

        foreach ($cardapios as $cardapio) {
            // Verifica se a data consultada está dentro do período de vigência deste cardápio
            $inicioVigencia = Carbon::parse($cardapio->data_inicio)->startOfDay();
            $fimVigencia = Carbon::parse($cardapio->data_fim)->endOfDay();

            if ($data->between($inicioVigencia, $fimVigencia)) {
                foreach ($cardapio->horarios as $horario) {
                    $itensPadrao = collect(
                        $horario->itensPadrao
                            ->where('dia_semana', $diaSemana)
                            ->map(function ($itemPadrao) {
                                return [
                                    'id' => $itemPadrao->item_contrato_uuid,
                                    'nome' => $itemPadrao->itemContrato->nome ?? 'Item não identificado',
                                    'quantidade_estimada_porcao' => $itemPadrao->quantidade_estimada_porcao,
                                    'origem' => 'padrao',
                                ];
                            })
                            ->values()
                            ->all()
                    );

                    $excecoesDoDia = $horario->excecoes
                        ->where('data_exata', $data->toDateString())
                        ->values();

                    $itensFinais = $this->aplicarExcecoes($itensPadrao, $excecoesDoDia);

                    if ($itensFinais->isNotEmpty()) {
                        $horariosDoDia->push([
                            'nome' => $horario->nome,
                            'hora_inicio' => $horario->hora_inicio,
                            'hora_fim' => $horario->hora_fim,
                            'descricao_publico' => $horario->descricao_publico,
                            'itens' => $itensFinais,
                        ]);
                    }
                }
            }
        }

        // Ordena os horários do dia pelo horário de início para exibição correta na tela
        $horariosDoDia = $horariosDoDia->sortBy('hora_inicio')->values();

        return [
            'data' => $data,
            'nome_dia' => $this->nomeDiaSemana($data),
            'eh_hoje' => $data->isToday(),
            'possui_cardapio' => $horariosDoDia->isNotEmpty(),
            'horarios' => $horariosDoDia,
        ];
    }

    private function aplicarExcecoes(Collection $itensPadrao, Collection $excecoes): Collection
    {
        $itens = $itensPadrao;

        foreach ($excecoes as $excecao) {
            $itensExcecao = $excecao->itens
                ->map(function ($itemExcecao) use ($excecao) {
                    return [
                        'id' => $itemExcecao->item_contrato_uuid,
                        'nome' => $itemExcecao->itemContrato->nome ?? 'Item não identificado',
                        'quantidade_estimada_porcao' => null,
                        'origem' => $excecao->tipo,
                    ];
                })
                ->values();

            if ($excecao->tipo === 'substituicao') {
                $itens = $itensExcecao;
            }

            if ($excecao->tipo === 'inclusao') {
                $itens = $itens->merge($itensExcecao)->unique('id')->values();
            }

            if ($excecao->tipo === 'supressao') {
                if ($itensExcecao->isEmpty()) {
                    $itens = collect();
                } else {
                    $idsRemovidos = $itensExcecao->pluck('id')->all();

                    $itens = $itens
                        ->reject(fn ($item) => in_array($item['id'], $idsRemovidos))
                        ->values();
                }
            }
        }

        return $itens->values();
    }

    private function nomeDiaSemana(Carbon $data): string
    {
        return match ($data->dayOfWeekIso) {
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado',
            7 => 'Domingo',
        };
    }

    public function avaliacao(){
        return view('avaliacao');
    }

}
