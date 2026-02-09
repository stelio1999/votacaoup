@extends('layouts.app')

@section('title', 'Histórico de Atividades')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Meu Perfil</a></li>
                <li class="breadcrumb-item active">Atividades</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-dark">
                <i class="fas fa-history me-2"></i>Histórico de Atividades
            </h1>
            <a href="{{ route('profile.show') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Voltar ao Perfil
            </a>
        </div>
        <p class="text-muted">Registro completo de todas as suas ações no sistema</p>
    </div>
</div>

<div class="card shadow">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">Todas as Atividades</h6>
            
            <!-- Filtros -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-2"></i>Filtrar
                </button>
                <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 300px;">
                    <form method="GET" action="{{ route('profile.activity') }}">
                        <div class="mb-3">
                            <label for="acao" class="form-label">Tipo de Ação</label>
                            <select class="form-select" id="acao" name="acao">
                                <option value="">Todas as ações</option>
                                <option value="login" {{ request('acao') == 'login' ? 'selected' : '' }}>Login</option>
                                <option value="logout" {{ request('acao') == 'logout' ? 'selected' : '' }}>Logout</option>
                                <option value="alterar_senha" {{ request('acao') == 'alterar_senha' ? 'selected' : '' }}>Alterar Senha</option>
                                <option value="registrar_voto" {{ request('acao') == 'registrar_voto' ? 'selected' : '' }}>Registrar Voto</option>
                                <option value="atualizar_perfil" {{ request('acao') == 'atualizar_perfil' ? 'selected' : '' }}>Atualizar Perfil</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="data_inicio" class="form-label">Data de Início</label>
                            <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="data_fim" class="form-label">Data de Fim</label>
                            <input type="date" class="form-control" id="data_fim" name="data_fim" value="{{ request('data_fim') }}">
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                <i class="fas fa-check me-1"></i>Aplicar
                            </button>
                            <a href="{{ route('profile.activity') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($logs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Ação</th>
                            <th>Descrição</th>
                            <th>IP</th>
                            <th>Data/Hora</th>
                            <th class="text-end">Detalhes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>
                                @switch($log->acao)
                                    @case('login')
                                        <span class="badge bg-success">
                                            <i class="fas fa-sign-in-alt me-1"></i>Login
                                        </span>
                                        @break
                                    @case('logout')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                                        </span>
                                        @break
                                    @case('alterar_senha')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-key me-1"></i>Alterar Senha
                                        </span>
                                        @break
                                    @case('registrar_voto')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-vote-yea me-1"></i>Votar
                                        </span>
                                        @break
                                    @case('atualizar_perfil')
                                        <span class="badge bg-info">
                                            <i class="fas fa-user-edit me-1"></i>Editar Perfil
                                        </span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-info-circle me-1"></i>Outro
                                        </span>
                                @endswitch
                            </td>
                            <td>{{ $log->descricao }}</td>
                            <td>
                                <code>{{ $log->ip_address }}</code>
                                @if($log->user_agent)
                                    <div class="small text-muted mt-1">
                                        {{ Str::limit($log->user_agent, 30) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="text-nowrap">
                                    {{ $log->created_at->format('d/m/Y') }}
                                </div>
                                <div class="small text-muted">
                                    {{ $log->created_at->format('H:i:s') }}
                                </div>
                            </td>
                            <td class="text-end">
                                @if($log->dados_alterados)
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailsModal{{ $log->id }}">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    
                                    <!-- Modal de Detalhes -->
                                    <div class="modal fade" id="detailsModal{{ $log->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detalhes da Ação</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <h6>Informações da Ação</h6>
                                                        <table class="table table-sm">
                                                            <tbody>
                                                                <tr>
                                                                    <th style="width: 30%;">Ação:</th>
                                                                    <td>{{ $log->descricao }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Data/Hora:</th>
                                                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Endereço IP:</th>
                                                                    <td>{{ $log->ip_address }}</td>
                                                                </tr>
                                                                @if($log->user_agent)
                                                                <tr>
                                                                    <th>Navegador:</th>
                                                                    <td>{{ $log->user_agent }}</td>
                                                                </tr>
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    
                                                    @if($log->dados_alterados)
                                                    <div>
                                                        <h6>Dados Alterados</h6>
                                                        <div class="bg-light p-3 rounded">
                                                            <pre class="mb-0" style="max-height: 300px; overflow: auto;">{{ json_encode($log->dados_alterados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Mostrando {{ $logs->firstItem() }} a {{ $logs->lastItem() }} de {{ $logs->total() }} atividades
                </div>
                <div>
                    {{ $logs->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhuma atividade encontrada</h5>
                <p class="text-muted mb-4">
                    Não há registros de atividades para o período selecionado.
                </p>
                <a href="{{ route('profile.activity') }}" class="btn btn-outline-primary">
                    <i class="fas fa-redo me-2"></i>Limpar Filtros
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Estatísticas -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card border-left-primary shadow">
            <div class="card-body">
                <div class="text-center">
                    <div class="text-xs fw-bold text-primary">Total de Atividades</div>
                    <div class="h3 fw-bold">{{ $logs->total() }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-success shadow">
            <div class="card-body">
                <div class="text-center">
                    <div class="text-xs fw-bold text-success">Logins</div>
                    @php
                        $loginsCount = $logs->where('acao', 'login')->count();
                    @endphp
                    <div class="h3 fw-bold">{{ $loginsCount }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-warning shadow">
            <div class="card-body">
                <div class="text-center">
                    <div class="text-xs fw-bold text-warning">Votos</div>
                    @php
                        $votosCount = $logs->where('acao', 'registrar_voto')->count();
                    @endphp
                    <div class="h3 fw-bold">{{ $votosCount }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-info shadow">
            <div class="card-body">
                <div class="text-center">
                    <div class="text-xs fw-bold text-info">Hoje</div>
                    @php
                        $hojeCount = $logs->where('created_at', '>=', today())->count();
                    @endphp
                    <div class="h3 fw-bold">{{ $hojeCount }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
pre {
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 0.875rem;
    line-height: 1.4;
    white-space: pre-wrap;
    word-wrap: break-word;
}
</style>
@endsection