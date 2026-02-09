@extends('layouts.app')

@section('title', 'Resultados - ' . $eleicao->titulo)

@section('styles')
<style>
    .results-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .winner-card {
        background: linear-gradient(135deg, var(--verde-suave) 0%, #2dce89 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(56, 161, 105, 0.3);
    }
    
    .winner-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 5px solid white;
        margin: 0 auto 1.5rem;
        overflow: hidden;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .winner-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .winner-avatar .initials {
        font-size: 2.5rem;
        font-weight: bold;
        color: var(--verde-suave);
    }
    
    .results-table .position-cell {
        width: 60px;
        text-align: center;
        font-weight: bold;
        font-size: 1.2rem;
    }
    
    .results-table .position-1 {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        color: #8b6914;
    }
    
    .results-table .position-2 {
        background: linear-gradient(135deg, #c0c0c0 0%, #e0e0e0 100%);
        color: #666;
    }
    
    .results-table .position-3 {
        background: linear-gradient(135deg, #cd7f32 0%, #e6a756 100%);
        color: #8b4513;
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        margin: 2rem 0;
    }
    
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        height: 100%;
    }
    
    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: var(--cinza-texto);
        font-size: 0.9rem;
    }
    
    .export-options {
        background: var(--cinza-claro);
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 2rem;
    }
    
    .vote-distribution {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .candidate-bar {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding: 0.5rem;
        border-radius: 8px;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }
    
    .candidate-bar:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }
    
    .bar-fill {
        height: 10px;
        border-radius: 5px;
        background: var(--azul-claro);
        transition: width 1s ease;
    }
</style>
@endsection

@section('content')
<div class="results-container">
    <!-- Cabeçalho -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('resultados.index') }}">Resultados</a></li>
                    <li class="breadcrumb-item active">{{ $eleicao->titulo }}</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-dark">Resultados da Eleição</h1>
                    <h2 class="h5 text-muted">{{ $eleicao->titulo }}</h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-briefcase me-2"></i>{{ $eleicao->cargo->nome }}
                        • 
                        <i class="fas fa-calendar me-2"></i>{{ $eleicao->data_inicio->format('d/m/Y') }}
                    </p>
                </div>
                <div class="btn-group">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-download me-2"></i>Exportar
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('resultados.exportar', [$eleicao, 'pdf']) }}">
                                    <i class="fas fa-file-pdf me-2"></i>PDF
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('resultados.exportar', [$eleicao, 'excel']) }}">
                                    <i class="fas fa-file-excel me-2"></i>Excel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('resultados.exportar', [$eleicao, 'csv']) }}">
                                    <i class="fas fa-file-csv me-2"></i>CSV
                                </a>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('resultados.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Vencedor -->
    @php
        $vencedor = $resultados->where('eleito', true)->first();
    @endphp
    
    @if($vencedor)
    <div class="winner-card">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <div class="winner-avatar">
                    @if($vencedor->candidato->foto)
                        <img src="{{ Storage::url($vencedor->candidato->foto) }}" 
                             alt="{{ $vencedor->candidato->user->name }}">
                    @else
                        <div class="initials">{{ $vencedor->candidato->iniciais }}</div>
                    @endif
                </div>
            </div>
            <div class="col-lg-8">
                <div class="text-center text-lg-start">
                    <h3 class="mb-3">
                        <i class="fas fa-trophy me-2"></i>VENCEDOR
                    </h3>
                    <h2 class="display-5 fw-bold mb-3">{{ $vencedor->candidato->user->name }}</h2>
                    <div class="mb-4">
                        <span class="badge bg-dark fs-6 px-3 py-2 me-2">
                            #{{ $vencedor->candidato->numero_candidato }}
                        </span>
                        <span class="badge bg-light text-dark fs-6 px-3 py-2">
                            {{ $vencedor->total_votos }} votos ({{ $vencedor->percentual_formatado }})
                        </span>
                    </div>
                    <p class="lead mb-0">
                        <i class="fas fa-quote-left me-2"></i>
                        Parabéns ao candidato eleito!
                        <i class="fas fa-quote-right ms-2"></i>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon text-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number text-primary">{{ $estatisticas['total_eleitores'] }}</div>
                <div class="stat-label">Total de Eleitores</div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon text-success">
                    <i class="fas fa-vote-yea"></i>
                </div>
                <div class="stat-number text-success">{{ $estatisticas['total_votos'] }}</div>
                <div class="stat-label">Votos Registrados</div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon text-info">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-number text-info">{{ $estatisticas['percentual_participacao'] }}%</div>
                <div class="stat-label">Taxa de Participação</div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon text-warning">
                    <i class="fas fa-user-slash"></i>
                </div>
                <div class="stat-number text-warning">{{ $estatisticas['abstencao'] }}</div>
                <div class="stat-label">Abstenções</div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Coluna de Resultados -->
        <div class="col-lg-8">
            <!-- Tabela de Resultados -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-list-ol me-2"></i>Resultados Detalhados
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover results-table">
                            <thead>
                                <tr>
                                    <th width="60">Posição</th>
                                    <th>Candidato</th>
                                    <th>Número</th>
                                    <th class="text-center">Votos</th>
                                    <th class="text-center">Percentual</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resultados as $resultado)
                                <tr>
                                    <td class="position-cell">
                                        @if($resultado->posicao == 1)
                                            <div class="position-1 rounded-circle d-inline-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                1º
                                            </div>
                                        @elseif($resultado->posicao == 2)
                                            <div class="position-2 rounded-circle d-inline-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                2º
                                            </div>
                                        @elseif($resultado->posicao == 3)
                                            <div class="position-3 rounded-circle d-inline-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                3º
                                            </div>
                                        @else
                                            <span class="text-muted">{{ $resultado->posicao_formatada }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($resultado->candidato->foto)
                                                <img src="{{ Storage::url($resultado->candidato->foto) }}" 
                                                     alt="{{ $resultado->candidato->user->name }}" 
                                                     class="rounded-circle me-3"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="avatar-circle-sm me-3">
                                                    <span class="initials-sm">{{ $resultado->candidato->iniciais }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $resultado->candidato->user->name }}</strong>
                                                @if($resultado->diferenca_para_primeiro > 0 && $resultado->posicao > 1)
                                                    <div class="small text-muted">
                                                        -{{ $resultado->diferenca_para_primeiro }} votos do líder
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-dark">#{{ $resultado->candidato->numero_candidato }}</span>
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ $resultado->total_votos }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $resultado->percentual_formatado }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($resultado->eleito)
                                            <span class="badge bg-success">ELEITO</span>
                                        @else
                                            <span class="badge bg-secondary">NÃO ELEITO</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Distribuição de Votos -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Distribuição de Votos
                    </h6>
                </div>
                <div class="card-body">
                    <div class="vote-distribution">
                        @foreach($resultados as $resultado)
                        <div class="candidate-bar">
                            <div class="flex-grow-1 me-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small">{{ $resultado->candidato->user->name }}</span>
                                    <span class="small fw-bold">{{ $resultado->total_votos }} votos</span>
                                </div>
                                <div class="bar-fill" 
                                     style="width: {{ $resultado->percentual }}%"
                                     data-percent="{{ $resultado->percentual }}">
                                </div>
                            </div>
                            <div class="text-end" style="min-width: 80px;">
                                <span class="badge bg-info">{{ $resultado->percentual_formatado }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Coluna de Informações -->
        <div class="col-lg-4">
            <!-- Informações da Eleição -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Informações
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th class="text-muted" width="40%">Eleição:</th>
                            <td>{{ $eleicao->titulo }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Cargo:</th>
                            <td>{{ $eleicao->cargo->nome }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Período:</th>
                            <td>
                                {{ $eleicao->data_inicio->format('d/m/Y H:i') }}<br>
                                {{ $eleicao->data_fim->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Duração:</th>
                            <td>{{ $eleicao->data_inicio->diffInHours($eleicao->data_fim) }} horas</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Candidatos:</th>
                            <td>{{ $resultados->count() }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Resultados gerados em:</th>
                            <td>{{ now()->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Gráfico (Placeholder) -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Gráfico de Resultados
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <div class="chart-placeholder mb-3">
                            <i class="fas fa-chart-pie fa-5x text-muted"></i>
                        </div>
                        <p class="text-muted small mb-0">
                            Visualização gráfica dos resultados<br>
                            <small>(Implementação futura)</small>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Exportar Resultados -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-share-alt me-2"></i>Compartilhar
                    </h6>
                </div>
                <div class="card-body">
                    <div class="export-options">
                        <h6 class="fw-bold mb-3">Exportar Resultados</h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('resultados.exportar', [$eleicao, 'pdf']) }}" 
                               class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf me-2"></i>PDF
                            </a>
                            <a href="{{ route('resultados.exportar', [$eleicao, 'excel']) }}" 
                               class="btn btn-outline-success">
                                <i class="fas fa-file-excel me-2"></i>Excel
                            </a>
                            <a href="{{ route('resultados.exportar', [$eleicao, 'csv']) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-file-csv me-2"></i>CSV
                            </a>
                        </div>
                        
                        <hr class="my-3">
                        
                        <h6 class="fw-bold mb-3">Compartilhar</h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary flex-fill">
                                <i class="fab fa-facebook me-2"></i>Facebook
                            </button>
                            <button class="btn btn-outline-info flex-fill">
                                <i class="fab fa-twitter me-2"></i>Twitter
                            </button>
                            <button class="btn btn-outline-success flex-fill">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Observações -->
    @if($eleicao->observacoes)
    <div class="card shadow mt-4">
        <div class="card-header">
            <h6 class="m-0 fw-bold text-primary">
                <i class="fas fa-sticky-note me-2"></i>Observações
            </h6>
        </div>
        <div class="card-body">
            {{ $eleicao->observacoes }}
        </div>
    </div>
    @endif
</div>

<style>
.avatar-circle-sm {
    width: 40px;
    height: 40px;
    background: var(--azul-claro);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
}

.initials-sm {
    font-size: 0.9rem;
}

.chart-placeholder {
    opacity: 0.5;
}

@media print {
    .no-print, .btn, .dropdown, .export-options, .card-header .btn {
        display: none !important;
    }
    
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
    
    .winner-card {
        background: #f8f9fa !important;
        color: #333 !important;
        border: 2px solid #28a745 !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Animar barras de distribuição
    $('.bar-fill').each(function() {
        const percent = $(this).data('percent');
        $(this).css('width', '0%');
        
        setTimeout(() => {
            $(this).animate({ width: percent + '%' }, 1500);
        }, 300);
    });
    
    // Copiar link para área de transferência
    $('[data-copy-link]').click(function() {
        const link = window.location.href;
        navigator.clipboard.writeText(link).then(() => {
            alert('Link copiado para área de transferência!');
        });
    });
});
</script>
@endsection