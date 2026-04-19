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
        Schema::create('contrato', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('fornecedor_id')->constrained('fornecedor')->restrictOnDelete();

            $table->string('processo');
            $table->date('inicio_vigencia');
            $table->date('fim_vigencia');
            $table->decimal('valor_global', 15, 2);
            $table->string('status');
            $table->string('pregao');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrato');
    }
};
