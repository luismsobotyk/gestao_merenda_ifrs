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
        Schema::create('pedido', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empenho_uuid')->constrained('empenho')->cascadeOnDelete();

            $table->dateTime('data_pedido');
            $table->dateTime('data_prevista_entrega')->nullable();
            $table->string('status')->default('Pendente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido');
    }
};
