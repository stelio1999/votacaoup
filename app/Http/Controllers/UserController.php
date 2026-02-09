<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(20);
        return view('usuarios.index', compact('users'));
    }

    public function create()
    {
        $categorias = ['estudante', 'docente', 'tecnico_administrativo'];
        $roles = ['admin', 'comissao', 'eleitor'];
        
        return view('usuarios.create', compact('categorias', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'comissao', 'eleitor'])],
            'categoria' => ['required', Rule::in(['estudante', 'docente', 'tecnico_administrativo'])],
            'matricula' => 'nullable|string|max:50',
            'curso' => 'nullable|string|max:255',
            'departamento' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['ativo'] = $request->has('ativo');

        $user = User::create($validated);

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'criar_usuario',
            'descricao' => "Criou o usuário: {$user->name}",
            'ip_address' => $request->ip(),
            'dados_alterados' => $validated,
        ]);

        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuário criado com sucesso!');
    }

    public function show(User $user)
    {
        $votos = $user->votos()->with('eleicao', 'candidato')->paginate(10);
        $candidaturas = $user->candidaturas()->with('eleicao')->paginate(10);
        $logs = $user->logs()->latest()->paginate(10);
        
        return view('usuarios.show', compact('user', 'votos', 'candidaturas', 'logs'));
    }

    public function edit(User $user)
    {
        $categorias = ['estudante', 'docente', 'tecnico_administrativo'];
        $roles = ['admin', 'comissao', 'eleitor'];
        
        return view('usuarios.edit', compact('user', 'categorias', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'comissao', 'eleitor'])],
            'categoria' => ['required', Rule::in(['estudante', 'docente', 'tecnico_administrativo'])],
            'matricula' => 'nullable|string|max:50',
            'curso' => 'nullable|string|max:255',
            'departamento' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:20',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $validated['ativo'] = $request->has('ativo');
        
        $oldData = $user->toArray();
        $user->update($validated);

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'atualizar_usuario',
            'descricao' => "Atualizou o usuário: {$user->name}",
            'ip_address' => $request->ip(),
            'dados_alterados' => [
                'antes' => $oldData,
                'depois' => $user->toArray(),
            ],
        ]);

        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(Request $request, User $user)
    {
        // Impedir exclusão do próprio usuário
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Não pode excluir a sua própria conta!');
        }

        $userName = $user->name;
        $user->delete();

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'excluir_usuario',
            'descricao' => "Excluiu o usuário: {$userName}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuário excluído com sucesso!');
    }

    public function toggleStatus(Request $request, User $user)
    {
        $user->ativo = !$user->ativo;
        $user->save();

        $status = $user->ativo ? 'ativado' : 'desativado';
        
        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'toggle_status_usuario',
            'descricao' => "{$status} o usuário: {$user->name}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Usuário {$status} com sucesso!");
    }
}