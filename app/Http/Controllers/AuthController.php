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
