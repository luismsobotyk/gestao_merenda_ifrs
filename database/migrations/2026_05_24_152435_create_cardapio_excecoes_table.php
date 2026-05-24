<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cardapio_excecoes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cardapio_id')->constrained('cardapios')->cascadeOnDelete();
            $table->foreignUuid('cardapio_horario_id')->constrained('cardapio_horarios')->cascadeOnDelete();

            $table->date('data_exata');
            $table->enum('tipo', ['inclusao', 'substituicao']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cardapio_excecoes');
    }
};
