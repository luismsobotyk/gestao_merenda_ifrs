<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConfiguracaoSeeder extends Seeder
{
    public function run(): void
    {
        $agora = Carbon::now();

        DB::table('configuracoes_retirada')->insert([
            ['chave' => 'modo_totem_ativo', 'descricao' => 'Autoatendimento', 'valor' => '1', 'created_at' => $agora],
            ['chave' => 'modo_manual_ativo', 'descricao' => 'Lançamento Manual', 'valor' => '1', 'created_at' => $agora],
        ]);

        $this->command->info('Configurações do sistema geradas com sucesso!');
    }
}
