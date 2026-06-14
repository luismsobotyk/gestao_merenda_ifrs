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
        Schema::create('avaliacao_sus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('ldap_username')->index();

            $table->json('payload')->nullable();

            $table->unsignedTinyInteger('sus_score')->nullable();

            $table->timestamp('last_saved_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            $table->unique('ldap_username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaliacao_sus');
    }
};
