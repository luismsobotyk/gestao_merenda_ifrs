<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
// IMPORTAMOS O MODELO DO ACTIVE DIRECTORY DO LDAPRECORD
use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use LdapRecord\Models\Entry as LdapEntry;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('name')->get();
        return view('dashboard.usuarios.index', compact('usuarios'));
    }

    // --- NOVO MÉTODO: Pesquisa no LDAP ---
    public function searchLdap(Request $request)
    {
        $term = $request->get('term');

        if (!$term || strlen($term) < 3) {
            return response()->json([]);
        }

        try {
            // 2. Usamos LdapEntry para não filtrar pelo objectClass=user
            // 3. Adicionamos a busca pelo 'uid' (onde normalmente ficam os CPFs)
            $ldapUsers = LdapEntry::where('cn', 'contains', $term)
                ->orWhere('samaccountname', 'contains', $term)
                ->orWhere('uid', 'contains', $term)
                ->limit(15) // Aumentei um pouco o limite para turmas grandes
                ->get();

            $results = [];

            foreach ($ldapUsers as $user) {
                // 4. Captura o login, dando prioridade ao samaccountname, com fallback para o uid
                $login = $user->getFirstAttribute('samaccountname') ?? $user->getFirstAttribute('uid');

                // Só adiciona ao resultado se tiver um login válido
                if ($login) {
                    $results[] = [
                        'name' => $user->getFirstAttribute('cn') ?? $user->getFirstAttribute('displayname') ?? 'Utilizador sem nome',
                        'username' => $login,
                        'email' => $user->getFirstAttribute('mail') ?? 'sem-email@ifrs.edu.br',
                    ];
                }
            }

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Falha ao comunicar com o servidor LDAP.'], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email',
        ], [
            'username.unique' => 'Este login já se encontra registado e autorizado.',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Utilizador autorizado com sucesso!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->username === env('ADMIN_LDAP_USERNAME')) {
            return back()->withErrors('Operação negada: Não é possível revogar o acesso do Administrador Principal do sistema.');
        }

        // Bloqueia a auto-exclusão para utilizadores normais
        if (auth()->id() === $user->id) {
            return back()->withErrors('Não é possível remover a sua própria conta.');
        }

        $user->delete();
        return redirect()->route('usuarios.index')->with('success', 'Acesso revogado com sucesso!');
    }

    public function history($id)
    {
        $user = User::with(['loginHistories' => function($query) {
            $query->orderBy('login_date', 'desc')->orderBy('login_time', 'desc');
        }])->findOrFail($id);

        return view('dashboard.usuarios.history', compact('user'));
    }
}
