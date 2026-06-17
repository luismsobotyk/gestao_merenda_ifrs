<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RetiradaSeeder extends Seeder
{
    public function run(): void
    {
        $agora = Carbon::now();

        // =====================================================================
        // 1. BUSCAR DADOS EXISTENTES (Alunos e Horários)
        // =====================================================================
        $alunoIds = DB::table('alunos')->pluck('id')->toArray();
        $horarioIds = DB::table('cardapio_horarios')->pluck('id')->toArray();

        if (empty($alunoIds)) {
            $this->command->warn('Nenhum aluno encontrado no banco de dados. Pulei a geração de retiradas (Sincronize a API primeiro se quiser gerar estes gráficos).');
            return; // Interrompe a execução desta seeder
        }

        if (count($horarioIds) < 3) {
            $this->command->warn('Horários do cardápio não encontrados corretamente. Pulei a geração de retiradas.');
            return; // Interrompe a execução desta seeder
        }

        $this->command->info(count($alunoIds) . ' alunos reais encontrados. A gerar histórico de retiradas dinâmicas...');

        // =====================================================================
        // 2. GERADOR AUTOMÁTICO DE RETIRADAS DIVERSIFICADAS
        // =====================================================================
        $dataInicial = Carbon::create(2026, 3, 1);
        $dataFinal = Carbon::today(); // Hoje

        $insertBuffer = [];

        for ($data = $dataInicial->copy(); $data->lte($dataFinal); $data->addDay()) {
            // Pula finais de semana
            if ($data->isWeekend()) {
                continue;
            }

            // Sorteia uma presença entre 30% e 75% dos alunos para dar um ar real aos gráficos
            $porcentagemPresenca = rand(30, 75);
            $quantidadeAlunosNoDia = intval(($porcentagemPresenca / 100) * count($alunoIds));

            if ($quantidadeAlunosNoDia <= 0) {
                continue;
            }

            $alunosSorteados = collect($alunoIds)->shuffle()->take($quantidadeAlunosNoDia);

            foreach ($alunosSorteados as $alunoId) {
                // Lógica de pesos para os turnos (0 = Manhã, 1 = Tarde, 2 = Noite)
                $pesoTurno = rand(1, 10);
                if ($pesoTurno <= 5) {
                    $horarioSorteadoId = $horarioIds[0];
                } elseif ($pesoTurno <= 8) {
                    $horarioSorteadoId = $horarioIds[1];
                } else {
                    $horarioSorteadoId = $horarioIds[2];
                }

                $insertBuffer[] = [
                    'aluno_id' => $alunoId,
                    'data_retirada' => $data->toDateString(),
                    'cardapio_horario_id' => $horarioSorteadoId,
                    'created_at' => $agora,
                    'updated_at' => $agora,
                ];

                // Insere em lotes de 200 para não estourar a memória
                if (count($insertBuffer) >= 200) {
                    DB::table('retiradas')->insert($insertBuffer);
                    $insertBuffer = [];
                }
            }
        }

        // Insere o restante que sobrou no buffer
        if (count($insertBuffer) > 0) {
            DB::table('retiradas')->insert($insertBuffer);
        }

        $this->command->info('Histórico de retiradas gerado com sucesso!');
    }
}
