@extends('layouts.app')

@section('title', 'Detalhes do Usuário - ' . $user->name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Usuários</a></li>
                <li class="breadcrumb-item active">{{ $user->name }}</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-dark">
                <i class="fas fa-user me-2"></i>Detalhes do Usuário
            </h1>
            <div class="btn-group">
                <a href="{{ route('usuarios.edit', $user) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Editar
                </a>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Coluna de Informações -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-body text-center">
                <div class="avatar-profile mb-4">
                    <div class="avatar-circle-lg">
                        <span class="initials-lg">{{ $user->iniciais }}</span>
                    </div>
                </div>
                
                <h4 class="mb-2">{{ $user->name }}</h4>
                
                <div class="mb-3">
                    @switch($user->role)
                        @case('admin')
                            <span class="badge bg-danger">Administrador</span>
                            @break
                        @case('comissao')
                            <span class="badge bg-warning">Comissão Eleitoral</span>
                            @break
                        @default
                            <span class="badge bg-info">Eleitor</span>
                    @endswitch
                    
                    @switch($user->categoria)
                        @case('estudante')
                            <span class="badge bg-success">Estudante</span>
                            @break
                        @case('docente')
                            <span class="badge bg-primary">Docente</span>
                            @break
                        @default
                            <span class="badge bg-secondary">Técnico</span>
                    @endswitch
                    
                    @if($user->ativo)
                        <span class="badge bg-success">Ativo</span>
                    @else
                        <span class="badge bg-danger">Inativo</span>
                    @endif
                </div>
                
                <div class="text-muted mb-4">
                    <p class="mb-1">
                        <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                    </p>
                    @if($user->telefone)
                        <p class="mb-1">
                            <i class="fas fa-phone me-2"></i>{{ $user->telefone }}
                        </p>
                    @endif
                    @if($user->matricula)
                        <p class="mb-1">
                            <i class="fas fa-id-card me-2"></i>Matrícula: {{ $user->matricula }}
                        </p>
                    @endif
                    @if($user->ultimo_acesso)
                        <p class="mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Último acesso: {{ $user->ultimo_acesso->diffForHumans() }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Estatísticas do Usuário -->
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">Estatísticas</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="h4 fw-bold text-primary">{{ $user->votos->count() }}</div>
                        <div class="small text-muted">Votos</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="h4 fw-bold text-success">{{ $user->candidaturas->count() }}</div>
                        <div class="small text-muted">Candidaturas</div>
                    </div>
                    <div class="col-6">
                        <div class="h4 fw-bold text-info">{{ $logs->count() }}</div>
                        <div class="small text-muted">Logs</div>
                    </div>
                    <div class="col-6">
                        <div class="h4 fw-bold text-warning">
                            {{ $user->created_at->diffInDays(now()) }}
                        </div>
                        <div class="small text-muted">Dias no sistema</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Coluna de Conteúdo -->
    <div class="col-lg-8">
        <!-- Abas -->
        <div class="card shadow">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="userTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info">
                            <i class="fas fa-info-circle me-2"></i>Informações
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="votos-tab" data-bs-toggle="tab" data-bs-target="#votos">
                            <i class="fas fa-vote-yea me-2"></i>Votos
                            <span class="badge bg-primary ms-2">{{ $votos->total() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="candidaturas-tab" data-bs-toggle="tab" data-bs-target="#candidaturas">
                            <i class="fas fa-user-tie me-2"></i>Candidaturas
                            <span class="badge bg-success ms-2">{{ $candidaturas->total() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs">
                            <i class="fas fa-history me-2"></i>Logs
                            <span class="badge bg-info ms-2">{{ $logs->total() }}</span>
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="userTabsContent">
                    <!-- Tab Informações -->
                    <div class="tab-pane fade show active" id="info">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <th class="text-muted" width="40%">Nome Completo:</th>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Email:</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Papel:</th>
                                        <td>
                                            @switch($user->role)
                                                @case('admin')
                                                    <span class="badge bg-danger">Administrador</span>
                                                    @break
                                                @case('comissao')
                                                    <span class="badge bg-warning">Comissão Eleitoral</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-info">Eleitor</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Status:</th>
                                        <td>
                                            @if($user->ativo)
                                                <span class="badge bg-success">Ativo</span>
                                            @else
                                                <span class="badge bg-danger">Inativo</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <th class="text-muted" width="40%">Categoria:</th>
                                        <td>
                                            @switch($user->categoria)
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
                                    </tr>
                                    @if($user->matricula)
                                    <tr>
                                        <th class="text-muted">Matrícula:</th>
                                        <td>{{ $user->matricula }}</td>
                                    </tr>
                                    @endif
                                    @if($user->curso)
                                    <tr>
                                        <th class="text-muted">Curso/Departamento:</th>
                                        <td>{{ $user->curso }}</td>
                                    </tr>
                                    @endif
                                    @if($user->telefone)
                                    <tr>
                                        <th class="text-muted">Telefone:</th>
                                        <td>{{ $user->telefone }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th class="text-muted">Registrado em:</th>
                                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        @if($user->categoria == 'estudante')
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-user-graduate me-2"></i>
                            <strong>Estudante</strong> - Este usuário tem direito a votar em eleições para cargos estudantis.
                        </div>
                        @elseif($user->categoria == 'docente')
                        <div class="alert alert-primary mt-3">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            <strong>Docente</strong> - Este usuário tem direito a votar em eleições para cargos docentes.
                        </div>
                        @else
                        <div class="alert alert-secondary mt-3">
                            <i class="fas fa-user-tie me-2"></i>
                            <strong>Técnico Administrativo</strong> - Este usuário tem direito a votar em eleições para cargos técnicos.
                        </div>
                        @endif
                    </div>
                    
                    <!-- Tab Votos -->
                    <div class="tab-pane fade" id="votos">
                        @if($votos->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Eleição</th>
                                            <th>Candidato</th>
                                            <th>Data</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($votos as $voto)
                                        <tr>
                                            <td>
                                                <strong>{{ $voto->eleicao->titulo }}</strong>
                                                <div class="small text-muted">{{ $voto->eleicao->cargo->nome }}</div>
                                            </td>
                                            <td>{{ $voto->candidato->user->name }}</td>
                                            <td>{{ $voto->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($voto->valido)
                                                    <span class="badge bg-success">Válido</span>
                                                @else
                                                    <span class="badge bg-danger">Nulo</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-center mt-3">
                                {{ $votos->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-vote-yea fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Nenhum Voto Registrado</h5>
                                <p class="text-muted">Este usuário ainda não participou de nenhuma eleição.</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Tab Candidaturas -->
                    <div class="tab-pane fade" id="candidaturas">
                        @if($candidaturas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Eleição</th>
                                            <th>Cargo</th>
                                            <th>Número</th>
                                            <th>Status</th>
                                            <th>Votos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($candidaturas as $candidatura)
                                        <tr>
                                            <td>
                                                <strong>{{ $candidatura->eleicao->titulo }}</strong>
                                                <div class="small text-muted">
                                                    {{ $candidatura->eleicao->data_inicio->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td>{{ $candidatura->eleicao->cargo->nome }}</td>
                                            <td>
                                                <span class="badge bg-dark">#{{ $candidatura->numero_candidato }}</span>
                                            </td>
                                            <td>
                                                @if($candidatura->aprovado)
                                                    <span class="badge bg-success">Aprovado</span>
                                                @else
                                                    @if($candidatura->motivo_reprovacao)
                                                        <span class="badge bg-danger">Reprovado</span>
                                                    @else
                                                        <span class="badge bg-warning">Pendente</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $candidatura->votos->count() }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-center mt-3">
                                {{ $candidaturas->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Nenhuma Candidatura</h5>
                                <p class="text-muted">Este usuário não se candidatou a nenhuma eleição.</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Tab Logs -->
                    <div class="tab-pane fade" id="logs">
                        @if($logs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Ação</th>
                                            <th>Descrição</th>
                                            <th>Data</th>
                                            <th>IP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($logs as $log)
                                        <tr>
                                            <td>
                                                @php
                                                    $acao = $log->getAcaoFormatadaAttribute();
                                                @endphp
                                                <i class="fas fa-{{ $acao['icon'] }} text-{{ $acao['color'] }} me-2"></i>
                                                {{ ucfirst(str_replace('_', ' ', $log->acao)) }}
                                            </td>
                                            <td>{{ $log->descricao }}</td>
                                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                            <td><small class="text-muted">{{ $log->ip_address }}</small></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-center mt-3">
                                {{ $logs->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Nenhum Log Registrado</h5>
                                <p class="text-muted">Não há registros de atividade para este usuário.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle-lg {
    width: 120px;
    height: 120px;
    background: var(--azul-claro);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: bold;
    margin: 0 auto;
}

.initials-lg {
    font-size: 2.5rem;
}

.nav-tabs .nav-link {
    color: var(--cinza-texto);
    border: none;
    padding: 0.75rem 1.5rem;
}

.nav-tabs .nav-link.active {
    color: var(--azul-claro);
    border-bottom: 3px solid var(--azul-claro);
    background: transparent;
    font-weight: 600;
}

.nav-tabs .nav-link:hover:not(.active) {
    color: var(--azul-escuro);
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Inicializar tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Ativar abas
    const hash = window.location.hash;
    if (hash) {
        const tab = new bootstrap.Tab(document.querySelector(`a[href="${hash}"]`));
        tab.show();
    }
});
</script>
@endsection