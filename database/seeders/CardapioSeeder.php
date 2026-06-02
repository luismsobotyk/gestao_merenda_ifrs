<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CardapioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agora = Carbon::now();

        // 1. Garantir que os itens do contrato existem e capturar seus UUIDs
        $nomesAlimentos = [
            'Iogurte',
            'Maçã',
            'Ovos',
            'Banana',
            'Pão de Batata',
            'Feijão Preto'
        ];

        $itensUuid = [];

        foreach ($nomesAlimentos as $nome) {
            $item = DB::table('item_contrato')->where('nome', $nome)->first();

            if (!$item) {
                $uuid = Str::uuid()->toString();
                DB::table('item_contrato')->insert([
                    'id' => $uuid,
                    'nome' => $nome,
                    'created_at' => $agora,
                    'updated_at' => $agora,
                ]);
                $itensUuid[$nome] = $uuid;
            } else {
                $itensUuid[$nome] = $item->id;
            }
        }

        // 2. Criar o Cardápio Principal
        $cardapioId = Str::uuid()->toString();

        DB::table('cardapios')->insert([
            'id' => $cardapioId,
            'nome' => '2026/1',
            'data_inicio' => '2026-01-01',
            'data_fim' => '2026-06-30',
            'created_at' => $agora,
            'updated_at' => $agora,
        ]);

        // 3. Definir os Horários
        $horarios = [
            ['nome' => 'MANHÃ', 'inicio' => '10:10:00', 'fim' => '10:25:00', 'desc' => 'Geral'],
            ['nome' => 'TARDE', 'inicio' => '15:30:00', 'fim' => '15:45:00', 'desc' => 'Geral'],
            ['nome' => 'NOITE', 'inicio' => '20:30:00', 'fim' => '20:40:00', 'desc' => 'Geral'],
        ];

        // 4. Definir a Grade Semanal Padrão (Igual para todos os turnos conforme o print)
        $gridSemanal = [
            1 => ['Iogurte', 'Maçã'],          // Segunda
            2 => ['Ovos', 'Banana'],           // Terça
            3 => ['Pão de Batata', 'Feijão Preto'], // Quarta
            4 => ['Iogurte', 'Maçã'],          // Quinta
            5 => ['Ovos', 'Banana'],           // Sexta
        ];

        // 5. Inserir Horários e os Itens da Grade
        foreach ($horarios as $h) {
            $horarioId = Str::uuid()->toString();

            DB::table('cardapio_horarios')->insert([
                'id' => $horarioId,
                'cardapio_id' => $cardapioId,
                'nome' => $h['nome'],
                'hora_inicio' => $h['inicio'],
                'hora_fim' => $h['fim'],
                'descricao_publico' => $h['desc'],
                'created_at' => $agora,
                'updated_at' => $agora,
            ]);

            foreach ($gridSemanal as $diaSemana => $alimentos) {
                foreach ($alimentos as $alimento) {
                    DB::table('cardapio_itens_padrao')->insert([
                        'id' => Str::uuid()->toString(),
                        'cardapio_horario_id' => $horarioId,
                        'item_contrato_uuid' => $itensUuid[$alimento],
                        'dia_semana' => $diaSemana,
                        'quantidade_estimada_porcao' => null,
                        'created_at' => $agora,
                        'updated_at' => $agora,
                    ]);
                }
            }
        }
    }
}
