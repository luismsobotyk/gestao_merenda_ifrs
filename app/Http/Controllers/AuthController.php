<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }

    public function loginSubmit(Request $request){
        $request->validate(
            [
                'username' => 'required',
                'password' => 'required'
            ],
            [
                'username.required' => 'O campo usuário é obrigatório.',
                'password.required' => 'O campo senha é obrigatório.'
            ]
        );

        $credentials = [
            'samaccountname' => $request->username,
            'password' => $request->password,
        ];

        // 1. O Laravel valida a senha diretamente no servidor LDAP
        if (Auth::attempt($credentials, $request->filled('remember'))) {

            // 2. SEGURANÇA: Verifica se este utilizador foi autorizado no nosso painel de "Gestão de Acessos"
            $localUser = \App\Models\User::where('username', $request->username)->first();

            if (!$localUser) {
                // A senha está correta no LDAP, mas ele não foi adicionado no sistema local
                Auth::logout();
                throw ValidationException::withMessages([
                    'username' => 'Credenciais inválidas'
                ]);
            }

            // 3. Grava o histórico usando o ID numérico correto da NOSSA tabela (ex: 1, 2, 3) em vez do UUID do LDAP
            \App\Models\LoginHistory::create([
                'user_id' => $localUser->id,
                'login_date' => now()->toDateString(),
                'login_time' => now()->toTimeString(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->intended('/home');
        }

        throw ValidationException::withMessages([
            'username' => [trans('auth.failed')]
        ]);
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
