<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GraficoController extends Controller
{
    public function tiposMerenda(\Illuminate\Http\Request $request)
    {
        // 1. Lógica de "Cache" na Sessão
        if ($request->has('limpar')) {
            session()->forget(['filtro_tipos_data_inicial', 'filtro_tipos_data_final', 'filtro_tipos_grafico']);
            return redirect()->route('graficos.tipos_merenda');
        }

        // Se o formulário foi enviado (sabemos disso pois o campo tipo_grafico sempre é enviado)
        if ($request->has('tipo_grafico')) {
            session(['filtro_tipos_grafico' => $request->tipo_grafico]);

            // Se preencheu as datas, salva. Se deixou em branco, limpa as datas (todo o período).
            if ($request->filled('data_inicial') && $request->filled('data_final')) {
                session(['filtro_tipos_data_inicial' => $request->data_inicial]);
                session(['filtro_tipos_data_final' => $request->data_final]);
            } else {
                session()->forget(['filtro_tipos_data_inicial', 'filtro_tipos_data_final']);
            }
        }

        $dataInicial = session('filtro_tipos_data_inicial');
        $dataFinal = session('filtro_tipos_data_final');
        $tipoGrafico = session('filtro_tipos_grafico', 'bar');

        // 2. Constrói a consulta baseada no filtro
        $query = \Illuminate\Support\Facades\DB::table('retiradas')
            ->join('cardapio_horarios', 'retiradas.cardapio_horario_id', '=', 'cardapio_horarios.id')
            ->select(
                'retiradas.data_retirada',
                'cardapio_horarios.id as horario_id',
                \Illuminate\Support\Facades\DB::raw('COUNT(retiradas.id) as total')
            )
            ->whereNotNull('retiradas.cardapio_horario_id');

        if ($dataInicial && $dataFinal) {
            $query->whereBetween('retiradas.data_retirada', [$dataInicial, $dataFinal]);
        }

        $retiradas = $query->groupBy('retiradas.data_retirada', 'cardapio_horarios.id')->get();

        $consumoPorLanche = [];

        foreach ($retiradas as $retirada) {
            $data = $retirada->data_retirada;
            $horarioId = $retirada->horario_id;
            $diaSemana = \Carbon\Carbon::parse($data)->dayOfWeekIso;

            $itensExcecao = \Illuminate\Support\Facades\DB::table('cardapio_excecoes')
                ->join('cardapio_excecao_itens', 'cardapio_excecoes.id', '=', 'cardapio_excecao_itens.cardapio_excecao_id')
                ->join('item_contrato', 'cardapio_excecao_itens.item_contrato_uuid', '=', 'item_contrato.id')
                ->where('cardapio_excecoes.data_exata', $data)
                ->where('cardapio_excecoes.cardapio_horario_id', $horarioId)
                ->pluck('item_contrato.nome');

            if ($itensExcecao->isNotEmpty()) {
                // MUDANÇA AQUI: Removido o . " (Exceção)" para agrupar junto com os itens padrão
                $nomeLanche = $itensExcecao->unique()->implode(' + ');
            } else {
                $itensPadrao = \Illuminate\Support\Facades\DB::table('cardapio_itens_padrao')
                    ->join('item_contrato', 'cardapio_itens_padrao.item_contrato_uuid', '=', 'item_contrato.id')
                    ->where('cardapio_itens_padrao.cardapio_horario_id', $horarioId)
                    ->where('cardapio_itens_padrao.dia_semana', $diaSemana)
                    ->pluck('item_contrato.nome');

                $nomeLanche = $itensPadrao->isNotEmpty() ? $itensPadrao->unique()->implode(' + ') : "Sem Cardápio Registrado";
            }

            if (!isset($consumoPorLanche[$nomeLanche])) {
                $consumoPorLanche[$nomeLanche] = 0;
            }
            $consumoPorLanche[$nomeLanche] += $retirada->total;
        }

        // 3. Ordena o array para formar um Ranking
        arsort($consumoPorLanche);

        $labels = array_keys($consumoPorLanche);
        $valores = array_values($consumoPorLanche);

        if (empty($labels) && !$dataInicial) {
            // Ajustado o mock de dados também para remover o "(Exceção)"
            $labels = ['Iogurte e Banana', 'Pão de Batata e Suco', 'Cachorro Quente', 'Pão de Queijo e Chá', 'Frutas Variadas'];
            $valores = [420, 310, 290, 150, 80];
        }

        // Passamos também o $tipoGrafico para a view
        return view('dashboard.graficos.tipos-merenda', compact('labels', 'valores', 'dataInicial', 'dataFinal', 'tipoGrafico'));
    }
    public function porDiaSemana(\Illuminate\Http\Request $request)
    {
        // 1. Lógica de "Cache" na Sessão
        if ($request->has('limpar')) {
            session()->forget(['filtro_data_inicial', 'filtro_data_final', 'filtro_dias_grafico']);
            return redirect()->route('graficos.por_dia_semana');
        }

        // Se o formulário foi enviado
        if ($request->has('tipo_grafico')) {
            session(['filtro_dias_grafico' => $request->tipo_grafico]);

            // Se preencheu as datas, salva. Se deixou em branco, limpa as datas.
            if ($request->filled('data_inicial') && $request->filled('data_final')) {
                session(['filtro_data_inicial' => $request->data_inicial]);
                session(['filtro_data_final' => $request->data_final]);
            } else {
                session()->forget(['filtro_data_inicial', 'filtro_data_final']);
            }
        }

        $dataInicial = session('filtro_data_inicial');
        $dataFinal = session('filtro_data_final');
        $tipoGrafico = session('filtro_dias_grafico', 'pie');

        // 2. Constrói a consulta baseada no filtro
        $query = \Illuminate\Support\Facades\DB::table('retiradas')
            ->select('data_retirada', \Illuminate\Support\Facades\DB::raw('COUNT(id) as total'));

        if ($dataInicial && $dataFinal) {
            $query->whereBetween('data_retirada', [$dataInicial, $dataFinal]);
        }

        $retiradas = $query->groupBy('data_retirada')->get();

        // 3. Inicializa o array garantindo os dias úteis
        $consumoPorDia = [
            1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0,
        ];

        foreach ($retiradas as $retirada) {
            $diaIso = \Carbon\Carbon::parse($retirada->data_retirada)->dayOfWeekIso;
            if (!isset($consumoPorDia[$diaIso])) {
                $consumoPorDia[$diaIso] = 0;
            }
            $consumoPorDia[$diaIso] += $retirada->total;
        }

        $nomesDias = [
            1 => 'Segunda-feira', 2 => 'Terça-feira', 3 => 'Quarta-feira',
            4 => 'Quinta-feira', 5 => 'Sexta-feira', 6 => 'Sábado', 7 => 'Domingo'
        ];

        ksort($consumoPorDia);

        $labels = [];
        $valores = [];
        foreach ($consumoPorDia as $iso => $total) {
            $labels[] = $nomesDias[$iso];
            $valores[] = $total;
        }

        // Passamos o $tipoGrafico para a view
        return view('dashboard.graficos.por-dia-semana', compact('labels', 'valores', 'dataInicial', 'dataFinal', 'tipoGrafico'));
    }

    public function porTurma(\Illuminate\Http\Request $request)
    {
        // 1. Lógica de "Cache" na Sessão
        if ($request->has('limpar')) {
            session()->forget(['filtro_turma_data_inicial', 'filtro_turma_data_final', 'filtro_turma_grafico']);
            return redirect()->route('graficos.por_turma');
        }

        if ($request->has('tipo_grafico')) {
            session(['filtro_turma_grafico' => $request->tipo_grafico]);

            if ($request->filled('data_inicial') && $request->filled('data_final')) {
                session(['filtro_turma_data_inicial' => $request->data_inicial]);
                session(['filtro_turma_data_final' => $request->data_final]);
            } else {
                session()->forget(['filtro_turma_data_inicial', 'filtro_turma_data_final']);
            }
        }

        $dataInicial = session('filtro_turma_data_inicial');
        $dataFinal = session('filtro_turma_data_final');

        // Mantemos 'bar' como padrão pois costumam existir muitas turmas
        $tipoGrafico = session('filtro_turma_grafico', 'bar');

        // 2. Constrói a consulta cruzando Retiradas -> Alunos -> Cursos
        $query = \Illuminate\Support\Facades\DB::table('retiradas')
            ->join('alunos', 'retiradas.aluno_id', '=', 'alunos.id')
            ->join('cursos', 'alunos.curso_id', '=', 'cursos.id')
            ->select('cursos.nome as curso_nome', \Illuminate\Support\Facades\DB::raw('COUNT(retiradas.id) as total'));

        if ($dataInicial && $dataFinal) {
            $query->whereBetween('retiradas.data_retirada', [$dataInicial, $dataFinal]);
        }

        // Agrupa pelo nome do curso e já ordena do maior para o menor
        $retiradas = $query->groupBy('cursos.nome')
            ->orderByDesc('total')
            ->get();

        $labels = $retiradas->pluck('curso_nome')->toArray();
        $valores = $retiradas->pluck('total')->toArray();

        // Mock de dados caso o banco esteja vazio
        if (empty($labels) && !$dataInicial) {
            $labels = ['Técnico em Informática', 'Técnico em Administração', 'Técnico em Mecânica', 'Engenharia Civil', 'Licenciatura em Matemática'];
            $valores = [450, 380, 210, 150, 90];
        }

        return view('dashboard.graficos.por-turma', compact('labels', 'valores', 'dataInicial', 'dataFinal', 'tipoGrafico'));
    }
}
