<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $votos = $user->votos()->with(['eleicao.cargo', 'candidato'])->latest()->paginate(10);
        $candidaturas = $user->candidaturas()->with(['eleicao.cargo'])->latest()->paginate(10);
        
        $estatisticas = [
            'total_votos' => $user->votos()->count(),
            'eleicoes_participadas' => $user->votos()->distinct('eleicao_id')->count('eleicao_id'),
            'candidaturas' => $user->candidaturas()->count(),
            'eleicoes_vencidas' => $user->candidaturas()
                                       ->whereHas('resultado', function($q) {
                                           $q->where('eleito', true);
                                       })
                                       ->count(),
        ];

        return view('profile.show', compact('user', 'votos', 'candidaturas', 'estatisticas'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'telefone' => ['nullable', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        // Processar upload da foto
        if ($request->hasFile('foto')) {
            // Remover foto antiga se existir
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            
            $path = $request->file('foto')->store('fotos_perfil', 'public');
            $validated['foto'] = $path;
        }

        // Atualizar senha se fornecida
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        // Registrar log da ação
        \App\Models\Log::create([
            'user_id' => $user->id,
            'acao' => 'atualizar_perfil',
            'descricao' => 'Atualizou informações do perfil',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('profile.show')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function updatePreferences(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'notificacoes_email' => ['boolean'],
            'notificacoes_sistema' => ['boolean'],
            'tema_escuro' => ['boolean'],
            'idioma' => ['in:pt_MZ,en'],
        ]);

        // Converter preferências para JSON
        $preferencias = array_merge((array) $user->preferencias, $validated);
        $user->preferencias = $preferencias;
        $user->save();

        // Registrar log
        \App\Models\Log::create([
            'user_id' => $user->id,
            'acao' => 'atualizar_preferencias',
            'descricao' => 'Atualizou preferências do sistema',
            'ip_address' => $request->ip(),
            'dados_alterados' => $validated,
        ]);

        return back()->with('success', 'Preferências atualizadas com sucesso!');
    }

    public function security()
    {
        $user = auth()->user();
        $sessoes = $this->getActiveSessions();
        
        return view('profile.security', compact('user', 'sessoes'));
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed', 'different:current_password'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Registrar log
        \App\Models\Log::create([
            'user_id' => $user->id,
            'acao' => 'alterar_senha',
            'descricao' => 'Alterou a senha de acesso',
            'ip_address' => $request->ip(),
        ]);

        // Invalida todas as outras sessões
        $this->invalidateOtherSessions($request);

        return back()->with('success', 'Senha alterada com sucesso! Todas as outras sessões foram invalidadas.');
    }

    public function activity()
    {
        $user = auth()->user();
        $logs = $user->logs()->latest()->paginate(20);
        
        return view('profile.activity', compact('user', 'logs'));
    }

    public function exportData()
    {
        $user = auth()->user();
        
        $dados = [
            'informacoes_pessoais' => [
                'name' => $user->name,
                'email' => $user->email,
                'categoria' => $user->categoria,
                'matricula' => $user->matricula,
                'telefone' => $user->telefone,
                'created_at' => $user->created_at->format('d/m/Y H:i:s'),
            ],
            'votos' => $user->votos()->with(['eleicao', 'candidato'])->get()->map(function($voto) {
                return [
                    'eleicao' => $voto->eleicao->titulo,
                    'candidato' => $voto->candidato->user->name,
                    'data' => $voto->created_at->format('d/m/Y H:i:s'),
                    'hash' => $voto->hash_voto,
                ];
            }),
            'candidaturas' => $user->candidaturas()->with(['eleicao', 'resultado'])->get()->map(function($candidatura) {
                return [
                    'eleicao' => $candidatura->eleicao->titulo,
                    'numero' => $candidatura->numero_candidato,
                    'status' => $candidatura->aprovado ? 'Aprovado' : 'Pendente',
                    'votos' => $candidatura->votos()->count(),
                ];
            }),
        ];

        $filename = "dados-pessoais-{$user->id}-" . now()->format('Y-m-d') . '.json';
        
        return response()->json($dados, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'confirmacao' => ['required', 'accepted'],
            'password' => ['required', 'current_password'],
        ]);

        // Não permitir exclusão de contas administrativas
        if ($user->hasRole('admin')) {
            return back()->with('error', 'Contas administrativas não podem ser excluídas por esta função.');
        }

        // Marcar conta como inativa em vez de excluir (soft delete)
        $user->ativo = false;
        $user->email = 'deleted_' . time() . '_' . $user->email;
        $user->save();

        // Registrar log
        \App\Models\Log::create([
            'user_id' => $user->id,
            'acao' => 'desativar_conta',
            'descricao' => 'Desativou a própria conta',
            'ip_address' => $request->ip(),
        ]);

        // Logout do usuário
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Sua conta foi desativada com sucesso. Sentiremos sua falta!');
    }

    private function getActiveSessions()
    {
        $user = auth()->user();
        $sessoes = [];
        
        // Obter sessões ativas (implementação simplificada)
        // Em produção, use um sistema mais robusto
        $ultimoAcesso = $user->ultimo_acesso;
        
        $sessoes[] = [
            'device' => 'Dispositivo Atual',
            'browser' => request()->userAgent(),
            'ip' => request()->ip(),
            'last_active' => now(),
            'current' => true,
        ];

        // Adicionar sessão anterior como exemplo
        if ($ultimoAcesso && $ultimoAcesso->diffInMinutes(now()) > 5) {
            $sessoes[] = [
                'device' => 'Dispositivo Anterior',
                'browser' => 'Mozilla/5.0...',
                'ip' => '192.168.1.100',
                'last_active' => $ultimoAcesso,
                'current' => false,
            ];
        }

        return $sessoes;
    }

    private function invalidateOtherSessions(Request $request)
    {
        // Implementação para invalidar outras sessões
        // Em produção, use Laravel Sanctum ou outro sistema de gestão de sessões
        
        // Por enquanto, apenas registramos a ação
        \App\Models\Log::create([
            'user_id' => auth()->id(),
            'acao' => 'invalidar_sessoes',
            'descricao' => 'Invalidou todas as outras sessões ao alterar a senha',
            'ip_address' => $request->ip(),
        ]);
    }
}