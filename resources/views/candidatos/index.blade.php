@extends('layouts.app')

@section('title', 'Gestão de Candidatos')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-dark">
                <i class="fas fa-user-tie me-2"></i>Gestão de Candidatos
            </h1>
            <a href="{{ route('candidatos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nova Candidatura
            </a>
        </div>
        <p class="text-muted">Gerencie as candidaturas para as eleições</p>
    </div>
</div>

<!-- Filtros -->
 

<div class="card shadow">
    <div class="card-header">
        <h6 class="m-0 fw-bold text-primary">Lista de Candidaturas</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>Candidato</th>
                        <th>Eleição</th>
                        <th>Cargo</th>
                        <th>Número</th>
                        <th>Status</th>
                        <th>Votos</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($candidatos as $candidato)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($candidato->foto)
                                    <img src="{{ Storage::url($candidato->foto) }}" 
                                         alt="{{ $candidato->user->name }}" 
                                         class="rounded-circle me-3"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="avatar-circle me-3">
                                        <span class="initials">{{ substr($candidato->user->name, 0, 2) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <strong>{{ $candidato->user->name }}</strong>
                                    <div class="small text-muted">{{ $candidato->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <small>{{ $candidato->eleicao->titulo }}</small>
                            <div class="small text-muted">
                                {{ $candidato->eleicao->data_inicio->format('d/m/Y') }}
                            </div>
                        </td>
                        <td>{{ $candidato->eleicao->cargo->nome }}</td>
                        <td>
                            <span class="badge bg-dark">#{{ $candidato->numero_candidato }}</span>
                        </td>
                        <td>
                            @if($candidato->aprovado)
                                <span class="badge bg-success">Aprovado</span>
                            @else
                                <span class="badge bg-warning">Pendente</span>
                                @if($candidato->motivo_reprovacao)
                                    <div class="small text-danger mt-1">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Reprovado
                                    </div>
                                @endif
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $candidato->votos_count ?? 0 }}</span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('candidatos.show', $candidato) }}" 
                                   class="btn btn-sm btn-info" 
                                   data-bs-toggle="tooltip" 
                                   title="Ver detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('candidatos.edit', $candidato) }}" 
                                   class="btn btn-sm btn-warning" 
                                   data-bs-toggle="tooltip" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if(!$candidato->aprovado)
                                <form action="{{ route('candidatos.aprovar', $candidato) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Tem certeza que deseja aprovar esta candidatura?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="btn btn-sm btn-success"
                                            data-bs-toggle="tooltip" 
                                            title="Aprovar">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                
                                <button type="button" 
                                        class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#reprovarModal{{ $candidato->id }}"
                                        title="Reprovar">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                                
                                <form action="{{ route('candidatos.destroy', $candidato) }}" 
                                      method="POST" 
                                      class="d-inline confirm-action"
                                      data-confirm="Tem certeza que deseja excluir esta candidatura? Esta ação não pode ser desfeita.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger"
                                            data-bs-toggle="tooltip" 
                                            title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Modal de Reprovação -->
                            <div class="modal fade" id="reprovarModal{{ $candidato->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reprovar Candidatura</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('candidatos.reprovar', $candidato) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <p>Você está prestes a reprovar a candidatura de <strong>{{ $candidato->user->name }}</strong>.</p>
                                                <div class="mb-3">
                                                    <label for="motivo{{ $candidato->id }}" class="form-label">Motivo da Reprovação *</label>
                                                    <textarea class="form-control" 
                                                              id="motivo{{ $candidato->id }}" 
                                                              name="motivo_reprovacao" 
                                                              rows="3" 
                                                              required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-danger">Confirmar Reprovação</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Mostrando {{ $candidatos->firstItem() }} a {{ $candidatos->lastItem() }} de {{ $candidatos->total() }} candidaturas
            </div>
            <div>
                {{ $candidatos->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas -->


<style>

</style>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
     
    
    // Tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endsection