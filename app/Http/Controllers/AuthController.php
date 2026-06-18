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

        if (Auth::attempt($credentials, $request->filled('remember'))) {

            $localUser = \App\Models\User::where('username', $request->username)->first();

            if (!$localUser) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'username' => 'Credenciais inválidas'
                ]);
            }

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
