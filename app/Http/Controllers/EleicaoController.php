<?php

namespace App\Http\Controllers;

use App\Models\Eleicao;
use App\Models\Cargo;
use App\Models\Candidato;
use App\Models\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class EleicaoController extends Controller
{
    public function index()
    {
        $eleicoes = Eleicao::with('cargo')
                          ->latest()
                          ->paginate(20);
        
        $estatisticas = [
            'total' => Eleicao::count(),
            'ativas' => Eleicao::ativas()->count(),
            'concluidas' => Eleicao::concluidas()->count(),
            'agendadas' => Eleicao::agendadas()->count(),
        ];
        
        return view('eleicoes.index', compact('eleicoes', 'estatisticas'));
    }

    public function create()
    {
        $cargos = Cargo::where('ativo', true)->get();
        return view('eleicoes.create', compact('cargos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'cargo_id' => 'required|exists:cargos,id',
            'data_inicio' => 'required|date|after:now',
            'data_fim' => 'required|date|after:data_inicio',
            'observacoes' => 'nullable|string',
        ]);

        $validated['status'] = 'agendada';
        $validated['total_eleitores'] = 0; // Será calculado depois
        
        $eleicao = Eleicao::create($validated);

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'criar_eleicao',
            'descricao' => "Criou a eleição: {$eleicao->titulo}",
            'ip_address' => $request->ip(),
            'dados_alterados' => $validated,
        ]);

        return redirect()->route('eleicoes.index')
                         ->with('success', 'Eleição criada com sucesso!');
    }

    public function show(Eleicao $eleicao)
    {
        $candidatos = $eleicao->candidatos()
                             ->with('user')
                             ->where('aprovado', true)
                             ->get();
        
        $estatisticas = [
            'total_votos' => $eleicao->votos_registrados,
            'percentual_conclusao' => $eleicao->percentual_conclusao,
            'total_eleitores' => $eleicao->total_eleitores,
            'candidatos' => $candidatos->count(),
        ];
        
        $votosPorCandidato = $eleicao->votos()
                                    ->selectRaw('candidato_id, COUNT(*) as total')
                                    ->groupBy('candidato_id')
                                    ->with('candidato.user')
                                    ->get();
        
        return view('eleicoes.show', compact('eleicao', 'candidatos', 'estatisticas', 'votosPorCandidato'));
    }

    public function edit(Eleicao $eleicao)
    {
        if ($eleicao->status === 'concluida') {
            return back()->with('error', 'Não é possível editar uma eleição concluída.');
        }
        
        $cargos = Cargo::where('ativo', true)->get();
        return view('eleicoes.edit', compact('eleicao', 'cargos'));
    }

    public function update(Request $request, Eleicao $eleicao)
    {
        if ($eleicao->status === 'concluida') {
            return back()->with('error', 'Não é possível editar uma eleição concluída.');
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'cargo_id' => 'required|exists:cargos,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after:data_inicio',
            'observacoes' => 'nullable|string',
            'status' => ['required', 'in:agendada,ativa,concluida,cancelada'],
        ]);

        $oldData = $eleicao->toArray();
        $eleicao->update($validated);

        // Atualizar estatísticas se necessário
        if (in_array($eleicao->status, ['ativa', 'concluida'])) {
            $eleicao->atualizarEstatisticas();
        }

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'atualizar_eleicao',
            'descricao' => "Atualizou a eleição: {$eleicao->titulo}",
            'ip_address' => $request->ip(),
            'dados_alterados' => [
                'antes' => $oldData,
                'depois' => $eleicao->toArray(),
            ],
        ]);

        return redirect()->route('eleicoes.index')
                         ->with('success', 'Eleição atualizada com sucesso!');
    }

    public function destroy(Request $request, Eleicao $eleicao)
    {
        if ($eleicao->status === 'ativa') {
            return back()->with('error', 'Não é possível excluir uma eleição ativa.');
        }

        $titulo = $eleicao->titulo;
        $eleicao->delete();

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'excluir_eleicao',
            'descricao' => "Excluiu a eleição: {$titulo}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('eleicoes.index')
                         ->with('success', 'Eleição excluída com sucesso!');
    }

    public function iniciar(Request $request, Eleicao $eleicao)
    {
        if ($eleicao->status !== 'agendada') {
            return back()->with('error', 'Apenas eleições agendadas podem ser iniciadas.');
        }

        if ($eleicao->candidatos()->where('aprovado', true)->count() < 2) {
            return back()->with('error', 'É necessário pelo menos 2 candidatos aprovados para iniciar a eleição.');
        }

        $eleicao->status = 'ativa';
        $eleicao->data_inicio = now();
        $eleicao->save();

        // Calcular total de eleitores
        $eleicao->total_eleitores = \App\Models\User::where('categoria', $eleicao->cargo->categoria)
                                                   ->where('ativo', true)
                                                   ->count();
        $eleicao->save();

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'iniciar_eleicao',
            'descricao' => "Iniciou a eleição: {$eleicao->titulo}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Eleição iniciada com sucesso!');
    }

    public function encerrar(Request $request, Eleicao $eleicao)
    {
        if ($eleicao->status !== 'ativa') {
            return back()->with('error', 'Apenas eleições ativas podem ser encerradas.');
        }

        $eleicao->status = 'concluida';
        $eleicao->data_fim = now();
        $eleicao->save();
        $eleicao->calcularResultados();

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'encerrar_eleicao',
            'descricao' => "Encerrou a eleição: {$eleicao->titulo}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Eleição encerrada com sucesso! Resultados calculados.');
    }

    public function candidatos(Eleicao $eleicao)
    {
        $candidatos = $eleicao->candidatos()
                             ->with('user')
                             ->paginate(20);
        
        return view('eleicoes.candidatos', compact('eleicao', 'candidatos'));
    }

    public function estatisticas(Eleicao $eleicao)
    {
        return response()->json([
            'votos_por_hora' => $eleicao->votos()
                                       ->selectRaw('HOUR(created_at) as hora, COUNT(*) as total')
                                       ->groupBy('hora')
                                       ->orderBy('hora')
                                       ->get(),
            'participacao_por_categoria' => $eleicao->votos()
                                                   ->join('users', 'votos.user_id', '=', 'users.id')
                                                   ->selectRaw('users.categoria, COUNT(*) as total')
                                                   ->groupBy('users.categoria')
                                                   ->get(),
        ]);
    }
}