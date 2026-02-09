<?php

namespace App\Http\Controllers;

use App\Models\Eleicao;
use App\Models\Resultado;
use Illuminate\Http\Request;

class ResultadoController extends Controller
{
    public function index()
    {
        $eleicoes = Eleicao::where('status', 'concluida')
                          ->with(['cargo', 'resultados.candidato.user'])
                          ->latest()
                          ->paginate(10);
        
        return view('resultados.index', compact('eleicoes'));
    }

    public function show(Eleicao $eleicao)
    {
        // Verificar se a eleição está concluída
        if ($eleicao->status !== 'concluida' && !auth()->user()->hasRole('admin')) {
            abort(403, 'Resultados disponíveis apenas para eleições concluídas.');
        }

        $resultados = $eleicao->resultados()
                             ->with('candidato.user')
                             ->orderByDesc('total_votos')
                             ->get();
        
        $estatisticas = [
            'total_votos' => $eleicao->votos_registrados,
            'total_eleitores' => $eleicao->total_eleitores,
            'abstencao' => $eleicao->total_eleitores - $eleicao->votos_registrados,
            'percentual_participacao' => $eleicao->total_eleitores > 0 
                ? round(($eleicao->votos_registrados / $eleicao->total_eleitores) * 100, 2)
                : 0,
        ];

        // Dados para gráficos
        $dadosGrafico = $this->prepararDadosGrafico($resultados);

        return view('resultados.show', compact('eleicao', 'resultados', 'estatisticas', 'dadosGrafico'));
    }

    public function publicos()
    {
        $eleicoes = Eleicao::where('status', 'concluida')
                          ->with(['cargo', 'resultados' => function($query) {
                              $query->orderByDesc('total_votos')->limit(3);
                          }])
                          ->latest()
                          ->paginate(12);
        
        return view('resultados.publicos', compact('eleicoes'));
    }

    public function exportar(Eleicao $eleicao, $formato = 'pdf')
    {
        $resultados = $eleicao->resultados()
                             ->with('candidato.user')
                             ->orderByDesc('total_votos')
                             ->get();
        
        $estatisticas = [
            'total_votos' => $eleicao->votos_registrados,
            'total_eleitores' => $eleicao->total_eleitores,
            'abstencao' => $eleicao->total_eleitores - $eleicao->votos_registrados,
            'percentual_participacao' => $eleicao->total_eleitores > 0 
                ? round(($eleicao->votos_registrados / $eleicao->total_eleitores) * 100, 2)
                : 0,
        ];

        switch ($formato) {
            case 'pdf':
                return $this->exportarPDF($eleicao, $resultados, $estatisticas);
            case 'excel':
                return $this->exportarExcel($eleicao, $resultados, $estatisticas);
            case 'csv':
                return $this->exportarCSV($eleicao, $resultados, $estatisticas);
            default:
                abort(404, 'Formato não suportado.');
        }
    }

    private function prepararDadosGrafico($resultados)
    {
        $labels = [];
        $data = [];
        $colors = [];

        $colorPalette = [
            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', 
            '#e74a3b', '#858796', '#5a5c69', '#3a3b45'
        ];

        foreach ($resultados as $index => $resultado) {
            $labels[] = $resultado->candidato->user->name;
            $data[] = $resultado->total_votos;
            $colors[] = $colorPalette[$index % count($colorPalette)];
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors,
        ];
    }

    private function exportarPDF($eleicao, $resultados, $estatisticas)
    {
        // Implementar exportação para PDF usando DomPDF
        // return PDF::loadView('resultados.pdf', compact('eleicao', 'resultados', 'estatisticas'))
        //           ->download("resultados-{$eleicao->id}.pdf");
        
        return response()->json(['message' => 'Exportação PDF em desenvolvimento']);
    }

    private function exportarExcel($eleicao, $resultados, $estatisticas)
    {
        // Implementar exportação para Excel usando Maatwebsite/Laravel-Excel
        return response()->json(['message' => 'Exportação Excel em desenvolvimento']);
    }

    private function exportarCSV($eleicao, $resultados, $estatisticas)
    {
        $filename = "resultados-{$eleicao->id}.csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($eleicao, $resultados, $estatisticas) {
            $file = fopen('php://output', 'w');
            
            // Cabeçalho
            fputcsv($file, ["Resultados da Eleição: {$eleicao->titulo}"]);
            fputcsv($file, ["Data: " . now()->format('d/m/Y H:i:s')]);
            fputcsv($file, []); // Linha vazia
            
            // Estatísticas
            fputcsv($file, ['ESTATÍSTICAS']);
            fputcsv($file, ['Total de Eleitores', $estatisticas['total_eleitores']]);
            fputcsv($file, ['Total de Votos', $estatisticas['total_votos']]);
            fputcsv($file, ['Abstenção', $estatisticas['abstencao']]);
            fputcsv($file, ['Participação', $estatisticas['percentual_participacao'] . '%']);
            fputcsv($file, []); // Linha vazia
            
            // Resultados
            fputcsv($file, ['RESULTADOS']);
            fputcsv($file, ['Posição', 'Candidato', 'Número', 'Votos', 'Percentual', 'Status']);
            
            $posicao = 1;
            foreach ($resultados as $resultado) {
                fputcsv($file, [
                    $posicao++,
                    $resultado->candidato->user->name,
                    $resultado->candidato->numero_candidato,
                    $resultado->total_votos,
                    $resultado->percentual . '%',
                    $resultado->eleito ? 'ELEITO' : ''
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}