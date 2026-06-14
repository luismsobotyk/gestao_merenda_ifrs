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
        $adminUsernamesString = env('ADMIN_LDAP_USERNAME');

        if (empty($adminUsernamesString)) {
            $this->command->warn('Atenção: A variável ADMIN_LDAP_USERNAME não foi definida no ficheiro .env. Nenhum administrador padrão foi criado.');
            return;
        }

        // Transforma a string separada por vírgulas num array e remove espaços em branco acidentais
        $adminUsernames = array_filter(array_map('trim', explode(',', $adminUsernamesString)));

        if (empty($adminUsernames)) {
            $this->command->warn('Atenção: Nenhum login válido foi encontrado na variável ADMIN_LDAP_USERNAME.');
            return;
        }

        foreach ($adminUsernames as $adminUsername) {
            $this->command->info("A procurar o administrador padrão '{$adminUsername}' no LDAP...");

            try {
                // Mantemos a busca pelo sAMAccountName conforme a sua infraestrutura
                $ldapUser = LdapUser::where('samaccountname', '=', $adminUsername)->first();

                if (!$ldapUser) {
                    // Usamos continue em vez de return para não impedir a criação dos restantes administradores da lista
                    $this->command->error("Erro: O utilizador '{$adminUsername}' não foi encontrado no servidor LDAP. O administrador não foi criado.");
                    continue;
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

                $this->command->info("Sucesso: O utilizador LDAP '{$nome}' ({$adminUsername}) foi autorizado como administrador!");

            } catch (\Exception $e) {
                $this->command->error("Não foi possível conectar ao servidor LDAP para verificar o administrador '{$adminUsername}'.");
                Log::error("Erro no LdapAdminSeeder para o utilizador {$adminUsername}: " . $e->getMessage());
            }
        }
    }
}
