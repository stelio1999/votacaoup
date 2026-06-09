@extends('layouts.app')

@section('title', 'Detalhes do Candidato')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('candidatos.index') }}">Candidatos</a></li>
                <li class="breadcrumb-item active">Detalhes</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-dark">
                <i class="fas fa-user-tie me-2"></i>Detalhes do Candidato
            </h1>
            <div class="btn-group">
                <a href="{{ route('candidatos.edit', $candidato) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Editar
                </a>
                <a href="{{ route('candidatos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 fw-bold">Informações do Candidato</h6>
            </div>
            <div class="card-body text-center">
                @if($candidato->foto)
                    <img src="{{ Storage::url($candidato->foto) }}" 
                         alt="{{ $candidato->user->name }}" 
                         class="rounded-circle mb-3"
                         style="width: 150px; height: 150px; object-fit: cover; border: 3px solid var(--azul-claro);">
                @else
                    <div class="avatar-large mb-3">
                        <span class="initials">{{ substr($candidato->user->name, 0, 2) }}</span>
                    </div>
                @endif
                
                <h4 class="fw-bold mb-2">{{ $candidato->user->name }}</h4>
                
                <div class="mb-3">
                    <span class="badge bg-dark fs-6">#{{ $candidato->numero_candidato }}</span>
                </div>
                
                <div class="mb-3">
                    @if($candidato->aprovado)
                        <span class="badge bg-success fs-6">
                            <i class="fas fa-check-circle me-1"></i>Aprovado
                        </span>
                    @else
                        <span class="badge bg-warning fs-6">
                            <i class="fas fa-clock me-1"></i>Pendente
                        </span>
                        @if($candidato->motivo_reprovacao)
                            <div class="alert alert-danger mt-2">
                                <small>
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <strong>Motivo da reprovação:</strong><br>
                                    {{ $candidato->motivo_reprovacao }}
                                </small>
                            </div>
                        @endif
                    @endif
                </div>
                
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Email</span>
                        <span class="fw-bold">{{ $candidato->user->email }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Categoria</span>
                        <span>
                            @switch($candidato->user->categoria)
                                @case('estudante')
                                    <span class="badge bg-success">Estudante</span>
                                    @break
                                @case('docente')
                                    <span class="badge bg-primary">Docente</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">Técnico</span>
                            @endswitch
                        </span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Matrícula</span>
                        <span class="fw-bold">{{ $candidato->user->matricula ?? 'N/A' }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Curso/Departamento</span>
                        <span class="fw-bold">{{ $candidato->user->curso ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card shadow mt-4">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 fw-bold">Informações da Eleição</h6>
            </div>
            <div class="card-body">
                <h5 class="fw-bold mb-3">{{ $candidato->eleicao->titulo }}</h5>
                
                <div class="mb-3">
                    <p class="mb-1">
                        <i class="fas fa-briefcase me-2"></i>
                        <strong>Cargo:</strong> {{ $candidato->eleicao->cargo->nome }}
                    </p>
                    <p class="mb-1">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <strong>Data da eleição:</strong> 
                        {{ $candidato->eleicao->data_inicio->format('d/m/Y') }}
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-flag me-2"></i>
                        <strong>Status da eleição:</strong>
                        @switch($candidato->eleicao->status)
                            @case('agendada')
                                <span class="badge bg-warning">Agendada</span>
                                @break
                            @case('ativa')
                                <span class="badge bg-success">Ativa</span>
                                @break
                            @case('concluida')
                                <span class="badge bg-info">Concluída</span>
                                @break
                            @default
                                <span class="badge bg-danger">Cancelada</span>
                        @endswitch
                    </p>
                </div>
                
                <div class="d-grid">
                    <a href="{{ route('eleicoes.show', $candidato->eleicao) }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-external-link-alt me-1"></i>Ver Detalhes da Eleição
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 fw-bold">
                    <i class="fas fa-file-alt me-2"></i>Proposta do Candidato
                </h6>
            </div>
            <div class="card-body">
                @if($candidato->proposta)
                    <div class="proposta-content">
                        {!! nl2br(e($candidato->proposta)) !!}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhuma proposta registrada</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Estatísticas de Votos
                    </h6>
                    <span class="badge bg-primary fs-6">
                        {{ $votos->total() }} Votos
                    </span>
                </div>
            </div>
            <div class="card-body">
                @if($votos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Eleitor</th>
                                    <th>Email</th>
                                    <th>Categoria</th>
                                    <th>Data/Hora</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($votos as $voto)
                                <tr>
                                    <td>{{ $voto->user->name }}</td>
                                    <td>{{ $voto->user->email }}</td>
                                    <td>
                                        @switch($voto->user->categoria)
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
                                    <td>{{ $voto->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-info" 
                                                data-bs-toggle="tooltip" 
                                                title="Ver detalhes do voto"
                                                onclick="verDetalhesVoto({{ $voto->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
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
                        <i class="fas fa-vote-yea fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhum voto registrado para este candidato</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalhes do voto -->
<div class="modal fade" id="detalhesVotoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes do Voto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalhesVotoContent">
                <!-- Conteúdo será carregado via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>

</style>
@endsection

@section('scripts')
<script>
function verDetalhesVoto(votoId) {
    // Carregar detalhes do voto via AJAX
    $.ajax({
        url: '/api/votos/' + votoId + '/detalhes',
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#detalhesVotoContent').html(response.html);
            $('#detalhesVotoModal').modal('show');
        },
        error: function() {
            alert('Erro ao carregar detalhes do voto.');
        }
    });
}

$(document).ready(function() {
    // Tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endsection