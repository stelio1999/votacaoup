@extends('layouts.app')

@section('title', $eleicao->titulo)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('eleicoes.index') }}">Eleições</a></li>
                <li class="breadcrumb-item active">{{ $eleicao->titulo }}</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1 text-dark">{{ $eleicao->titulo }}</h1>
                <p class="text-muted mb-0">
                    <i class="fas fa-briefcase me-2"></i>{{ optional($eleicao->cargo)->nome ?? 'Sem cargo' }}
                    • 
                    <i class="fas fa-calendar me-2"></i>{{ optional($eleicao->data_inicio)->format('d/m/Y') ?? '---' }} 
- 
{{ optional($eleicao->data_fim)->format('d/m/Y') ?? '---' }}
                </p>
            </div>
            <div class="btn-group">
                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('comissao'))
                    @if($eleicao->status == 'agendada')
                        <form action="{{ route('eleicoes.iniciar', $eleicao) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" 
                                    onclick="return confirm('Tem certeza que deseja iniciar esta eleição?')">
                                <i class="fas fa-play me-2"></i>Iniciar Eleição
                            </button>
                        </form>
                    @elseif($eleicao->status == 'ativa')
                        <form action="{{ route('eleicoes.encerrar', $eleicao) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Tem certeza que deseja encerrar esta eleição?')">
                                <i class="fas fa-stop me-2"></i>Encerrar Eleição
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('eleicoes.edit', $eleicao->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                @endif
                
                <a href="{{ route('eleicoes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Status da Eleição -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center mb-3 mb-md-0">
                        @switch($eleicao->status)
                            @case('agendada')
                                <div class="status-indicator bg-secondary">
                                    <i class="fas fa-clock fa-3x"></i>
                                </div>
                                <h5 class="mt-3 mb-0">Agendada</h5>
                                @break
                            @case('ativa')
                                <div class="status-indicator bg-success">
                                    <i class="fas fa-play fa-3x"></i>
                                </div>
                                <h5 class="mt-3 mb-0">Em Andamento</h5>
                                @break
                            @case('concluida')
                                <div class="status-indicator bg-info">
                                    <i class="fas fa-check fa-3x"></i>
                                </div>
                                <h5 class="mt-3 mb-0">Concluída</h5>
                                @break
                            @case('cancelada')
                                <div class="status-indicator bg-danger">
                                    <i class="fas fa-times fa-3x"></i>
                                </div>
                                <h5 class="mt-3 mb-0">Cancelada</h5>
                                @break
                        @endswitch
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-6 col-md-3 mb-3">
                                <div class="text-center">
                                    <div class="h4 fw-bold text-primary mb-1">{{ $estatisticas['total_votos'] }}</div>
                                    <div class="small text-muted">Votos Registrados</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <div class="text-center">
                                    <div class="h4 fw-bold text-success mb-1">{{ $estatisticas['total_eleitores'] }}</div>
                                    <div class="small text-muted">Total de Eleitores</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <div class="text-center">
                                    <div class="h4 fw-bold text-info mb-1">{{ $estatisticas['percentual_conclusao'] }}%</div>
                                    <div class="small text-muted">Participação</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <div class="text-center">
                                    <div class="h4 fw-bold text-warning mb-1">{{ $estatisticas['candidatos'] }}</div>
                                    <div class="small text-muted">Candidatos</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="progress mb-3" style="height: 12px;">
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: {{ $estatisticas['percentual_conclusao'] }}%">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between small text-muted">
                            <span>{{ $eleicao->data_inicio->format('d/m/Y H:i') }}</span>
                            <span>{{ $eleicao->data_fim->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Coluna Esquerda -->
    <div class="col-lg-8">
        <!-- Descrição -->
        @if($eleicao->descricao)
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-file-alt me-2"></i>Descrição da Eleição
                </h6>
            </div>
            <div class="card-body">
                {{ $eleicao->descricao }}
            </div>
        </div>
        @endif
        
        <!-- Candidatos -->
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-user-tie me-2"></i>Candidatos
                </h6>
                <span class="badge bg-primary">{{ $candidatos->count() }}</span>
            </div>
            <div class="card-body">
                @if($candidatos->count() > 0)
                    <div class="row">
                        @foreach($candidatos as $candidato)
                        <div class="col-md-6 mb-4">
                            <div class="card candidate-profile-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        @if($candidato->foto)
                                            <img src="{{ Storage::url($candidato->foto) }}" 
                                                 alt="{{ $candidato->user->name }}" 
                                                 class="rounded-circle me-3"
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="avatar-circle me-3">
                                                <span class="initials">{{ $candidato->iniciais }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="fw-bold mb-1">{{ $candidato->user->name }}</h6>
                                            <div class="small text-muted">
                                                <span class="badge bg-dark">#{{ $candidato->numero_candidato }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($candidato->proposta)
                                    <button class="btn btn-sm btn-outline-info w-100 mb-2"
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#proposta{{ $candidato->id }}">
                                        <i class="fas fa-file-alt me-1"></i>Ver Proposta
                                    </button>
                                    
                                    <div class="collapse" id="proposta{{ $candidato->id }}">
                                        <div class="proposal-preview">
                                            {{ Str::limit($candidato->proposta, 150) }}
                                            @if(strlen($candidato->proposta) > 150)
                                                <a href="#" class="small">Ver mais</a>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($votosPorCandidato->where('candidato_id', $candidato->id)->first())
                                        @php
                                            $votosCandidato = $votosPorCandidato->where('candidato_id', $candidato->id)->first();
                                        @endphp
                                        <div class="mt-3 pt-3 border-top">
                                            <div class="small text-muted">
                                                <i class="fas fa-vote-yea me-1"></i>
                                                {{ $votosCandidato->total }} votos
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhum Candidato</h5>
                        <p class="text-muted">Não há candidatos registrados para esta eleição.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Coluna Direita -->
    <div class="col-lg-4">
        <!-- Informações -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>Informações
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th class="text-muted" width="40%">Cargo:</th>
                        <td>{{ $eleicao->cargo->nome }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Categoria:</th>
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
                    </tr>
                    <tr>
                        <th class="text-muted">Início:</th>
                        <td>{{ $eleicao->data_inicio->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Término:</th>
                        <td>{{ $eleicao->data_fim->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Duração:</th>
                        <td>{{ $eleicao->data_inicio->diffInDays($eleicao->data_fim) }} dias</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Criada em:</th>
                        <td>{{ $eleicao->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
                
                @if($eleicao->observacoes)
                <div class="mt-3 pt-3 border-top">
                    <h6 class="fw-bold text-muted mb-2">Observações:</h6>
                    <p class="small text-muted mb-0">{{ $eleicao->observacoes }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Ações Rápidas -->
         
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-bolt me-2"></i>Ações Rápidas
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(auth()->user()->canVote($eleicao))
                        <a href="{{ route('votacao.show', $eleicao) }}" class="btn btn-success">
                            <i class="fas fa-vote-yea me-2"></i>Votar Agora
                        </a>
                    @elseif(auth()->user()->jaVotou($eleicao->id))
                        <button class="btn btn-info" disabled>
                            <i class="fas fa-check-circle me-2"></i>Você já votou
                        </button>
                    @elseif(!$eleicao->estaAtiva)
                        <button class="btn btn-secondary" disabled>
                            <i class="fas fa-clock me-2"></i>Eleição não disponível
                        </button>
                    @endif
                    
                    @if($eleicao->status == 'concluida')
                        <a href="{{ route('resultados.show', $eleicao) }}" class="btn btn-primary">
                            <i class="fas fa-chart-bar me-2"></i>Ver Resultados
                        </a>
                    @endif
                    
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('comissao'))
                        <a href="{{ route('candidatos.index', $eleicao) }}" class="btn btn-warning">
                            <i class="fas fa-users me-2"></i>Gerenciar Candidatos
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Estatísticas Detalhadas -->
        @if($votosPorCandidato->count() > 0)
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-chart-pie me-2"></i>Votos por Candidato
                </h6>
            </div>
            <div class="card-body">
                @foreach($votosPorCandidato as $votoCandidato)
                <div class="mb-3">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>{{ $votoCandidato->candidato->user->name }}</span>
                        <span>{{ $votoCandidato->total }} votos</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        @php
                            $totalVotos = $estatisticas['total_votos'];
                            $percentual = $totalVotos > 0 ? ($votoCandidato->total / $totalVotos) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-info" 
                             role="progressbar" 
                             style="width: {{ $percentual }}%">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.status-indicator {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
}

.candidate-profile-card {
    transition: transform 0.3s ease;
    border: 1px solid #e2e8f0;
}

.candidate-profile-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.proposal-preview {
    max-height: 100px;
    overflow: hidden;
    position: relative;
}

.avatar-circle {
    width: 60px;
    height: 60px;
    background: var(--azul-claro);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.initials {
    font-size: 1.2rem;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Atualizar contador se a eleição estiver ativa
    @if($eleicao->status == 'ativa')
    function updateCountdown() {
        const endTime = new Date('{{ $eleicao->data_fim }}').getTime();
        const now = new Date().getTime();
        const distance = endTime - now;
        
        if (distance < 0) {
            location.reload(); // Recarregar página quando a eleição terminar
        }
    }
    
    // Verificar a cada minuto
    setInterval(updateCountdown, 60000);
    @endif
});
</script>
@endsection