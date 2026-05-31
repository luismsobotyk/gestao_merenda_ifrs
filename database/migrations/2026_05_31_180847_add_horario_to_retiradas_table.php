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
        Schema::table('retiradas', function (Blueprint $table) {
            // Permite nulo inicial apenas para não quebrar os registros que já existem no seu banco
            $table->foreignUuid('cardapio_horario_id')->nullable()->constrained('cardapio_horarios')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retiradas', function (Blueprint $table) {
            $table->dropForeign(['cardapio_horario_id']);
            $table->dropColumn('cardapio_horario_id');
        });
    }
};
