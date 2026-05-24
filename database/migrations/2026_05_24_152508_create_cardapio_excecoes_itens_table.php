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
        Schema::create('cardapio_excecao_itens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cardapio_excecao_id')->constrained('cardapio_excecoes')->cascadeOnDelete();
            $table->foreignUuid('item_contrato_uuid')->constrained('item_contrato')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cardapio_excecao_itens');
    }
};
