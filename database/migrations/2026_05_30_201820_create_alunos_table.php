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
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->string('matricula')->unique(); // Matricula como identificador único
            $table->string('nome');
            $table->string('login')->nullable(); // Útil caso haja integração com LDAP/AD no futuro

            // Relacionamento com a tabela de cursos local
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};
