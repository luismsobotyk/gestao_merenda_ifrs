<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ContratoSeeder extends Seeder
{
    public function run(): void
    {
        $agora = Carbon::now();

        $unidadeKgId = DB::table('unidade')->where('sigla', 'KG')->value('id');
        $unidadeUnId = DB::table('unidade')->where('sigla', 'UN')->value('id');

        if (!$unidadeKgId || !$unidadeUnId) {
            $this->command->error('Unidades KG ou UN não encontradas. Verifique a UnidadeSeeder antes de rodar os Contratos.');
            return;
        }

        $fornecedores = [
            ['id' => Str::uuid()->toString(), 'cnpj' => '11.111.111/0001-11', 'nome' => 'Laticínios Sul', 'sigla' => 'LATSUL'],
            ['id' => Str::uuid()->toString(), 'cnpj' => '22.222.222/0001-22', 'nome' => 'Hortifruti Fazenda', 'sigla' => 'HORTI'],
            ['id' => Str::uuid()->toString(), 'cnpj' => '33.333.333/0001-33', 'nome' => 'Mercearia Atacadista', 'sigla' => 'MERC'],
        ];

        foreach ($fornecedores as $f) {
            DB::table('fornecedor')->insert(array_merge($f, ['created_at' => $agora]));
        }

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
                ['nome' => 'Suco de Caju', 'unidade' => $unidadeUnId, 'qtd' => 6000, 'preco' => 0.80],
                ['nome' => 'Pastel Assado', 'unidade' => $unidadeUnId, 'qtd' => 5000, 'preco' => 3.50]
            ]
        ];

        foreach ($fornecedores as $index => $forn) {
            $contratoId = Str::uuid()->toString();

            $valorGlobalContrato = 0;
            $valorTotalEmpenho = 0;

            foreach ($alimentos[$index] as $ali) {
                $valorGlobalContrato += ($ali['qtd'] * $ali['preco']);
                $valorTotalEmpenho += (($ali['qtd'] / 2) * $ali['preco']);
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
    }
}
