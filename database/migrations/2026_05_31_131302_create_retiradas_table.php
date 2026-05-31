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
        Schema::create('retiradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->cascadeOnDelete();

            // Usamos um campo do tipo 'date' puro para facilitar a busca por "hoje"
            $table->date('data_retirada');

            $table->timestamps();

            // Garante que o aluno não consiga registrar duas retiradas no mesmo dia
            $table->unique(['aluno_id', 'data_retirada']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retiradas');
    }
};
