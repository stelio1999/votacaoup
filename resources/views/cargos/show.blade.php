@extends('layouts.app')

@section('title', 'Detalhes do Cargo: ' . $cargo->nome)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cargos.index') }}">Cargos</a></li>
                <li class="breadcrumb-item active">{{ $cargo->nome }}</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-dark">
                <i class="fas fa-briefcase me-2"></i>{{ $cargo->nome }}
            </h1>
            <div class="btn-group" role="group">
                <a href="{{ route('cargos.edit', $cargo) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Editar
                </a>
                <a href="{{ route('cargos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>
        </div>
        <p class="text-muted">Detalhes e informações sobre o cargo</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>Informações do Cargo
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Nome do Cargo</label>
                    <p class="mb-0">{{ $cargo->nome }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Categoria</label>
                    <p class="mb-0">
                        @switch($cargo->categoria)
                            @case('estudante')
                                <span class="badge bg-success">Estudante</span>
                                @break
                            @case('docente')
                                <span class="badge bg-primary">Docente</span>
                                @break
                            @default
                                <span class="badge bg-secondary">Técnico Administrativo</span>
                        @endswitch
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Mandato</label>
                    <p class="mb-0">{{ $cargo->mandato_meses }} meses</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Status</label>
                    <p class="mb-0">
                        @if($cargo->ativo)
                            <span class="badge bg-success">Ativo</span>
                        @else
                            <span class="badge bg-danger">Inativo</span>
                        @endif
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Criado em</label>
                    <p class="mb-0">{{ $cargo->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Atualizado em</label>
                    <p class="mb-0">{{ $cargo->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
        
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-chart-bar me-2"></i>Estatísticas
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="display-6 fw-bold text-primary">
                        {{ $eleicoes->total() }}
                    </div>
                    <div class="text-muted">Eleições Realizadas</div>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h5 fw-bold text-success">
                                {{ $eleicoes->where('status', 'concluida')->count() }}
                            </div>
                            <div class="small text-muted">Concluídas</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h5 fw-bold text-info">
                                {{ $eleicoes->where('status', 'ativa')->count() }}
                            </div>
                            <div class="small text-muted">Ativas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-vote-yea me-2"></i>Eleições para este Cargo
                    </h6>
                    @if($cargo->ativo)
                    <a href="{{ route('eleicoes.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Nova Eleição
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($eleicoes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Período</th>
                                <th>Status</th>
                                <th>Participação</th>
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
                                <td>
                                    <small class="d-block">{{ $eleicao->data_inicio->format('d/m/Y') }}</small>
                                    <small class="d-block text-muted">{{ $eleicao->data_fim->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    @switch($eleicao->status)
                                        @case('agendada')
                                            <span class="badge bg-warning">Agendada</span>
                                            @break
                                        @case('ativa')
                                            <span class="badge bg-success">Ativa</span>
                                            @break
                                        @case('concluida')
                                            <span class="badge bg-info">Concluída</span>
                                            @break
                                        @case('cancelada')
                                            <span class="badge bg-danger">Cancelada</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" 
                                             style="width: {{ $eleicao->percentual_conclusao }}%">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $eleicao->percentual_conclusao }}%</small>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('eleicoes.show', $eleicao) }}" 
                                       class="btn btn-sm btn-info"
                                       data-bs-toggle="tooltip" 
                                       title="Ver detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
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
                @else
                <div class="text-center py-4">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhuma Eleição Encontrada</h5>
                    <p class="text-muted">
                        Não há eleições cadastradas para este cargo.
                    </p>
                    @if($cargo->ativo)
                    <a href="{{ route('eleicoes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Criar Primeira Eleição
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
        
        @if($cargo->descricao)
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-file-alt me-2"></i>Descrição do Cargo
                </h6>
            </div>
            <div class="card-body">
                <div class="prose">
                    {!! nl2br(e($cargo->descricao)) !!}
                </div>
            </div>
        </div>
        @endif
        
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-exclamation-triangle me-2"></i>Ações
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <form action="{{ route('cargos.toggle-status', $cargo) }}" 
                          method="POST" 
                          class="d-inline"
                          onsubmit="return confirm('Tem certeza que deseja alterar o status deste cargo?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="btn {{ $cargo->ativo ? 'btn-warning' : 'btn-success' }} w-100">
                            <i class="fas {{ $cargo->ativo ? 'fa-ban' : 'fa-check' }} me-2"></i>
                            {{ $cargo->ativo ? 'Desativar' : 'Ativar' }} Cargo
                        </button>
                    </form>
                    
                    <form action="{{ route('cargos.destroy', $cargo) }}" 
                          method="POST" 
                          class="d-inline"
                          onsubmit="return confirm('ATENÇÃO: Esta ação não pode ser desfeita. Tem certeza que deseja excluir este cargo?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Excluir Cargo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>

<style>
.prose {
    line-height: 1.6;
    color: #4a5568;
}

.prose p {
    margin-bottom: 1rem;
}
</style>
@endsection