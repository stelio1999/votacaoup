@extends('layouts.app')

@section('title', 'Meu Histórico de Votos')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0 text-dark">
            <i class="fas fa-history me-2"></i>Meu Histórico de Votos
        </h1>
        <p class="text-muted">Consulte todas as eleições em que você votou</p>
    </div>
</div>

<div class="card shadow">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">Histórico de Votos</h6>
            <div class="btn-group">
                <button class="btn btn-outline-primary btn-sm" id="exportBtn">
                    <i class="fas fa-download me-2"></i>Exportar
                </button>
                <a href="{{ route('votacao.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($votos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover" id="votosTable">
                    <thead>
                        <tr>
                            <th>Eleição</th>
                            <th>Cargo</th>
                            <th>Candidato</th>
                            <th>Data do Voto</th>
                            <th>Status da Eleição</th>
                            <th class="text-end">Comprovante</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($votos as $voto)
                        <tr>
                            <td>
                                <strong>{{ $voto->eleicao->titulo }}</strong>
                                @if($voto->eleicao->descricao)
                                    <div class="small text-muted">
                                        {{ Str::limit($voto->eleicao->descricao, 50) }}
                                    </div>
                                @endif
                            </td>
                            <td>{{ $voto->eleicao->cargo->nome }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($voto->candidato->foto)
                                        <img src="{{ Storage::url($voto->candidato->foto) }}" 
                                             alt="{{ $voto->candidato->user->name }}" 
                                             class="rounded-circle me-2"
                                             style="width: 30px; height: 30px; object-fit: cover;">
                                    @else
                                        <div class="avatar-circle-sm me-2">
                                            <span class="initials-sm">{{ $voto->candidato->iniciais }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        {{ $voto->candidato->user->name }}
                                        <div class="small text-muted">
                                            Número: {{ $voto->candidato->numero_candidato }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $voto->created_at->format('d/m/Y H:i') }}
                                <div class="small text-muted">
                                    {{ $voto->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td>
                                @switch($voto->eleicao->status)
                                    @case('agendada')
                                        <span class="badge bg-secondary">Agendada</span>
                                        @break
                                    @case('ativa')
                                        <span class="badge bg-success">Em Andamento</span>
                                        @break
                                    @case('concluida')
                                        <span class="badge bg-info">Concluída</span>
                                        @break
                                    @case('cancelada')
                                        <span class="badge bg-danger">Cancelada</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="text-end">
                                <a href="{{ route('votacao.comprovante', $voto) }}" 
                                   class="btn btn-sm btn-outline-info"
                                   data-bs-toggle="tooltip" 
                                   title="Ver comprovante">
                                    <i class="fas fa-file-alt"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Mostrando {{ $votos->firstItem() }} a {{ $votos->lastItem() }} de {{ $votos->total() }} votos
                </div>
                <div>
                    {{ $votos->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-vote-yea fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Nenhum Voto Registrado</h4>
                <p class="text-muted mb-4">
                    Você ainda não participou de nenhuma eleição.
                </p>
                <a href="{{ route('votacao.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-2"></i>Ver Eleições Disponíveis
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Estatísticas -->
@if($votos->count() > 0)
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card border-left-primary shadow">
            <div class="card-body">
                <div class="text-center">
                    <div class="text-xs fw-bold text-primary">Total de Votos</div>
                    <div class="h3 fw-bold">{{ $votos->total() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-success shadow">
            <div class="card-body">
                <div class="text-center">
                    @php
                        $eleicoesConcluidas = $votos->where('eleicao.status', 'concluida')->count();
                    @endphp
                    <div class="text-xs fw-bold text-success">Eleições Concluídas</div>
                    <div class="h3 fw-bold">{{ $eleicoesConcluidas }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-info shadow">
            <div class="card-body">
                <div class="text-center">
                    @php
                        $eleicoesAtivas = $votos->where('eleicao.status', 'ativa')->count();
                    @endphp
                    <div class="text-xs fw-bold text-info">Eleições em Andamento</div>
                    <div class="h3 fw-bold">{{ $eleicoesAtivas }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-warning shadow">
            <div class="card-body">
                <div class="text-center">
                    @php
                        $primeiroVoto = $votos->last();
                        $diasPrimeiroVoto = $primeiroVoto ? $primeiroVoto->created_at->diffInDays(now()) : 0;
                    @endphp
                    <div class="text-xs fw-bold text-warning">Dias no Sistema</div>
                    <div class="h3 fw-bold">{{ $diasPrimeiroVoto }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mt-4">
    <div class="card-header">
        <h6 class="m-0 fw-bold text-primary">
            <i class="fas fa-chart-pie me-2"></i>Distribuição de Votos por Mês
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            @php
                $votosPorMes = $votos->groupBy(function($voto) {
                    return $voto->created_at->format('Y-m');
                })->map->count();
            @endphp
            
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Mês/Ano</th>
                                <th>Votos</th>
                                <th width="70%">Gráfico</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($votosPorMes as $mes => $total)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($mes)->translatedFormat('F Y') }}</td>
                                <td><strong>{{ $total }}</strong></td>
                                <td>
                                    <div class="progress" style="height: 10px;">
                                        @php
                                            $maxVotos = $votosPorMes->max();
                                            $percentual = $maxVotos > 0 ? ($total / $maxVotos) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar bg-success" 
                                             role="progressbar" 
                                             style="width: {{ $percentual }}%">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="display-6 fw-bold text-primary">{{ $votos->count() }}</div>
                    <div class="text-muted mb-3">Votos Totais</div>
                    <div class="small text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Você participou ativamente do processo eleitoral da universidade.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<style>
.avatar-circle-sm {
    width: 30px;
    height: 30px;
    background: var(--azul-claro);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.8rem;
}

.initials-sm {
    font-size: 0.8rem;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Inicializar DataTable
    $('#votosTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-PT.json"
        },
        "pageLength": 10,
        "order": [[3, 'desc']],
        "dom": '<"top"f>rt<"bottom"lip><"clear">',
    });
    
    // Tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Exportar histórico
    $('#exportBtn').click(function() {
        // Implementar exportação
        alert('Funcionalidade de exportação em desenvolvimento.');
    });
});
</script>
@endsection