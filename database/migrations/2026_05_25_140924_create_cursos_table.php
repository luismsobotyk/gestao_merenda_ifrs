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
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_curso')->unique(); // O ID que vem da API do IFRS
            $table->string('codigo')->nullable();
            $table->string('nome'); // Campo "curso" da API
            $table->string('nivel')->nullable();
            $table->string('turno')->nullable();
            $table->boolean('direito_merenda')->default(false); // O nosso controle local!
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
