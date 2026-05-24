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
        Schema::create('cardapio_itens_padrao', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cardapio_horario_id')->constrained('cardapio_horarios')->cascadeOnDelete();

            $table->foreignUuid('item_contrato_uuid')->constrained('item_contrato')->restrictOnDelete();
            $table->integer('dia_semana'); // 1 = Segunda, 2 = Terça, 3 = Quarta, 4 = Quinta, 5 = Sexta
            $table->decimal('quantidade_estimada_porcao', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cardapio_itens_padrao');
    }
};
