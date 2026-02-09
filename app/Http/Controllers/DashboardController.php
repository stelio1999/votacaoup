<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Eleicao;
use App\Models\Candidato;
use App\Models\Voto;
use App\Models\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Estatísticas gerais
        $totalEleitores = User::where('ativo', true)->count();
        $eleicoesAtivas = Eleicao::ativas()->count();
        $totalCandidatos = Candidato::whereHas('eleicao', function($q) {
            $q->where('status', 'ativa');
        })->count();
        
        $votosHoje = Voto::whereDate('created_at', Carbon::today())->count();
        
        // Atividades recentes
        $atividades = Log::with('user')
                        ->latest()
                        ->take(10)
                        ->get();
        
        // Eleições ativas com estatísticas
        $eleicoes = Eleicao::ativas()
                          ->withCount('votos')
                          ->orderBy('data_fim')
                          ->take(5)
                          ->get();
        
        // Usuários online (últimos 5 minutos)
        $usuariosOnline = User::where('ultimo_acesso', '>=', Carbon::now()->subMinutes(5))
                             ->count();

        return view('dashboard.index', compact(
            'totalEleitores',
            'eleicoesAtivas',
            'totalCandidatos',
            'votosHoje',
            'atividades',
            'eleicoes',
            'usuariosOnline'
        ));
    }

    public function estatisticas()
    {
        // Estatísticas detalhadas para gráficos
        $votosPorDia = Voto::selectRaw('DATE(created_at) as data, COUNT(*) as total')
                          ->where('created_at', '>=', Carbon::now()->subDays(30))
                          ->groupBy('data')
                          ->orderBy('data')
                          ->get()
                          ->pluck('total', 'data');

        $usuariosPorCategoria = User::where('ativo', true)
                                   ->selectRaw('categoria, COUNT(*) as total')
                                   ->groupBy('categoria')
                                   ->get()
                                   ->pluck('total', 'categoria');

        $eleicoesPorStatus = Eleicao::selectRaw('status, COUNT(*) as total')
                                   ->groupBy('status')
                                   ->get()
                                   ->pluck('total', 'status');

        return response()->json([
            'votosPorDia' => $votosPorDia,
            'usuariosPorCategoria' => $usuariosPorCategoria,
            'eleicoesPorStatus' => $eleicoesPorStatus,
        ]);
    }
}