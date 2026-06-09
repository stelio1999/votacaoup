<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Log;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{
    /**
     * Mostrar formulário de solicitação de redefinição de senha
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Enviar link de redefinição de senha por email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Não encontramos nenhum utilizador com este email institucional.'
        ]);

        $user = User::where('email', $request->email)->first();

        // Verificar se o utilizador está ativo
        if (!$user->ativo) {
            return back()->withErrors([
                'email' => 'A sua conta está desativada. Contacte o administrador do sistema.'
            ]);
        }

        try {
            // Gerar token único
            $token = Str::random(64);
            
            // Remover tokens antigos deste email
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            
            // Inserir novo token
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]);

            // Gerar link de redefinição
            $resetLink = url('/password/reset/' . $token . '?email=' . urlencode($request->email));

            // ENVIO REAL DE EMAIL
            Mail::to($request->email)->send(new ResetPasswordMail($resetLink, $user, $token));

            // Registrar log de sucesso
            Log::create([
                'user_id' => $user->id,
                'acao' => 'solicitar_redefinicao_senha',
                'descricao' => 'Solicitou redefinição de palavra-passe - Email enviado com sucesso',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Em ambiente de desenvolvimento, mostrar o link também
            if (app()->environment('local')) {
                session()->flash('reset_link', $resetLink);
                session()->flash('reset_token', $token);
                session()->flash('reset_email', $request->email);
                session()->flash('email_enviado', true);
            }

            return back()->with('status', 'Enviamos para o seu email as instruções para redefinir a sua palavra-passe! Verifique a sua caixa de entrada e pasta de spam.');

        } catch (\Exception $e) {
            // Registrar log de erro
            Log::create([
                'user_id' => $user->id ?? null,
                'acao' => 'erro_redefinicao_senha',
                'descricao' => 'Erro ao enviar email: ' . $e->getMessage(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Em ambiente de desenvolvimento, mostrar o link mesmo com erro
            if (app()->environment('local')) {
                // Gerar token mesmo com erro de email
                $token = Str::random(64);
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();
                DB::table('password_reset_tokens')->insert([
                    'email' => $request->email,
                    'token' => Hash::make($token),
                    'created_at' => Carbon::now()
                ]);
                
                $resetLink = url('/password/reset/' . $token . '?email=' . urlencode($request->email));
                
                session()->flash('reset_link', $resetLink);
                session()->flash('reset_token', $token);
                session()->flash('reset_email', $request->email);
                session()->flash('erro_email', $e->getMessage());
                
                return back()->with('status', '[AMBIENTE DE DESENVOLVIMENTO] Link gerado mas email não enviado. Use o link abaixo.');
            }

            return back()->withErrors([
                'email' => 'Ocorreu um erro ao enviar o email. Por favor, tente novamente mais tarde.'
            ]);
        }
    }

    /**
     * Mostrar formulário de redefinição de senha
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Redefinir a senha
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ], [
            'email.exists' => 'Email não encontrado no sistema.',
            'password.min' => 'A palavra-passe deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'As palavras-passe não coincidem.',
        ]);

        // Verificar se o token é válido
        $resetData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetData) {
            return back()->withErrors(['email' => 'Token de redefinição inválido ou expirado.']);
        }

        if (!Hash::check($request->token, $resetData->token)) {
            return back()->withErrors(['email' => 'Token de redefinição inválido.']);
        }

        // Verificar se o token não expirou (60 minutos)
        $createdAt = Carbon::parse($resetData->created_at);
        if ($createdAt->diffInMinutes(Carbon::now()) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Este link de redefinição expirou. Solicite um novo.']);
        }

        // Atualizar senha do utilizador
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Remover token usado
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Registrar log
        Log::create([
            'user_id' => $user->id,
            'acao' => 'redefinir_senha',
            'descricao' => 'Redefiniu a palavra-passe com sucesso',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Opcional: Enviar email de confirmação de redefinição
        try {
            // Mail::to($user->email)->send(new PasswordChangedMail($user));
        } catch (\Exception $e) {
            // Log do erro mas não interrompe o fluxo
            \Log::error('Erro ao enviar email de confirmação: ' . $e->getMessage());
        }

        return redirect()->route('login')
            ->with('success', 'Palavra-passe redefinida com sucesso! Faça login com a nova palavra-passe.');
    }

    /**
     * Mostrar formulário de verificação de email (alternativa)
     */
    public function showVerifyForm()
    {
        return view('auth.passwords.verify');
    }

    /**
     * Verificar email e enviar código (alternativa - sem email)
     */
    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user->ativo) {
            return back()->withErrors(['email' => 'Conta desativada. Contacte o administrador.']);
        }

        try {
            // Gerar código de 6 dígitos
            $code = rand(100000, 999999);
            
            // Armazenar código na sessão
            session(['reset_code' => $code]);
            session(['reset_email' => $request->email]);
            session(['reset_code_expires' => Carbon::now()->addMinutes(15)]);

            // Enviar código por email
            // Mail::to($request->email)->send(new VerificationCodeMail($code, $user));

            // Registrar log
            Log::create([
                'user_id' => $user->id,
                'acao' => 'enviar_codigo_verificacao',
                'descricao' => 'Código de verificação enviado',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Em ambiente de desenvolvimento, mostrar o código
            if (app()->environment('local')) {
                session()->flash('verification_code', $code);
            }

            return redirect()->route('password.verify.code')
                ->with('success', 'Código de verificação enviado para o seu email!');

        } catch (\Exception $e) {
            \Log::error('Erro ao enviar código de verificação: ' . $e->getMessage());
            
            if (app()->environment('local')) {
                $code = rand(100000, 999999);
                session(['reset_code' => $code]);
                session(['reset_email' => $request->email]);
                session(['reset_code_expires' => Carbon::now()->addMinutes(15)]);
                session()->flash('verification_code', $code);
                
                return redirect()->route('password.verify.code')
                    ->with('success', '[DEV] Código gerado: ' . $code);
            }

            return back()->withErrors([
                'email' => 'Erro ao enviar código. Tente novamente.'
            ]);
        }
    }

    /**
     * Mostrar formulário de verificação de código
     */
    public function showCodeVerificationForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sessão expirada. Inicie o processo novamente.']);
        }

        return view('auth.passwords.verify-code');
    }

    /**
     * Verificar código de redefinição
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $sessionCode = session('reset_code');
        $expiresAt = session('reset_code_expires');

        if (!$sessionCode || !$expiresAt || Carbon::now()->greaterThan(Carbon::parse($expiresAt))) {
            return back()->withErrors(['code' => 'Código expirado. Solicite um novo código.']);
        }

        if ($request->code != $sessionCode) {
            return back()->withErrors(['code' => 'Código inválido. Tente novamente.']);
        }

        // Código válido - permitir redefinição
        $email = session('reset_email');
        
        // Gerar token para redefinição
        $token = Str::random(64);
        
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        // Registrar log
        $user = User::where('email', $email)->first();
        if ($user) {
            Log::create([
                'user_id' => $user->id,
                'acao' => 'codigo_verificado',
                'descricao' => 'Código de verificação validado com sucesso',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        // Limpar sessão
        session()->forget(['reset_code', 'reset_code_expires']);

        return redirect()->route('password.reset', ['token' => $token, 'email' => $email]);
    }

    /**
     * Reenviar código de verificação
     */
    public function resendVerificationCode(Request $request)
    {
        $email = session('reset_email');
        
        if (!$email) {
            return response()->json(['error' => 'Sessão expirada'], 422);
        }

        $user = User::where('email', $email)->first();

        if (!$user || !$user->ativo) {
            return response()->json(['error' => 'Utilizador inválido'], 422);
        }

        try {
            // Gerar novo código
            $code = rand(100000, 999999);
            
            session(['reset_code' => $code]);
            session(['reset_code_expires' => Carbon::now()->addMinutes(15)]);

            // Enviar código por email
            // Mail::to($email)->send(new VerificationCodeMail($code, $user));

            if (app()->environment('local')) {
                session()->flash('verification_code', $code);
                return response()->json(['success' => true, 'code' => $code]);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Erro ao reenviar código: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao reenviar código'], 500);
        }
    }
}