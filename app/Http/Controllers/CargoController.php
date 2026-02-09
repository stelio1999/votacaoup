<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CargoController extends Controller
{
    public function index()
    {
        $cargos = Cargo::orderBy('nome')->paginate(20);
        return view('cargos.index', compact('cargos'));
    }

    public function create()
    {
        $categorias = ['estudante', 'docente', 'tecnico_administrativo'];
        return view('cargos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:cargos',
            'descricao' => 'nullable|string',
            'categoria' => ['required', Rule::in(['estudante', 'docente', 'tecnico_administrativo'])],
            'mandato_meses' => 'required|integer|min:1|max:48',
        ]);

        $validated['ativo'] = $request->has('ativo');

        $cargo = Cargo::create($validated);

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'criar_cargo',
            'descricao' => "Criou o cargo: {$cargo->nome}",
            'ip_address' => $request->ip(),
            'dados_alterados' => $validated,
        ]);

        return redirect()->route('cargos.index')
                         ->with('success', 'Cargo criado com sucesso!');
    }

    public function show(Cargo $cargo)
    {
        $eleicoes = $cargo->eleicoes()->withCount('votos')->paginate(10);
        return view('cargos.show', compact('cargo', 'eleicoes'));
    }

    public function edit(Cargo $cargo)
    {
        $categorias = ['estudante', 'docente', 'tecnico_administrativo'];
        return view('cargos.edit', compact('cargo', 'categorias'));
    }

    public function update(Request $request, Cargo $cargo)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255', Rule::unique('cargos')->ignore($cargo->id)],
            'descricao' => 'nullable|string',
            'categoria' => ['required', Rule::in(['estudante', 'docente', 'tecnico_administrativo'])],
            'mandato_meses' => 'required|integer|min:1|max:48',
        ]);

        $oldData = $cargo->toArray();
        $validated['ativo'] = $request->has('ativo');
        $cargo->update($validated);

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'atualizar_cargo',
            'descricao' => "Atualizou o cargo: {$cargo->nome}",
            'ip_address' => $request->ip(),
            'dados_alterados' => [
                'antes' => $oldData,
                'depois' => $cargo->toArray(),
            ],
        ]);

        return redirect()->route('cargos.index')
                         ->with('success', 'Cargo atualizado com sucesso!');
    }

    public function destroy(Request $request, Cargo $cargo)
    {
        // Verificar se o cargo está sendo usado em eleições
        if ($cargo->eleicoes()->exists()) {
            return back()->with('error', 'Não é possível excluir este cargo porque está sendo usado em eleições.');
        }

        $cargoNome = $cargo->nome;
        $cargo->delete();

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'excluir_cargo',
            'descricao' => "Excluiu o cargo: {$cargoNome}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('cargos.index')
                         ->with('success', 'Cargo excluído com sucesso!');
    }

    public function toggleStatus(Request $request, Cargo $cargo)
    {
        $cargo->ativo = !$cargo->ativo;
        $cargo->save();

        $status = $cargo->ativo ? 'ativado' : 'desativado';
        
        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'toggle_status_cargo',
            'descricao' => "{$status} o cargo: {$cargo->nome}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Cargo {$status} com sucesso!");
    }
}