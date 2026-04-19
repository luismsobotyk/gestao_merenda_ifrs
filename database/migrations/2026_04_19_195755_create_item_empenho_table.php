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
        Schema::create('item_empenho', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empenho_uuid')->constrained('empenho')->cascadeOnDelete();
            $table->foreignUuid('item_contrato_uuid')->constrained('item_contrato')->restrictOnDelete();

            $table->decimal('quantidade_empenhada', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_empenho');
    }
};
