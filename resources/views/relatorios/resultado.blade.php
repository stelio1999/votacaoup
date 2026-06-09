@extends('layouts.app')

@section('title', 'Resultado do Relatório')

@section('styles')
<style>
    
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-dark">
                <i class="fas fa-chart-bar me-2"></i>Resultado do Relatório
            </h1>
            <div class="export-buttons">
                <div class="btn-group">
                    <a href="{{ route('relatorios.exportar', ['tipo' => $dados['tipo'], 'formato' => 'pdf']) }}" 
                       class="btn btn-danger">
                        <i class="fas fa-file-pdf me-2"></i>PDF
                    </a>
                    <a href="{{ route('relatorios.exportar', ['tipo' => $dados['tipo'], 'formato' => 'excel']) }}" 
                       class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>Excel
                    </a>
                    <a href="{{ route('relatorios.exportar', ['tipo' => $dados['tipo'], 'formato' => 'csv']) }}" 
                       class="btn btn-info">
                        <i class="fas fa-file-csv me-2"></i>CSV
                    </a>
                </div>
            </div>
        </div>
        <p class="text-muted">Análise detalhada dos dados do sistema</p>
    </div>
</div>

<!-- Cabeçalho do Relatório -->
<div class="report-header">
    <div class="row">
        <div class="col-md-8">
            <h2 class="fw-bold">{{ $dados['titulo'] }}</h2>
            <p class="mb-0">
                <i class="fas fa-calendar-alt me-2"></i>
                Período: {{ ucfirst($dados['periodo']) }}
            </p>
            <p class="mb-0">
                <i class="fas fa-clock me-2"></i>
                {{ $dados['data_inicio']->format('d/m/Y') }} até {{ $dados['data_fim']->format('d/m/Y') }}
            </p>
        </div>
        <div class="col-md-4 text-end">
            <div class="period-badge">
                <i class="fas fa-calendar-check me-2"></i>
                Gerado em: {{ $dados['gerado_em']->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas Principais -->
<div class="row mb-4">
    @php
        $estatisticas = $dados['estatisticas'] ?? [];
    @endphp
    
    @if(!empty($estatisticas))
        @foreach($estatisticas as $chave => $valor)
            @if(!is_array($valor) && !is_object($valor))
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-number mb-2">
                        {{ is_numeric($valor) ? number_format($valor, 0, ',', '.') : $valor }}
                    </div>
                    <div class="stat-label">
                        {{ ucfirst(str_replace('_', ' ', $chave)) }}
                    </div>
                    @if(is_numeric($valor) && $valor > 0)
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-success" 
                             style="width: {{ min($valor, 100) }}%">
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        @endforeach
    @endif
</div>

<!-- Dados Detalhados -->
<div class="card shadow mb-4">
    <div class="card-header">
        <h6 class="m-0 fw-bold text-primary">
            <i class="fas fa-table me-2"></i>Dados Detalhados
            <span class="badge bg-primary ms-2">{{ count($dados['dados'] ?? []) }} registros</span>
        </h6>
    </div>
    <div class="card-body">
        @if(!empty($dados['dados']) && count($dados['dados']) > 0)
            <div class="table-responsive">
                <table class="table table-hover data-table">
                    <thead>
                        <tr>
                            @php
                                $primeiroItem = $dados['dados']->first();
                                $cabecalhos = array_keys($primeiroItem->toArray());
                            @endphp
                            
                            @foreach($cabecalhos as $cabecalho)
                                <th>{{ ucfirst(str_replace('_', ' ', $cabecalho)) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dados['dados'] as $item)
                        <tr>
                            @foreach($cabecalhos as $cabecalho)
                                <td>
                                    @php
                                        $valor = $item->{$cabecalho};
                                    @endphp
                                    
                                    @if($cabecalho === 'created_at' || $cabecalho === 'updated_at' || $cabecalho === 'data_inicio' || $cabecalho === 'data_fim')
                                        {{ \Carbon\Carbon::parse($valor)->format('d/m/Y H:i') }}
                                    @elseif(is_bool($valor))
                                        @if($valor)
                                            <span class="badge bg-success">Sim</span>
                                        @else
                                            <span class="badge bg-danger">Não</span>
                                        @endif
                                    @elseif(is_array($valor))
                                        <span class="badge bg-info">{{ count($valor) }} itens</span>
                                    @elseif($cabecalho === 'status')
                                        @switch($valor)
                                            @case('agendada')
                                                <span class="badge bg-warning">Agendada</span>
                                                @break
                                            @case('ativa')
                                                <span class="badge bg-success">Ativa</span>
                                                @break
                                            @case('concluida')
                                                <span class="badge bg-info">Concluída</span>
                                                @break
                                            @case('cancelada')
                                                <span class="badge bg-danger">Cancelada</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $valor }}</span>
                                        @endswitch
                                    @elseif($cabecalho === 'role')
                                        @switch($valor)
                                            @case('admin')
                                                <span class="badge bg-danger">Administrador</span>
                                                @break
                                            @case('comissao')
                                                <span class="badge bg-warning">Comissão</span>
                                                @break
                                            @default
                                                <span class="badge bg-info">Eleitor</span>
                                        @endswitch
                                    @elseif($cabecalho === 'categoria')
                                        @switch($valor)
                                            @case('estudante')
                                                <span class="badge bg-success">Estudante</span>
                                                @break
                                            @case('docente')
                                                <span class="badge bg-primary">Docente</span>
                                                @break
                                            @case('tecnico_administrativo')
                                                <span class="badge bg-secondary">Técnico</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $valor }}</span>
                                        @endswitch
                                    @else
                                        {{ $valor }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Mostrando {{ count($dados['dados']) }} registros
                </div>
                <nav>
                    {{ $dados['dados']->links() }}
                </nav>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-database fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Nenhum dado encontrado</h4>
                <p class="text-muted">
                    Não foram encontrados registros para os critérios selecionados.
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Gráficos e Visualizações -->
@if(!empty($estatisticas))
<div class="row mb-4">
    <!-- Gráfico de Distribuição -->
    @foreach($estatisticas as $chave => $valor)
        @if(is_array($valor) && count($valor) > 0)
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>
                        {{ ucfirst(str_replace('_', ' ', $chave)) }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="chart{{ $chave }}"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach
</div>
@endif

<!-- Resumo Executivo -->
<div class="card shadow">
    <div class="card-header">
        <h6 class="m-0 fw-bold text-primary">
            <i class="fas fa-file-alt me-2"></i>Resumo Executivo
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold mb-3">Principais Insights</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Período analisado:</strong> {{ $dados['periodo'] }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Total de registros:</strong> {{ count($dados['dados'] ?? []) }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Tipo de relatório:</strong> {{ ucfirst($dados['tipo']) }}
                    </li>
                    @if(!empty($estatisticas['total']))
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Total geral:</strong> {{ $estatisticas['total'] }}
                    </li>
                    @endif
                </ul>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold mb-3">Recomendações</h6>
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="fas fa-lightbulb me-2"></i>Sugestões baseadas nos dados:
                    </h6>
                    <ul class="mb-0 small">
                        <li>Revise os dados regularmente para identificar tendências</li>
                        <li>Compare com períodos anteriores para análise de progresso</li>
                        <li>Exporte os dados para análise mais aprofundada</li>
                        <li>Compartilhe insights relevantes com a equipe</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ações -->
<div class="card shadow mt-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <a href="{{ route('relatorios.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left me-2"></i>Voltar para Relatórios
                </a>
            </div>
            <div class="col-md-4">
                <button onclick="window.print()" class="btn btn-outline-primary w-100">
                    <i class="fas fa-print me-2"></i>Imprimir Relatório
                </button>
            </div>
            <div class="col-md-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-success w-100">
                    <i class="fas fa-home me-2"></i>Voltar ao Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function() {
    // Inicializar DataTables
    $('.data-table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-PT.json"
        },
        "pageLength": 10,
        "order": [[0, 'desc']],
        "responsive": true,
    });
    
    // Criar gráficos para estatísticas em array
    @if(!empty($estatisticas))
        @foreach($estatisticas as $chave => $valor)
            @if(is_array($valor) && count($valor) > 0)
                // Configurar cores para o gráfico
                const colors{{ $chave }} = [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e',
                    '#e74a3b', '#858796', '#5a5c69', '#3a3b45',
                    '#2e59d9', '#17a673', '#2c9faf', '#e0b03a'
                ];
                
                // Preparar dados para o gráfico
                const labels{{ $chave }} = [];
                const data{{ $chave }} = [];
                const backgroundColors{{ $chave }} = [];
                
                @if(is_array($valor))
                    @php $index = 0; @endphp
                    @foreach($valor as $subChave => $subValor)
                        labels{{ $chave }}.push('{{ $subChave }}');
                        data{{ $chave }}.push({{ is_numeric($subValor) ? $subValor : 0 }});
                        backgroundColors{{ $chave }}.push(colors{{ $chave }}[{{ $index }} % colors{{ $chave }}.length]);
                        @php $index++; @endphp
                    @endforeach
                @endif
                
                // Criar gráfico
                const ctx{{ $chave }} = document.getElementById('chart{{ $chave }}').getContext('2d');
                new Chart(ctx{{ $chave }}, {
                    type: 'bar',
                    data: {
                        labels: labels{{ $chave }},
                        datasets: [{
                            label: '{{ ucfirst(str_replace('_', ' ', $chave)) }}',
                            data: data{{ $chave }},
                            backgroundColor: backgroundColors{{ $chave }},
                            borderColor: backgroundColors{{ $chave }},
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.parsed.y;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            @endif
        @endforeach
    @endif
    
    // Configurar impressão
    $('.btn-print').click(function() {
        window.print();
    });
    
    // Configurar exportação
    $('.export-btn').click(function() {
        const formato = $(this).data('formato');
        const url = "{{ route('relatorios.exportar', ['tipo' => $dados['tipo'], 'formato' => ':formato']) }}".replace(':formato', formato);
        window.location.href = url;
    });
});
</script>

<!-- Estilos para impressão -->
<style>
@media print {
    .export-buttons,
    .btn,
    .sidebar-wrapper,
    .navbar,
    .breadcrumb,
    .card-footer {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .card-header {
        background: white !important;
        color: black !important;
        border-bottom: 2px solid #000 !important;
    }
    
    .report-header {
        background: white !important;
        color: black !important;
        border: 2px solid #000 !important;
    }
    
    .stat-card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    
    .container-fluid {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }
    
    body {
        font-size: 12pt !important;
        color: black !important;
        background: white !important;
    }
    
    h1, h2, h3, h4, h5, h6 {
        color: black !important;
    }
}
</style>
@endsection