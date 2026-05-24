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
        Schema::create('cardapio_horarios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cardapio_id')->constrained('cardapios')->cascadeOnDelete();

            $table->string('nome'); // Ex: MANHÃ (M)
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->string('descricao_publico')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cardapio_horarios');
    }
};
