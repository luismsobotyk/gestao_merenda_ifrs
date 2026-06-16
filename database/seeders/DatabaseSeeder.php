<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================================================
        // 1. CHAMA OS SEEDERS EXTERNOS PRIMEIRO (A ORDEM IMPORTA!)
        // =====================================================================
        $this->call([
            LdapAdminSeeder::class, // Cria o Super Admin primeiro
            // Se tiver o CardapioSeeder separado, pode chamá-lo aqui também
        ]);

        $agora = Carbon::now();

        // =====================================================================
        // 2. UNIDADES DE MEDIDA
        // =====================================================================
        $unidadeKgId = Str::uuid()->toString();
        $unidadeUnId = Str::uuid()->toString();

        DB::table('unidade')->insert([
            ['id' => $unidadeKgId, 'descricao' => 'Quilograma', 'sigla' => 'KG', 'created_at' => $agora],
            ['id' => $unidadeUnId, 'descricao' => 'Unidade', 'sigla' => 'UN', 'created_at' => $agora],
        ]);

        // =====================================================================
        // 3. FORNECEDORES
        // =====================================================================
        $fornecedores = [
            ['id' => Str::uuid()->toString(), 'cnpj' => '11.111.111/0001-11', 'nome' => 'Laticínios Sul', 'sigla' => 'LATSUL'],
            ['id' => Str::uuid()->toString(), 'cnpj' => '22.222.222/0001-22', 'nome' => 'Hortifruti Fazenda', 'sigla' => 'HORTI'],
            ['id' => Str::uuid()->toString(), 'cnpj' => '33.333.333/0001-33', 'nome' => 'Mercearia Atacadista', 'sigla' => 'MERC'],
        ];

        foreach ($fornecedores as $f) {
            DB::table('fornecedor')->insert(array_merge($f, ['created_at' => $agora]));
        }

        // =====================================================================
        // 4. CONTRATOS, ITENS, EMPENHOS E PEDIDOS
        // =====================================================================
        $alimentos = [
            0 => [
                ['nome' => 'Iogurte', 'unidade' => $unidadeUnId, 'qtd' => 5000, 'preco' => 1.50],
                ['nome' => 'Pão de Batata', 'unidade' => $unidadeUnId, 'qtd' => 4000, 'preco' => 2.00]
            ],
            1 => [
                ['nome' => 'Maçã', 'unidade' => $unidadeKgId, 'qtd' => 200, 'preco' => 6.50],
                ['nome' => 'Banana', 'unidade' => $unidadeKgId, 'qtd' => 300, 'preco' => 4.00]
            ],
            2 => [
                // Trocado Ovos por Suco de Caju
                ['nome' => 'Suco de Caju', 'unidade' => $unidadeUnId, 'qtd' => 6000, 'preco' => 0.80],
                // Trocado Feijão Preto por Pastel Assado (e unidade alterada de KG para UN)
                ['nome' => 'Pastel Assado', 'unidade' => $unidadeUnId, 'qtd' => 5000, 'preco' => 3.50]
            ]
        ];

        $itensContratoUuids = [];

        foreach ($fornecedores as $index => $forn) {
            $contratoId = Str::uuid()->toString();

            // Calcula os valores reais antes de inserir o contrato
            $valorGlobalContrato = 0;
            $valorTotalEmpenho = 0;

            foreach ($alimentos[$index] as $ali) {
                $valorGlobalContrato += ($ali['qtd'] * $ali['preco']);
                $valorTotalEmpenho += (($ali['qtd'] / 2) * $ali['preco']); // O empenho é metade da qtd contratada
            }

            DB::table('contrato')->insert([
                'id' => $contratoId,
                'fornecedor_id' => $forn['id'],
                'processo' => '23354.' . rand(1000,9999) . '/2025-01',
                'inicio_vigencia' => '2025-12-01',
                'fim_vigencia' => '2026-12-01',
                'valor_global' => $valorGlobalContrato,
                'status' => 'Vigente',
                'pregao' => 'PE 0' . ($index + 1) . '/2025',
                'created_at' => $agora,
            ]);

            $empenhoId = Str::uuid()->toString();
            DB::table('empenho')->insert([
                'id' => $empenhoId,
                'contrato_uuid' => $contratoId,
                'numero_empenho' => '2025NE00' . rand(100, 999),
                'valor_total' => $valorTotalEmpenho,
                'created_at' => $agora,
            ]);

            $pedidoId = Str::uuid()->toString();
            DB::table('pedido')->insert([
                'id' => $pedidoId,
                'contrato_uuid' => $contratoId,
                'data_pedido' => '2026-01-10 10:00:00',
                'data_prevista_entrega' => '2026-01-20 10:00:00',
                'status' => 'Entregue',
                'created_at' => $agora,
            ]);

            foreach ($alimentos[$index] as $ali) {
                $itemContratoId = Str::uuid()->toString();
                $itemEmpenhoId = Str::uuid()->toString();
                $itensContratoUuids[$ali['nome']] = $itemContratoId;

                DB::table('item_contrato')->insert([
                    'id' => $itemContratoId,
                    'contrato_uuid' => $contratoId,
                    'unidade_uuid' => $ali['unidade'],
                    'nome' => $ali['nome'],
                    'quantidade' => $ali['qtd'],
                    'valor_unitario' => $ali['preco'],
                    'created_at' => $agora,
                ]);

                DB::table('item_empenho')->insert([
                    'id' => $itemEmpenhoId,
                    'empenho_uuid' => $empenhoId,
                    'item_contrato_uuid' => $itemContratoId,
                    'quantidade_empenhada' => $ali['qtd'] / 2,
                    'created_at' => $agora,
                ]);

                DB::table('item_pedido')->insert([
                    'id' => Str::uuid()->toString(),
                    'pedido_uuid' => $pedidoId,
                    'item_empenho_uuid' => $itemEmpenhoId,
                    'quantidade' => $ali['qtd'] / 4,
                    'created_at' => $agora,
                ]);
            }
        }

        // =====================================================================
        // 5. CARDÁPIO (01/01/2026 a 31/07/2026)
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

        // Atualizado para refletir os novos alimentos
        $gridSemanal = [
            1 => ['Iogurte', 'Maçã'],
            2 => ['Suco de Caju', 'Banana'],
            3 => ['Pão de Batata', 'Pastel Assado'],
            4 => ['Iogurte', 'Maçã'],
            5 => ['Suco de Caju', 'Banana'],
        ];

        $horarioIds = [];

        foreach ($turnos as $turno) {
            $horarioId = Str::uuid()->toString();
            $horarioIds[] = $horarioId;

            DB::table('cardapio_horarios')->insert([
                'id' => $horarioId,
                'cardapio_id' => $cardapioId,
                'nome' => $turno['nome'],
                'hora_inicio' => $turno['inicio'],
                'hora_fim' => $turno['fim'],
                'descricao_publico' => 'Geral',
                'created_at' => $agora,
            ]);

            foreach ($gridSemanal as $dia => $nomesAlimentos) {
                foreach ($nomesAlimentos as $nome) {
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

        // =====================================================================
        // 6. BUSCAR ALUNOS EXISTENTES NO BANCO (Sincronizados da API)
        // =====================================================================
        $alunoIds = DB::table('alunos')->pluck('id')->toArray();

        if (empty($alunoIds)) {
            $this->command->warn('Nenhum aluno encontrado no banco de dados. Pulei a geração de retiradas (Sincronize a API primeiro se quiser gerar estes gráficos).');
        } else {
            $this->command->info(count($alunoIds) . ' alunos reais encontrados. A gerar histórico de retiradas dinâmicas...');

            // =====================================================================
            // 7. GERADOR AUTOMÁTICO DE RETIRADAS DIVERSIFICADAS (Março até Hoje)
            // =====================================================================
            $dataInicial = Carbon::create(2026, 3, 1);
            $dataFinal = Carbon::today(); // Hoje

            $insertBuffer = [];

            for ($data = $dataInicial->copy(); $data->lte($dataFinal); $data->addDay()) {
                if ($data->isWeekend()) {
                    continue;
                }

                $porcentagemPresenca = rand(30, 75);
                $quantidadeAlunosNoDia = intval(($porcentagemPresenca / 100) * count($alunoIds));

                if ($quantidadeAlunosNoDia <= 0) {
                    continue;
                }

                $alunosSorteados = collect($alunoIds)->shuffle()->take($quantidadeAlunosNoDia);

                foreach ($alunosSorteados as $alunoId) {
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

                    if (count($insertBuffer) >= 200) {
                        DB::table('retiradas')->insert($insertBuffer);
                        $insertBuffer = [];
                    }
                }
            }

            if (count($insertBuffer) > 0) {
                DB::table('retiradas')->insert($insertBuffer);
            }
        }

        // =====================================================================
        // 8. CONFIGURAÇÕES
        // =====================================================================
        DB::table('configuracoes_retirada')->insert([
            ['chave' => 'modo_totem_ativo', 'descricao' => 'Autoatendimento', 'valor' => '1', 'created_at' => $agora],
            ['chave' => 'modo_manual_ativo', 'descricao' => 'Lançamento Manual', 'valor' => '1', 'created_at' => $agora],
        ]);

        $this->command->info('Base de dados secundária populada com sucesso!');
    }
}
