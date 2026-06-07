<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use Illuminate\Support\Facades\Log;

class LdapAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUsername = env('ADMIN_LDAP_USERNAME');

        if (!$adminUsername) {
            $this->command->warn('Atenção: A variável ADMIN_LDAP_USERNAME não foi definida no ficheiro .env. Nenhum administrador padrão foi criado.');
            return;
        }

        $this->command->info("A procurar o administrador padrão '{$adminUsername}' no LDAP...");

        try {
            $ldapUser = LdapUser::where('samaccountname', '=', $adminUsername)->first();

            if (!$ldapUser) {
                $this->command->error("Erro: O utilizador '{$adminUsername}' não foi encontrado no servidor LDAP. O administrador não foi criado.");
                return;
            }

            $nome = $ldapUser->getFirstAttribute('cn') ?? $ldapUser->getFirstAttribute('displayname') ?? 'Administrador Padrão';
            $email = $ldapUser->getFirstAttribute('mail') ?? 'admin@ifrs.edu.br';

            User::updateOrCreate(
                ['username' => $adminUsername],
                [
                    'name' => $nome,
                    'email' => $email,
                ]
            );

            $this->command->info("Sucesso: O utilizador LDAP '{$nome}' ({$adminUsername}) foi autorizado como administrador inicial!");

        } catch (\Exception $e) {
            $this->command->error('Não foi possível conectar ao servidor LDAP para criar o administrador padrão.');
            Log::error('Erro no LdapAdminSeeder: ' . $e->getMessage());
        }
    }
}
