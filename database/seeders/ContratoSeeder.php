<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContratoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Criar as Unidades de Medida (Vamos reaproveitar essas para os 10 novos)
        $unidadeKgId = Str::uuid();
        $unidadeUnId = Str::uuid();

        DB::table('unidade')->insert([
            ['id' => $unidadeKgId, 'descricao' => 'Quilograma', 'sigla' => 'kg', 'created_at' => now(), 'updated_at' => now()],
            ['id' => $unidadeUnId, 'descricao' => 'Unidade', 'sigla' => 'un', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // =========================================================================
        // ENTRADA PRINCIPAL MANUAL (COOMAVIT) - MANTIDA INTACTA
        // =========================================================================

        $fornecedorId = Str::uuid();
        DB::table('fornecedor')->insert([
            'id' => $fornecedorId, 'cnpj' => '12.345.678/0001-90', 'nome' => 'Coomavit', 'sigla' => 'COOMAVIT', 'created_at' => now(), 'updated_at' => now(),
        ]);

        $responsavelId = Str::uuid();
        DB::table('fornecedor_responsavel')->insert([
            'id' => $responsavelId, 'fornecedor_uuid' => $fornecedorId, 'nome' => 'Contato Principal', 'is_principal' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('fornecedor_responsavel_contato')->insert([
            'id' => Str::uuid(), 'fornecedor_responsavel_id' => $responsavelId, 'tipo' => 'Email', 'valor' => 'contato@coomavit.com.br', 'created_at' => now(), 'updated_at' => now(),
        ]);

        $contratoId = Str::uuid();
        DB::table('contrato')->insert([
            'id' => $contratoId, 'fornecedor_id' => $fornecedorId, 'processo' => '23344.001234/2026-10', 'inicio_vigencia' => '2026-01-01', 'fim_vigencia' => '2026-12-31', 'valor_global' => 150000.00, 'status' => 'Vigente', 'pregao' => '05/2026', 'created_at' => now(), 'updated_at' => now(),
        ]);

        $itemBananaId = Str::uuid();
        $itemMacaId = Str::uuid();
        $itemPaoId = Str::uuid();

        DB::table('item_contrato')->insert([
            ['id' => $itemBananaId, 'contrato_uuid' => $contratoId, 'unidade_uuid' => $unidadeKgId, 'nome' => 'Banana', 'quantidade' => 2000.00, 'valor_unitario' => 6.28, 'created_at' => now(), 'updated_at' => now()],
            ['id' => $itemMacaId, 'contrato_uuid' => $contratoId, 'unidade_uuid' => $unidadeKgId, 'nome' => 'Maçã', 'quantidade' => 1500.00, 'valor_unitario' => 12.90, 'created_at' => now(), 'updated_at' => now()],
            ['id' => $itemPaoId, 'contrato_uuid' => $contratoId, 'unidade_uuid' => $unidadeUnId, 'nome' => 'Pão de Batata', 'quantidade' => 2000.00, 'valor_unitario' => 6.90, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $empenho1Id = Str::uuid();
        $empenho2Id = Str::uuid();
        $empenho3Id = Str::uuid();

        DB::table('empenho')->insert([
            ['id' => $empenho1Id, 'contrato_uuid' => $contratoId, 'numero_empenho' => '2026NE00123', 'valor_total' => 5966.00, 'valor_utilizado' => 2512.00, 'created_at' => now(), 'updated_at' => now()],
            ['id' => $empenho2Id, 'contrato_uuid' => $contratoId, 'numero_empenho' => '2026NE00122', 'valor_total' => 9030.00, 'valor_utilizado' => 8385.00, 'created_at' => now(), 'updated_at' => now()],
            ['id' => $empenho3Id, 'contrato_uuid' => $contratoId, 'numero_empenho' => '2025NE00005', 'valor_total' => 6900.00, 'valor_utilizado' => 6900.00, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $itemEmpBananaId = Str::uuid();
        $itemEmpMacaId = Str::uuid();

        DB::table('item_empenho')->insert([
            ['id' => $itemEmpBananaId, 'empenho_uuid' => $empenho1Id, 'item_contrato_uuid' => $itemBananaId, 'quantidade_empenhada' => 950.00, 'created_at' => now(), 'updated_at' => now()],
            ['id' => $itemEmpMacaId, 'empenho_uuid' => $empenho2Id, 'item_contrato_uuid' => $itemMacaId, 'quantidade_empenhada' => 700.00, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'empenho_uuid' => $empenho3Id, 'item_contrato_uuid' => $itemPaoId, 'quantidade_empenhada' => 1000.00, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $pedido1Id = Str::uuid();
        $pedido2Id = Str::uuid();

        DB::table('pedido')->insert([
            ['id' => $pedido1Id, 'empenho_uuid' => $empenho1Id, 'data_pedido' => '2026-04-29 08:00:00', 'data_prevista_entrega' => '2026-05-08 12:00:00', 'status' => 'Aguardando', 'created_at' => now(), 'updated_at' => now()],
            ['id' => $pedido2Id, 'empenho_uuid' => $empenho2Id, 'data_pedido' => '2026-03-20 08:00:00', 'data_prevista_entrega' => '2026-04-02 12:00:00', 'status' => 'Atrasado', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('item_pedido')->insert([
            ['id' => Str::uuid(), 'pedido_uuid' => $pedido1Id, 'item_empenho_uuid' => $itemEmpBananaId, 'quantidade' => 70.00, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'pedido_uuid' => $pedido2Id, 'item_empenho_uuid' => $itemEmpMacaId, 'quantidade' => 50.00, 'created_at' => now(), 'updated_at' => now()],
        ]);


        // =========================================================================
        // LOOP PARA GERAR +10 CONTRATOS ALEATÓRIOS COM O FAKER
        // =========================================================================

        $faker = \Faker\Factory::create('pt_BR'); // Instancia o gerador de dados brasileiros

        // Lista de alimentos para sortear
        $alimentos = ['Feijão Preto', 'Arroz Parboilizado', 'Carne Moída', 'Leite Integral', 'Macarrão', 'Óleo de Soja', 'Biscoito Doce', 'Ovos', 'Frango Congelado', 'Iogurte'];
        $statusContrato = ['Vigente', 'Vigente', 'Vigente', 'Encerrado', 'Pausado']; // Mais chance de cair vigente

        for ($i = 0; $i < 10; $i++) {

            // 1. Fornecedor Dinâmico
            $novoFornecedorId = Str::uuid();
            $nomeFornecedor = $faker->company;
            DB::table('fornecedor')->insert([
                'id' => $novoFornecedorId,
                'cnpj' => $faker->unique()->cnpj,
                'nome' => $nomeFornecedor,
                'sigla' => strtoupper(substr($nomeFornecedor, 0, 4)),
                'created_at' => now(), 'updated_at' => now(),
            ]);

            // 2. Responsável Dinâmico
            $novoRespId = Str::uuid();
            DB::table('fornecedor_responsavel')->insert([
                'id' => $novoRespId, 'fornecedor_uuid' => $novoFornecedorId, 'nome' => $faker->name, 'is_principal' => true, 'created_at' => now(), 'updated_at' => now(),
            ]);

            DB::table('fornecedor_responsavel_contato')->insert([
                'id' => Str::uuid(), 'fornecedor_responsavel_id' => $novoRespId, 'tipo' => 'Email', 'valor' => $faker->companyEmail, 'created_at' => now(), 'updated_at' => now(),
            ]);

            // 3. Contrato Dinâmico
            $novoContratoId = Str::uuid();
            $anoPregao = $faker->numberBetween(2023, 2026);
            DB::table('contrato')->insert([
                'id' => $novoContratoId,
                'fornecedor_id' => $novoFornecedorId,
                'processo' => '23344.' . $faker->randomNumber(6, true) . '/' . $anoPregao . '-' . $faker->randomNumber(2, true),
                'inicio_vigencia' => $anoPregao . '-01-01',
                'fim_vigencia' => $anoPregao . '-12-31',
                'valor_global' => $faker->randomFloat(2, 20000, 300000), // Entre 20 mil e 300 mil
                'status' => $faker->randomElement($statusContrato),
                'pregao' => $faker->numberBetween(1, 20) . '/' . $anoPregao,
                'created_at' => now(), 'updated_at' => now(),
            ]);

            // 4. Um Item Dinâmico para o contrato
            $novoItemId = Str::uuid();
            DB::table('item_contrato')->insert([
                'id' => $novoItemId,
                'contrato_uuid' => $novoContratoId,
                'unidade_uuid' => $unidadeKgId,
                'nome' => $faker->randomElement($alimentos),
                'quantidade' => $faker->randomFloat(2, 500, 3000),
                'valor_unitario' => $faker->randomFloat(2, 4, 30),
                'created_at' => now(), 'updated_at' => now()
            ]);

            // 5. Um Empenho Dinâmico
            $novoEmpenhoId = Str::uuid();
            DB::table('empenho')->insert([
                'id' => $novoEmpenhoId,
                'contrato_uuid' => $novoContratoId,
                'numero_empenho' => $anoPregao . 'NE' . $faker->randomNumber(5, true),
                'valor_total' => $faker->randomFloat(2, 5000, 15000),
                'valor_utilizado' => $faker->randomFloat(2, 1000, 4000),
                'created_at' => now(), 'updated_at' => now(),
            ]);

            // 6. Vinculando item ao empenho
            $novoItemEmpenhoId = Str::uuid();
            DB::table('item_empenho')->insert([
                'id' => $novoItemEmpenhoId,
                'empenho_uuid' => $novoEmpenhoId,
                'item_contrato_uuid' => $novoItemId,
                'quantidade_empenhada' => $faker->randomFloat(2, 100, 500),
                'created_at' => now(), 'updated_at' => now(),
            ]);

            // 7. Pedido Dinâmico
            $novoPedidoId = Str::uuid();
            DB::table('pedido')->insert([
                'id' => $novoPedidoId,
                'empenho_uuid' => $novoEmpenhoId,
                'data_pedido' => now()->subDays($faker->numberBetween(2, 30)),
                'data_prevista_entrega' => now()->addDays($faker->numberBetween(1, 10)),
                'status' => $faker->randomElement(['Pendente', 'Aguardando', 'Atrasado', 'Recebido']),
                'created_at' => now(), 'updated_at' => now(),
            ]);

            DB::table('item_pedido')->insert([
                'id' => Str::uuid(),
                'pedido_uuid' => $novoPedidoId,
                'item_empenho_uuid' => $novoItemEmpenhoId,
                'quantidade' => $faker->randomFloat(2, 10, 100),
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }
}
