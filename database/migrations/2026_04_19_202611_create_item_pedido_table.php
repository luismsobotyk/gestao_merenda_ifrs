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
        Schema::create('item_pedido', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('pedido_uuid')->constrained('pedido')->cascadeOnDelete();
            $table->foreignUuid('item_empenho_uuid')->constrained('item_empenho')->cascadeOnDelete();

            $table->decimal('quantidade', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_pedido');
    }
};
