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
         * - 3 dias anteriores
         * - hoje
         * - 7 dias seguintes
         */
        $dataInicial = $hoje->copy()->subDays(3);
        $dataFinal = $hoje->copy()->addDays(7);

        /*
         * Ajuste este ponto conforme sua regra.
         * Por exemplo:
         *
         * Cardapio::where('ativo', true)
         * Cardapio::where('unidade_uuid', ...)
         * Cardapio::where('contrato_uuid', ...)
         *
         * Como você não mostrou a migration de cardapios,
         * deixei a busca ampla.
         */
        $cardapios = Cardapio::with([
            'horarios.itensPadrao.itemContrato',
            'horarios.excecoes.itens.itemContrato',
        ])->get();

        $dias = collect();

        for ($data = $dataInicial->copy(); $data->lte($dataFinal); $data->addDay()) {
            $dias->push($this->montarCardapioDoDia($data->copy(), $cardapios));
        }

        /*
         * Define qual slide ficará ativo:
         * 1. Se hoje tiver cardápio, destaca hoje.
         * 2. Senão, destaca o próximo dia com cardápio.
         * 3. Se não houver nenhum próximo, destaca o primeiro item da lista.
         */
        $indiceAtivo = $dias->search(function ($dia) use ($hoje) {
            return $dia['data']->isSameDay($hoje) && $dia['possui_cardapio'];
        });

        if ($indiceAtivo === false) {
            $indiceAtivo = $dias->search(function ($dia) use ($hoje) {
                return $dia['data']->greaterThan($hoje) && $dia['possui_cardapio'];
            });
        }

        if ($indiceAtivo === false) {
            $indiceAtivo = 0;
        }

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
                /*
                 * Substituição:
                 * Remove os itens normais e usa somente os itens da exceção.
                 */
                $itens = $itensExcecao;
            }

            if ($excecao->tipo === 'inclusao') {
                /*
                 * Inclusão:
                 * Mantém os itens normais e adiciona os itens da exceção.
                 */
                $itens = $itens->merge($itensExcecao)->unique('id')->values();
            }

            if ($excecao->tipo === 'supressao') {
                /*
                 * Supressão:
                 * Se houver itens vinculados à exceção, remove somente esses itens.
                 * Se não houver itens vinculados, remove todo o cardápio daquele horário.
                 */
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

    public function teste(Request $request){
        dd($request);
    }
}
