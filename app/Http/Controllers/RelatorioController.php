<?php

namespace App\Http\Controllers;

use App\Models\Eleicao;
use App\Models\User;
use App\Models\Voto;
use App\Models\Candidato;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    public function index()
    {
        $periodos = [
            'hoje' => 'Hoje',
            'semana' => 'Última Semana',
            'mes' => 'Último Mês',
            'trimestre' => 'Último Trimestre',
            'ano' => 'Último Ano',
        ];

        return view('relatorios.index', compact('periodos'));
    }

    public function gerar(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:eleicoes,usuarios,votos,auditoria',
            'periodo' => 'required|in:hoje,semana,mes,trimestre,ano,todos',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'eleicao_id' => 'nullable|exists:eleicoes,id',
            'formato' => 'required|in:tela,pdf,excel,csv',
        ]);

        // Definir período de datas
        $datas = $this->definirPeriodo($validated['periodo'], $validated['data_inicio'] ?? null, $validated['data_fim'] ?? null);

        // Gerar relatório conforme o tipo
        switch ($validated['tipo']) {
            case 'eleicoes':
                $dados = $this->relatorioEleicoes($datas['inicio'], $datas['fim'], $validated['eleicao_id'] ?? null);
                $titulo = 'Relatório de Eleições';
                break;
            case 'usuarios':
                $dados = $this->relatorioUsuarios($datas['inicio'], $datas['fim']);
                $titulo = 'Relatório de Usuários';
                break;
            case 'votos':
                $dados = $this->relatorioVotos($datas['inicio'], $datas['fim'], $validated['eleicao_id'] ?? null);
                $titulo = 'Relatório de Votos';
                break;
            case 'auditoria':
                $dados = $this->relatorioAuditoria($datas['inicio'], $datas['fim']);
                $titulo = 'Relatório de Auditoria';
                break;
        }

        // Adicionar informações do relatório
        $dados['periodo'] = $validated['periodo'];
        $dados['data_inicio'] = $datas['inicio'];
        $dados['data_fim'] = $datas['fim'];
        $dados['titulo'] = $titulo;
        $dados['gerado_em'] = now();

        // Exportar conforme formato solicitado
        if ($validated['formato'] === 'tela') {
            return view('relatorios.resultado', compact('dados'));
        } else {
            return $this->exportar($dados, $validated['formato'], $titulo);
        }
    }

    private function definirPeriodo($periodo, $dataInicio = null, $dataFim = null)
    {
        $hoje = Carbon::today();

        switch ($periodo) {
            case 'hoje':
                return [
                    'inicio' => $hoje,
                    'fim' => $hoje->copy()->endOfDay(),
                ];
            case 'semana':
                return [
                    'inicio' => $hoje->copy()->subWeek(),
                    'fim' => $hoje->copy()->endOfDay(),
                ];
            case 'mes':
                return [
                    'inicio' => $hoje->copy()->subMonth(),
                    'fim' => $hoje->copy()->endOfDay(),
                ];
            case 'trimestre':
                return [
                    'inicio' => $hoje->copy()->subMonths(3),
                    'fim' => $hoje->copy()->endOfDay(),
                ];
            case 'ano':
                return [
                    'inicio' => $hoje->copy()->subYear(),
                    'fim' => $hoje->copy()->endOfDay(),
                ];
            case 'todos':
                return [
                    'inicio' => Carbon::create(2020, 1, 1), // Data do início do sistema
                    'fim' => $hoje->copy()->endOfDay(),
                ];
            default:
                return [
                    'inicio' => $dataInicio ? Carbon::parse($dataInicio) : $hoje->copy()->subMonth(),
                    'fim' => $dataFim ? Carbon::parse($dataFim)->endOfDay() : $hoje->copy()->endOfDay(),
                ];
        }
    }

    private function relatorioEleicoes($inicio, $fim, $eleicaoId = null)
    {
        $query = Eleicao::whereBetween('created_at', [$inicio, $fim]);
        
        if ($eleicaoId) {
            $query->where('id', $eleicaoId);
        }

        $eleicoes = $query->withCount(['votos', 'candidatos'])
                         ->with('cargo')
                         ->get();

        $estatisticas = [
            'total' => $eleicoes->count(),
            'agendadas' => $eleicoes->where('status', 'agendada')->count(),
            'ativas' => $eleicoes->where('status', 'ativa')->count(),
            'concluidas' => $eleicoes->where('status', 'concluida')->count(),
            'canceladas' => $eleicoes->where('status', 'cancelada')->count(),
            'total_votos' => $eleicoes->sum('votos_registrados'),
            'total_candidatos' => $eleicoes->sum('candidatos_count'),
        ];

        return [
            'tipo' => 'eleicoes',
            'dados' => $eleicoes,
            'estatisticas' => $estatisticas,
        ];
    }

    private function relatorioUsuarios($inicio, $fim)
    {
        $usuarios = User::whereBetween('created_at', [$inicio, $fim])
                       ->get();

        $estatisticas = [
            'total' => $usuarios->count(),
            'ativos' => $usuarios->where('ativo', true)->count(),
            'inativos' => $usuarios->where('ativo', false)->count(),
            'por_categoria' => $usuarios->groupBy('categoria')->map->count(),
            'por_papel' => $usuarios->groupBy('role')->map->count(),
            'novos_7_dias' => User::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
        ];

        return [
            'tipo' => 'usuarios',
            'dados' => $usuarios,
            'estatisticas' => $estatisticas,
        ];
    }

    private function relatorioVotos($inicio, $fim, $eleicaoId = null)
    {
        $query = Voto::whereBetween('created_at', [$inicio, $fim]);
        
        if ($eleicaoId) {
            $query->where('eleicao_id', $eleicaoId);
        }

        $votos = $query->with(['eleicao.cargo', 'candidato.user', 'user'])
                      ->get();

        $estatisticas = [
            'total' => $votos->count(),
            'validos' => $votos->where('valido', true)->count(),
            'nulos' => $votos->where('valido', false)->count(),
            'por_eleicao' => $votos->groupBy('eleicao_id')->map->count(),
            'por_hora' => $votos->groupBy(function($voto) {
                return $voto->created_at->format('H:00');
            })->map->count()->sortKeys(),
        ];

        return [
            'tipo' => 'votos',
            'dados' => $votos,
            'estatisticas' => $estatisticas,
        ];
    }

    private function relatorioAuditoria($inicio, $fim)
    {
        $logs = \App\Models\Log::whereBetween('created_at', [$inicio, $fim])
                               ->with('user')
                               ->get();

        $estatisticas = [
            'total' => $logs->count(),
            'por_acao' => $logs->groupBy('acao')->map->count()->sortDesc(),
            'por_usuario' => $logs->groupBy('user_id')->map->count()->sortDesc()->take(10),
            'por_dia' => $logs->groupBy(function($log) {
                return $log->created_at->format('Y-m-d');
            })->map->count()->sortKeys(),
        ];

        return [
            'tipo' => 'auditoria',
            'dados' => $logs,
            'estatisticas' => $estatisticas,
        ];
    }

    private function exportar($dados, $formato, $titulo)
    {
        switch ($formato) {
            case 'pdf':
                return $this->exportarPDF($dados, $titulo);
            case 'excel':
                return $this->exportarExcel($dados, $titulo);
            case 'csv':
                return $this->exportarCSV($dados, $titulo);
            default:
                abort(404, 'Formato não suportado.');
        }
    }

    private function exportarPDF($dados, $titulo)
    {
        // Implementar exportação PDF
        return response()->json(['message' => 'Exportação PDF em desenvolvimento']);
    }

    private function exportarExcel($dados, $titulo)
    {
        // Implementar exportação Excel
        return response()->json(['message' => 'Exportação Excel em desenvolvimento']);
    }

    private function exportarCSV($dados, $titulo)
    {
        $filename = "relatorio-{$dados['tipo']}-" . now()->format('Y-m-d') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($dados, $titulo) {
            $file = fopen('php://output', 'w');
            
            // Cabeçalho do relatório
            fputcsv($file, [$titulo]);
            fputcsv($file, ["Período: {$dados['periodo']}"]);
            fputcsv($file, ["Data de início: {$dados['data_inicio']->format('d/m/Y')}"]);
            fputcsv($file, ["Data de fim: {$dados['data_fim']->format('d/m/Y')}"]);
            fputcsv($file, ["Gerado em: {$dados['gerado_em']->format('d/m/Y H:i:s')}"]);
            fputcsv($file, []); // Linha vazia
            
            // Estatísticas
            fputcsv($file, ['ESTATÍSTICAS']);
            foreach ($dados['estatisticas'] as $chave => $valor) {
                if (is_array($valor)) {
                    foreach ($valor as $subChave => $subValor) {
                        fputcsv($file, ["{$chave} - {$subChave}", $subValor]);
                    }
                } else {
                    fputcsv($file, [$chave, $valor]);
                }
            }
            
            fputcsv($file, []); // Linha vazia
            
            // Dados detalhados (se existirem)
            if (!empty($dados['dados']) && $dados['dados']->count() > 0) {
                fputcsv($file, ['DADOS DETALHADOS']);
                
                // Cabeçalhos das colunas
                $primeiroItem = $dados['dados']->first();
                if ($primeiroItem) {
                    $cabecalhos = array_keys($primeiroItem->toArray());
                    fputcsv($file, $cabecalhos);
                    
                    // Dados
                    foreach ($dados['dados'] as $item) {
                        fputcsv($file, array_values($item->toArray()));
                    }
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}