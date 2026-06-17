<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UnidadeSeeder extends Seeder
{
    public function run(): void
    {
        $agora = Carbon::now();

        DB::table('unidade')->insert([
            ['id' => Str::uuid()->toString(), 'descricao' => 'Quilograma', 'sigla' => 'KG', 'created_at' => $agora],
            ['id' => Str::uuid()->toString(), 'descricao' => 'Unidade', 'sigla' => 'UN', 'created_at' => $agora],
        ]);
    }
}
