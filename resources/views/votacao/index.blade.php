@extends('layouts.app')

@section('title', 'Área de Votação')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0 text-dark">
            <i class="fas fa-vote-yea me-2"></i>Área de Votação
        </h1>
        <p class="text-muted">Participe das eleições da Universidade Pedagógica</p>
    </div>
</div>

@if($eleicoes->count() > 0)
<div class="card shadow mb-4">
    <div class="card-header bg-success text-white">
        <h6 class="m-0 fw-bold">
            <i class="fas fa-check-circle me-2"></i>Eleições Disponíveis para Voto
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($eleicoes as $eleicao)
            <div class="col-md-6 mb-4">
                <div class="card h-100 voting-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">{{ $eleicao->titulo }}</h6>
                        <span class="badge bg-success">Disponível</span>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            <i class="fas fa-briefcase me-2"></i>
                            {{ $eleicao->cargo->nome }}
                        </p>
                        
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Início: {{ $eleicao->data_inicio->format('d/m/Y H:i') }}
                            </small>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-calendar-times me-1"></i>
                                Término: {{ $eleicao->data_fim->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: {{ $eleicao->percentual_conclusao }}%">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between small text-muted mb-3">
                            <span>{{ $eleicao->votos_registrados }} votos</span>
                            <span>{{ $eleicao->percentual_conclusao }}% concluído</span>
                        </div>
                        
                        <div class="d-grid">
                            <a href="{{ route('votacao.show', $eleicao) }}" class="btn btn-success">
                                <i class="fas fa-arrow-right me-2"></i>Ir para Votação
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@if($eleicoesVotadas->count() > 0)
<div class="card shadow mb-4">
    <div class="card-header bg-info text-white">
        <h6 class="m-0 fw-bold">
            <i class="fas fa-history me-2"></i>Eleições em que Já Votou
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Eleição</th>
                        <th>Cargo</th>
                        <th>Data do Voto</th>
                        <th>Status</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eleicoesVotadas as $eleicao)
                    <tr>
                        <td>
                            <strong>{{ $eleicao->titulo }}</strong>
                            <div class="small text-muted">
                                {{ $eleicao->descricao }}
                            </div>
                        </td>
                        <td>{{ $eleicao->cargo->nome }}</td>
                        <td>
                            @php
                                $voto = $eleicao->votos()->where('user_id', auth()->id())->first();
                            @endphp
                            {{ $voto->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            @if($eleicao->status === 'ativa')
                                <span class="badge bg-success">Em Andamento</span>
                            @elseif($eleicao->status === 'concluida')
                                <span class="badge bg-info">Concluída</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('votacao.comprovante', $voto) }}" 
                               class="btn btn-sm btn-outline-info">
                                <i class="fas fa-file-alt me-1"></i>Comprovante
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if($eleicoesAgendadas->count() > 0)
<div class="card shadow">
    <div class="card-header bg-warning">
        <h6 class="m-0 fw-bold">
            <i class="fas fa-clock me-2"></i>Próximas Eleições Agendadas
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($eleicoesAgendadas as $eleicao)
            <div class="col-md-4 mb-3">
                <div class="card">
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
        </div>
    </div>
</div>
@endif

@if($eleicoes->count() === 0 && $eleicoesVotadas->count() === 0 && $eleicoesAgendadas->count() === 0)
<div class="card shadow">
    <div class="card-body text-center py-5">
        <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
        <h4 class="text-muted">Nenhuma Eleição Disponível</h4>
        <p class="text-muted">
            Não há eleições disponíveis para votação no momento.
        </p>
    </div>
</div>
@endif
<!--
<div class="card shadow mt-4">
    <div class="card-header">
        <h6 class="m-0 fw-bold text-primary">
            <i class="fas fa-info-circle me-2"></i>Instruções para Votação
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="text-center mb-3">
                    <div class="step-number mb-3">1</div>
                    <h6>Selecione a Eleição</h6>
                    <p class="small text-muted">
                        Escolha uma das eleições disponíveis para o seu perfil.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center mb-3">
                    <div class="step-number mb-3">2</div>
                    <h6>Conheça os Candidatos</h6>
                    <p class="small text-muted">
                        Veja as propostas e informações de cada candidato.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center mb-3">
                    <div class="step-number mb-3">3</div>
                    <h6>Registre seu Voto</h6>
                    <p class="small text-muted">
                        Selecione seu candidato e confirme o voto.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>-->
@endsection

@section('styles')
<style>
</style>
@endsection