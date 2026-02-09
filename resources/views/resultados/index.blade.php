@extends('layouts.app')

@section('title', 'Resultados das Eleições')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0 text-dark">
            <i class="fas fa-chart-bar me-2"></i>Resultados das Eleições
        </h1>
        <p class="text-muted">Consulte os resultados das eleições concluídas</p>
    </div>
</div>

<div class="row">
    @foreach($eleicoes as $eleicao)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card shadow h-100">
            <div class="card-header">
                <h6 class="m-0 fw-bold">{{ $eleicao->titulo }}</h6>
                <small class="text-muted">{{ $eleicao->cargo->nome }}</small>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Data da Eleição</span>
                        <span>{{ $eleicao->data_inicio->format('d/m/Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Total de Eleitores</span>
                        <span>{{ $eleicao->total_eleitores }}</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Participação</span>
                        <span>{{ $eleicao->percentual_conclusao }}%</span>
                    </div>
                </div>
                
                @if($eleicao->resultados->isNotEmpty())
                    <h6 class="fw-bold mb-3">Top 3 Candidatos</h6>
                    @foreach($eleicao->resultados->take(3) as $index => $resultado)
                    <div class="d-flex align-items-center mb-2">
                        <div class="position-badge me-2">{{ $index + 1 }}</div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $resultado->candidato->user->name }}</div>
                            <div class="small text-muted">
                                {{ $resultado->total_votos }} votos • {{ $resultado->percentual }}%
                            </div>
                        </div>
                        @if($resultado->eleito)
                            <span class="badge bg-success">Eleito</span>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-chart-line fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Resultados ainda não calculados</p>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <div class="d-grid">
                    <a href="{{ route('resultados.show', $eleicao) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-chart-pie me-1"></i>Ver Resultados Completos
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($eleicoes->isEmpty())
<div class="card shadow">
    <div class="card-body text-center py-5">
        <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
        <h4 class="text-muted">Nenhuma Eleição Concluída</h4>
        <p class="text-muted">
            Não há resultados disponíveis no momento.
        </p>
    </div>
</div>
@endif

<div class="d-flex justify-content-center mt-4">
    {{ $eleicoes->links() }}
</div>

<style>
.position-badge {
    width: 30px;
    height: 30px;
    background: var(--azul-claro);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
}
</style>
@endsection