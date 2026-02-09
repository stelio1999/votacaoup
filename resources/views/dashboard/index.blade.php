@extends('layouts.app')

@section('title', 'Dashboard - Sistema de Votação')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h1 class="h3 mb-0 text-dark">Dashboard</h1>
        <p class="text-muted">Bem-vindo, {{ auth()->user()->name }}! Aqui está o resumo do sistema.</p>
    </div>
</div>

<!-- Cartões de Estatísticas -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                            Eleitores Ativos
                        </div>
                        <div class="h5 mb-0 fw-bold text-dark">{{ $totalEleitores }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">
                            Eleições Ativas
                        </div>
                        <div class="h5 mb-0 fw-bold text-dark">{{ $eleicoesAtivas }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-vote-yea fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">
                            Candidatos
                        </div>
                        <div class="h5 mb-0 fw-bold text-dark">{{ $totalCandidatos }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-tie fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                            Votos Hoje
                        </div>
                        <div class="h5 mb-0 fw-bold text-dark">{{ $votosHoje }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos e Tabelas -->
<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">Atividade Recente</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Utilizador</th>
                                <th>Ação</th>
                                <th>Data/Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($atividades as $atividade)
                            <tr>
                                <td>{{ $atividade->user->name }}</td>
                                <td>{{ $atividade->descricao }}</td>
                                <td>{{ \Carbon\Carbon::parse($atividade->created_at)->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">Eleições Ativas</h6>
            </div>
            <div class="card-body">
                @foreach($eleicoes as $eleicao)
                <div class="mb-3">
                    <h6 class="fw-bold">{{ $eleicao->titulo }}</h6>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-success" 
                             role="progressbar" 
                             style="width: {{ $eleicao->percentual_conclusao }}%">
                        </div>
                    </div>
                    <div class="small text-muted">
                        {{ $eleicao->votos_count }} votos • 
                        {{ $eleicao->percentual_conclusao }}% concluído
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Informações do Sistema -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">Informações do Sistema</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Versão:</strong> 1.0.0</p>
                        <p><strong>Última Atualização:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                        <p><strong>Usuários Online:</strong> {{ $usuariosOnline }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Status do Sistema:</strong> <span class="badge bg-success">Operacional</span></p>
                        <p><strong>Horário do Servidor:</strong> <span class="current-datetime"></span></p>
                        <p><strong>Suporte:</strong> votacao@up.ac.mz</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection