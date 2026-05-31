<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aluno;
use App\Models\Retirada;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RetiradaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Busca todos os alunos já existentes na base de dados
        $alunos = Aluno::all();

        if ($alunos->isEmpty()) {
            $this->command->warn('Nenhum aluno encontrado! Crie alunos antes de rodar este seeder.');
            return;
        }

        // Configuração de quantos dias para trás queremos simular
        $diasParaSimular = 30;
        $hoje = Carbon::today();

        $this->command->info("A gerar retiradas para os últimos {$diasParaSimular} dias...");

        for ($i = 0; $i <= $diasParaSimular; $i++) {
            $dataAtual = $hoje->copy()->subDays($i);

            // Ignora sábados e domingos (fins de semana não costumam ter merenda)
            if ($dataAtual->isWeekend()) {
                continue;
            }

            // Descobre qual cardápio estava ativo neste dia específico
            $cardapio = DB::table('cardapios')
                ->where('data_inicio', '<=', $dataAtual->toDateString())
                ->where('data_fim', '>=', $dataAtual->toDateString())
                ->first();

            // Pega os IDs dos turnos (Manhã, Tarde, etc) do cardápio ativo.
            // Se não tiver cardápio na data, pega qualquer turno cadastrado no banco como "fallback" pra não quebrar o gráfico.
            if ($cardapio) {
                $horariosIds = DB::table('cardapio_horarios')->where('cardapio_id', $cardapio->id)->pluck('id');
            } else {
                $horariosIds = DB::table('cardapio_horarios')->pluck('id');
            }

            // Se o banco não tem nenhum horário cadastrado, aborta o dia
            if ($horariosIds->isEmpty()) {
                continue;
            }

            // Simula uma frequência diária: entre 40% e 80% dos alunos comem a cada dia
            $percentagemPresentes = rand(40, 80) / 100;
            $quantidadeAlunos = max(1, (int) ($alunos->count() * $percentagemPresentes));

            // Escolhe alunos aleatórios para o dia atual
            $alunosDoDia = $alunos->random($quantidadeAlunos);

            foreach ($alunosDoDia as $aluno) {
                // Escolhe um turno aleatório para este aluno (ex: sorteia se ele comeu de Manhã ou de Tarde)
                $horarioSorteado = $horariosIds->random();

                // updateOrCreate busca pelo Aluno + Data.
                // Se achar e estiver sem horário (NULL do seeder antigo), ele atualiza. Se não achar, ele cria.
                Retirada::updateOrCreate(
                    [
                        'aluno_id' => $aluno->id,
                        'data_retirada' => $dataAtual->toDateString(),
                    ],
                    [
                        'cardapio_horario_id' => $horarioSorteado
                    ]
                );
            }
        }

        $this->command->info('Dados de retiradas gerados e horários atualizados com sucesso!');
    }
}
