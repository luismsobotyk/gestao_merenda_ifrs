<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CardapioSeeder extends Seeder
{
    public function run(): void
    {
        $agora = Carbon::now();

        // =====================================================================
        // 1. RECUPERA OS ITENS DO CONTRATO PARA O CARDÁPIO
        // =====================================================================
        $nomesAlimentos = ['Iogurte', 'Maçã', 'Suco de Caju', 'Banana', 'Pão de Batata', 'Pastel Assado'];
        $itensContratoUuids = [];

        foreach ($nomesAlimentos as $nome) {
            $itemId = DB::table('item_contrato')->where('nome', $nome)->value('id');
            if ($itemId) {
                $itensContratoUuids[$nome] = $itemId;
            } else {
                $this->command->error("Item '{$nome}' não encontrado no banco. O cardápio pode ficar incompleto.");
            }
        }

        // =====================================================================
        // 2. CARDÁPIO (01/01/2026 a 31/07/2026)
        // =====================================================================
        $cardapioId = Str::uuid()->toString();
        DB::table('cardapios')->insert([
            'id' => $cardapioId,
            'nome' => 'Semestre 2026/1',
            'data_inicio' => '2026-01-01',
            'data_fim' => '2026-07-31',
            'created_at' => $agora,
        ]);

        $turnos = [
            ['nome' => 'MANHÃ', 'inicio' => '10:10:00', 'fim' => '10:25:00'],
            ['nome' => 'TARDE', 'inicio' => '15:30:00', 'fim' => '15:45:00'],
            ['nome' => 'NOITE', 'inicio' => '20:30:00', 'fim' => '20:40:00'],
        ];

        $gridSemanal = [
            1 => ['Iogurte', 'Maçã'],
            2 => ['Suco de Caju', 'Banana'],
            3 => ['Pão de Batata', 'Pastel Assado'],
            4 => ['Iogurte', 'Maçã'],
            5 => ['Suco de Caju', 'Banana'],
        ];

        foreach ($turnos as $turno) {
            $horarioId = Str::uuid()->toString();

            DB::table('cardapio_horarios')->insert([
                'id' => $horarioId,
                'cardapio_id' => $cardapioId,
                'nome' => $turno['nome'],
                'hora_inicio' => $turno['inicio'],
                'hora_fim' => $turno['fim'],
                'descricao_publico' => 'Geral',
                'created_at' => $agora,
            ]);

            foreach ($gridSemanal as $dia => $nomesAlimentosDia) {
                foreach ($nomesAlimentosDia as $nome) {
                    if (isset($itensContratoUuids[$nome])) {
                        DB::table('cardapio_itens_padrao')->insert([
                            'id' => Str::uuid()->toString(),
                            'cardapio_horario_id' => $horarioId,
                            'item_contrato_uuid' => $itensContratoUuids[$nome],
                            'dia_semana' => $dia,
                            'created_at' => $agora,
                        ]);
                    }
                }
            }
        }
    }
}
