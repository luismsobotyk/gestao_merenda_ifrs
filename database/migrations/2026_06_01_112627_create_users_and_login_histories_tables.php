<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabela de Usuários (Sincronizada com o LDAP)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // A coluna username será utilizada para armazenar o sAMAccountName do LDAP
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('guid')->unique()->nullable();
            $table->string('password')->nullable(); // Nullable porque a validação real ocorre no ADDC
            $table->rememberToken();
            $table->timestamps();
        });

        // Tabela de Histórico de Logins
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->date('login_date');
            $table->time('login_time');

            // O método ipAddress do Laravel suporta nativamente IPv4 e IPv6
            $table->ipAddress('ip_address');

            // Opcional, mas muito útil para auditoria de segurança
            $table->string('user_agent')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_histories');
        Schema::dropIfExists('users');
    }
};
