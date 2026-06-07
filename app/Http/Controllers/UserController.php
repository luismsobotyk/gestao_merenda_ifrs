<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
// IMPORTAMOS O MODELO DO ACTIVE DIRECTORY DO LDAPRECORD
use LdapRecord\Models\ActiveDirectory\User as LdapUser;

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
            // Procura no LDAP por Nome (cn) ou Login (samaccountname)
            $ldapUsers = LdapUser::where('cn', 'contains', $term)
                ->orWhere('samaccountname', 'contains', $term)
                ->limit(10)
                ->get();

            $results = [];

            foreach ($ldapUsers as $user) {
                // Só adiciona se o utilizador tiver sAMAccountName
                if ($user->hasAttribute('samaccountname')) {
                    $results[] = [
                        'name' => $user->getFirstAttribute('cn') ?? $user->getFirstAttribute('displayname'),
                        'username' => $user->getFirstAttribute('samaccountname'),
                        'email' => $user->getFirstAttribute('mail') ?? 'sem-email@ifrs.edu.br', // Fallback caso não tenha e-mail no AD
                    ];
                }
            }

            return response()->json($results);
        } catch (\Exception $e) {
            // Em caso de erro de conexão com o AD, retorna erro amigável para o JS
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
