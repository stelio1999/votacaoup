<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Verificar se o usuário está ativo
            if (!$user->ativo) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'A sua conta está desativada. Contacte o administrador.',
                ]);
            }

            // Atualizar último acesso
            $user->registrarAcesso();

            // Registrar log de login
            Log::create([
                'user_id' => $user->id,
                'acao' => 'login',
                'descricao' => 'Login no sistema',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registos.',
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            // Registrar log de logout
            Log::create([
                'user_id' => Auth::id(),
                'acao' => 'logout',
                'descricao' => 'Logout do sistema',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}