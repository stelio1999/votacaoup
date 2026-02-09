@extends('layouts.app')

@section('title', 'Gestão de Eleições')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0 text-dark">
                    <i class="fas fa-vote-yea me-2"></i>Gestão de Eleições
                </h1>
                <p class="text-muted">Gerencie todas as eleições do sistema</p>
            </div>
            <a href="{{ route('eleicoes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nova Eleição
            </a>
        </div>
    </div>
</div>

<!-- Estatísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                            Total
                        </div>
                        <div class="h5 mb-0 fw-bold text-dark">{{ $estatisticas['total'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">
                            Ativas
                        </div>
                        <div class="h5 mb-0 fw-bold text-dark">{{ $estatisticas['ativas'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-play fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">
                            Concluídas
                        </div>
                        <div class="h5 mb-0 fw-bold text-dark">{{ $estatisticas['concluidas'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                            Agendadas
                        </div>
                        <div class="h5 mb-0 fw-bold text-dark">{{ $estatisticas['agendadas'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('eleicoes.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Todos os status</option>
                    <option value="agendada" {{ request('status') == 'agendada' ? 'selected' : '' }}>Agendada</option>
                    <option value="ativa" {{ request('status') == 'ativa' ? 'selected' : '' }}>Ativa</option>
                    <option value="concluida" {{ request('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
                    <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="categoria" class="form-label">Categoria</label>
                <select name="categoria" id="categoria" class="form-select">
                    <option value="">Todas as categorias</option>
                    <option value="estudante" {{ request('categoria') == 'estudante' ? 'selected' : '' }}>Estudante</option>
                    <option value="docente" {{ request('categoria') == 'docente' ? 'selected' : '' }}>Docente</option>
                    <option value="tecnico_administrativo" {{ request('categoria') == 'tecnico_administrativo' ? 'selected' : '' }}>Técnico</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="ano" class="form-label">Ano</label>
                <select name="ano" id="ano" class="form-select">
                    <option value="">Todos os anos</option>
                    @for($i = date('Y'); $i >= 2020; $i--)
                        <option value="{{ $i }}" {{ request('ano') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label d-block">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>Filtrar
                    </button>
                    <a href="{{ route('eleicoes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo me-2"></i>Limpar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">Lista de Eleições</h6>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-primary" id="exportBtn">
                    <i class="fas fa-download me-1"></i>Exportar
                </button>
                <button class="btn btn-sm btn-outline-secondary" id="refreshBtn">
                    <i class="fas fa-sync-alt me-1"></i>Atualizar
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Cargo</th>
                        <th>Categoria</th>
                        <th>Período</th>
                        <th>Status</th>
                        <th>Participação</th>
                        <th>Candidatos</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eleicoes as $eleicao)
                    <tr>
                        <td>
                            <strong>{{ $eleicao->titulo }}</strong>
                            @if($eleicao->descricao)
                                <div class="small text-muted">
                                    {{ Str::limit($eleicao->descricao, 50) }}
                                </div>
                            @endif
                        </td>
                        <td>{{ $eleicao->cargo->nome }}</td>
                        <td>
                            @switch($eleicao->cargo->categoria)
                                @case('estudante')
                                    <span class="badge bg-success">Estudante</span>
                                    @break
                                @case('docente')
                                    <span class="badge bg-primary">Docente</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">Técnico</span>
                            @endswitch
                        </td>
                        <td>
                            <small>{{ $eleicao->data_inicio->format('d/m/Y') }}</small>
                            <div class="small text-muted">
                                {{ $eleicao->data_inicio->format('H:i') }} - {{ $eleicao->data_fim->format('H:i') }}
                            </div>
                        </td>
                        <td>
                            @switch($eleicao->status)
                                @case('agendada')
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-clock me-1"></i>Agendada
                                    </span>
                                    <div class="small text-muted">
                                        {{ $eleicao->data_inicio->diffForHumans() }}
                                    </div>
                                    @break
                                @case('ativa')
                                    <span class="badge bg-success">
                                        <i class="fas fa-play me-1"></i>Ativa
                                    </span>
                                    <div class="small text-muted">
                                        Termina {{ $eleicao->data_fim->diffForHumans() }}
                                    </div>
                                    @break
                                @case('concluida')
                                    <span class="badge bg-info">
                                        <i class="fas fa-check me-1"></i>Concluída
                                    </span>
                                    @break
                                @case('cancelada')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times me-1"></i>Cancelada
                                    </span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                    <div class="progress-bar bg-success" 
                                         style="width: {{ $eleicao->percentual_conclusao }}%">
                                    </div>
                                </div>
                                <span class="small">{{ $eleicao->percentual_conclusao }}%</span>
                            </div>
                            <div class="small text-muted">
                                {{ $eleicao->votos_registrados }}/{{ $eleicao->total_eleitores }}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $eleicao->candidatos_count ?? 0 }}</span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('eleicoes.show', $eleicao) }}" 
                                   class="btn btn-sm btn-info" 
                                   data-bs-toggle="tooltip" 
                                   title="Ver detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('comissao'))
                                <a href="{{ route('eleicoes.edit', $eleicao) }}" 
                                   class="btn btn-sm btn-warning" 
                                   data-bs-toggle="tooltip" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($eleicao->status == 'agendada')
                                <form action="{{ route('eleicoes.iniciar', $eleicao) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Tem certeza que deseja iniciar esta eleição?')">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-sm btn-success"
                                            data-bs-toggle="tooltip" 
                                            title="Iniciar eleição">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </form>
                                @elseif($eleicao->status == 'ativa')
                                <form action="{{ route('eleicoes.encerrar', $eleicao) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Tem certeza que deseja encerrar esta eleição?')">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger"
                                            data-bs-toggle="tooltip" 
                                            title="Encerrar eleição">
                                        <i class="fas fa-stop"></i>
                                    </button>
                                </form>
                                @endif
                                
                                <form action="{{ route('eleicoes.destroy', $eleicao) }}" 
                                      method="POST" 
                                      class="d-inline confirm-action"
                                      data-confirm="Tem certeza que deseja excluir esta eleição? Esta ação não pode ser desfeita.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger"
                                            data-bs-toggle="tooltip" 
                                            title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Mostrando {{ $eleicoes->firstItem() }} a {{ $eleicoes->lastItem() }} de {{ $eleicoes->total() }} eleições
            </div>
            <div>
                {{ $eleicoes->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Ações em Massa -->
@if(auth()->user()->hasRole('admin'))
<div class="card shadow mt-4">
    <div class="card-header bg-dark text-white">
        <h6 class="m-0 fw-bold">
            <i class="fas fa-cogs me-2"></i>Ações em Massa
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <button class="btn btn-outline-primary w-100" 
                        onclick="return confirm('Esta ação irá iniciar todas as eleições agendadas cuja data de início já passou. Continuar?')">
                    <i class="fas fa-play me-2"></i>Iniciar Eleições Atrasadas
                </button>
            </div>
            <div class="col-md-4 mb-3">
                <button class="btn btn-outline-success w-100" 
                        onclick="return confirm('Esta ação irá encerrar todas as eleições ativas cuja data de término já passou. Continuar?')">
                    <i class="fas fa-stop me-2"></i>Encerrar Eleições Vencidas
                </button>
            </div>
            <div class="col-md-4 mb-3">
                <button class="btn btn-outline-info w-100"
                        onclick="alert('Funcionalidade em desenvolvimento')">
                    <i class="fas fa-chart-line me-2"></i>Gerar Relatório Geral
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Calendário de Eleições (Opcional) -->
<div class="card shadow mt-4">
    <div class="card-header">
        <h6 class="m-0 fw-bold text-primary">
            <i class="fas fa-calendar-alt me-2"></i>Próximas Eleições
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            @php
                $proximasEleicoes = \App\Models\Eleicao::where('status', 'agendada')
                    ->where('data_inicio', '>', now())
                    ->orderBy('data_inicio')
                    ->take(6)
                    ->get();
            @endphp
            
            @if($proximasEleicoes->count() > 0)
                @foreach($proximasEleicoes as $eleicao)
                <div class="col-md-4 mb-3">
                    <div class="card border-left-primary h-100">
                        <div class="card-body">
                            <h6 class="card-title">{{ $eleicao->titulo }}</h6>
                            <p class="card-text small text-muted">
                                {{ $eleicao->cargo->nome }}
                            </p>
                            <div class="small">
                                <i class="fas fa-calendar-day me-1"></i>
                                {{ $eleicao->data_inicio->format('d/m/Y H:i') }}
                            </div>
                            <div class="small text-muted mt-1">
                                <i class="fas fa-hourglass-start me-1"></i>
                                Inicia em: {{ $eleicao->data_inicio->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Não há eleições agendadas</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    
    
    // Tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Botão de atualizar
    $('#refreshBtn').click(function() {
        location.reload();
    });
    
    // Botão de exportar
    $('#exportBtn').click(function() {
        // Implementar exportação
        alert('Funcionalidade de exportação em desenvolvimento.');
    });
    
    // Filtro automático por status
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    if (status) {
        $('#status').val(status);
    }
    
    // Mostrar/ocultar ações conforme status
    $('tr').each(function() {
        const statusBadge = $(this).find('.badge');
        if (statusBadge.text().includes('Concluída') || statusBadge.text().includes('Cancelada')) {
            $(this).find('form[action*="iniciar"], form[action*="encerrar"]').remove();
        }
    });
});
</script>
@endsection