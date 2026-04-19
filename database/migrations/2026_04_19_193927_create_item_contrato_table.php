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
        Schema::create('item_contrato', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('contrato_uuid')->constrained('contrato')->cascadeOnDelete();
            $table->foreignUuid('unidade_uuid')->constrained('unidade')->restrictOnDelete();

            $table->string('nome');
            $table->decimal('quantidade', 10, 2);
            $table->decimal('valor_unitario', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_contrato');
    }
};
