<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chama a classe que acabamos de criar
        $this->call([
            ContratoSeeder::class,
        ]);
    }
}
