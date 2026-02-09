<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use App\Models\Eleicao;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidatoController extends Controller
{
    public function index()
    {
        $candidatos = Candidato::with(['user', 'eleicao', 'cargo'])
                              ->latest()
                              ->paginate(20);
        
        return view('candidatos.index', compact('candidatos'));
    }

    public function create()
    {
        $eleicoes = Eleicao::where('status', 'agendada')
                          ->orWhere('status', 'ativa')
                          ->with('cargo')
                          ->get();
        
        return view('candidatos.create', compact('eleicoes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'eleicao_id' => 'required|exists:eleicoes,id',

            'numero_candidato' => 'required|string|max:20|unique:candidatos',
            'proposta' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

         $eleicao = Eleicao::with('cargo')->findOrFail($validated['eleicao_id']);
    $validated['cargo_id'] = $eleicao->cargo_id;

        // Verificar se o usuário já é candidato nesta eleição
        $jaCandidato = Candidato::where('user_id', $validated['user_id'])
                               ->where('eleicao_id', $validated['eleicao_id'])
                               ->exists();
        
        if ($jaCandidato) {
            return back()->withErrors(['user_id' => 'Este usuário já é candidato nesta eleição.']);
        }

        // Processar upload da foto
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('fotos_candidatos', 'public');
            $validated['foto'] = $path;
        }

        $candidato = Candidato::create($validated);

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'criar_candidato',
            'descricao' => "Criou candidatura para: {$candidato->user->name}",
            'ip_address' => $request->ip(),
            'dados_alterados' => $validated,
        ]);

        return redirect()->route('candidatos.index')
                         ->with('success', 'Candidatura registrada com sucesso!');
    }

    public function show(Candidato $candidato)
    {
        $votos = $candidato->votos()->with('user')->paginate(10);
        return view('candidatos.show', compact('candidato', 'votos'));
    }

    public function edit(Candidato $candidato)
    {
        if ($candidato->eleicao->status === 'concluida') {
            return back()->with('error', 'Não é possível editar candidatura de eleição concluída.');
        }

        $eleicoes = Eleicao::where('status', 'agendada')
                          ->orWhere('status', 'ativa')
                          ->with('cargo')
                          ->get();
        
        return view('candidatos.edit', compact('candidato', 'eleicoes'));
    }

    public function update(Request $request, Candidato $candidato)
    {
        if ($candidato->eleicao->status === 'concluida') {
            return back()->with('error', 'Não é possível editar candidatura de eleição concluída.');
        }

        $validated = $request->validate([
            'eleicao_id' => 'required|exists:eleicoes,id',
            'numero_candidato' => ['required', 'string', 'max:20', 'unique:candidatos,numero_candidato,' . $candidato->id],
            'proposta' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Processar upload da foto
        if ($request->hasFile('foto')) {
            // Remover foto antiga se existir
            if ($candidato->foto && Storage::disk('public')->exists($candidato->foto)) {
                Storage::disk('public')->delete($candidato->foto);
            }
            
            $path = $request->file('foto')->store('fotos_candidatos', 'public');
            $validated['foto'] = $path;
        }

        $oldData = $candidato->toArray();
        $candidato->update($validated);

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'atualizar_candidato',
            'descricao' => "Atualizou candidatura de: {$candidato->user->name}",
            'ip_address' => $request->ip(),
            'dados_alterados' => [
                'antes' => $oldData,
                'depois' => $candidato->toArray(),
            ],
        ]);

        return redirect()->route('candidatos.index')
                         ->with('success', 'Candidatura atualizada com sucesso!');
    }

    public function destroy(Request $request, Candidato $candidato)
    {
        if ($candidato->eleicao->status === 'ativa' || $candidato->eleicao->status === 'concluida') {
            return back()->with('error', 'Não é possível excluir candidatura de eleição ativa ou concluída.');
        }

        // Remover foto se existir
        if ($candidato->foto && Storage::disk('public')->exists($candidato->foto)) {
            Storage::disk('public')->delete($candidato->foto);
        }

        $candidatoNome = $candidato->user->name;
        $candidato->delete();

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'excluir_candidato',
            'descricao' => "Excluiu candidatura de: {$candidatoNome}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('candidatos.index')
                         ->with('success', 'Candidatura excluída com sucesso!');
    }

    public function aprovar(Request $request, Candidato $candidato)
    {
        if ($candidato->eleicao->status !== 'agendada') {
            return back()->with('error', 'Apenas candidaturas de eleições agendadas podem ser aprovadas.');
        }

        $candidato->aprovado = true;
        $candidato->motivo_reprovacao = null;
        $candidato->save();

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'aprovar_candidato',
            'descricao' => "Aprovou candidatura de: {$candidato->user->name}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Candidatura aprovada com sucesso!');
    }

    public function reprovar(Request $request, Candidato $candidato)
    {
        $validated = $request->validate([
            'motivo_reprovacao' => 'required|string|max:500',
        ]);

        $candidato->aprovado = false;
        $candidato->motivo_reprovacao = $validated['motivo_reprovacao'];
        $candidato->save();

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'reprovar_candidato',
            'descricao' => "Reprovou candidatura de: {$candidato->user->name}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Candidatura reprovada com sucesso!');
    }

    public function buscarUsuarios(Request $request)
    {
        $termo = $request->get('q');
        
        $usuarios = \App\Models\User::where('ativo', true)
                                   ->where(function($query) use ($termo) {
                                       $query->where('name', 'LIKE', "%{$termo}%")
                                             ->orWhere('email', 'LIKE', "%{$termo}%")
                                             ->orWhere('matricula', 'LIKE', "%{$termo}%");
                                   })
                                   ->limit(10)
                                   ->get(['id', 'name', 'email', 'matricula', 'categoria', 'curso']);
        
        return response()->json($usuarios);
    }
}