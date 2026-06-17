<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================================================
        // ORQUESTRAÇÃO DAS SEEDERS DE DOMÍNIO (A ORDEM IMPORTA!)
        // =====================================================================
        $this->call([
//            LdapAdminSeeder::class,    // 1. Cria o Super Admin
            ConfiguracaoSeeder::class, // 2. Configurações base do sistema
            UnidadeSeeder::class,      // 3. Cria as Unidades de Medida
            ContratoSeeder::class,     // 4. Cria Fornecedores, Contratos e Itens
            CardapioSeeder::class,     // 5. Cria o Cardápio e as Grades Semanais
            RetiradaSeeder::class,     // 6. Gera o histórico dinâmico de consumo
        ]);

        $this->command->info('Base de dados populada com sucesso!');
    }
}
