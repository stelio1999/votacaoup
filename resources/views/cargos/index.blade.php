@extends('layouts.app')

@section('title', 'Gestão de Cargos')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-dark">
                <i class="fas fa-briefcase me-2"></i>Gestão de Cargos
            </h1>
            <a href="{{ route('cargos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Novo Cargo
            </a>
        </div>
        <p class="text-muted">Gerencie os cargos disponíveis para eleição</p>
    </div>
</div>

<div class="card shadow">
    <div class="card-header">
        <h6 class="m-0 fw-bold text-primary">Lista de Cargos</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th>Mandato</th>
                        <th>Status</th>
                        <th>Eleições</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cargos as $cargo)
                    <tr>
                        <td>
                            <strong>{{ $cargo->nome }}</strong>
                        </td>
                        <td>
                            @if($cargo->descricao)
                                <small class="text-muted">{{ Str::limit($cargo->descricao, 50) }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @switch($cargo->categoria)
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
                        <td>{{ $cargo->mandato_meses }} meses</td>
                        <td>
                            @if($cargo->ativo)
                                <span class="badge bg-success">Ativo</span>
                            @else
                                <span class="badge bg-danger">Inativo</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $cargo->eleicoes_count ?? 0 }}</span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('cargos.show', $cargo) }}" 
                                   class="btn btn-sm btn-info" 
                                   data-bs-toggle="tooltip" 
                                   title="Ver detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('cargos.edit', $cargo) }}" 
                                   class="btn btn-sm btn-warning" 
                                   data-bs-toggle="tooltip" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('cargos.toggle-status', $cargo) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Tem certeza que deseja alterar o status deste cargo?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="btn btn-sm {{ $cargo->ativo ? 'btn-secondary' : 'btn-success' }}"
                                            data-bs-toggle="tooltip" 
                                            title="{{ $cargo->ativo ? 'Desativar' : 'Ativar' }}">
                                        <i class="fas {{ $cargo->ativo ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('cargos.destroy', $cargo) }}" 
                                      method="POST" 
                                      class="d-inline confirm-action"
                                      data-confirm="Tem certeza que deseja excluir este cargo? Esta ação não pode ser desfeita.">
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
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Mostrando {{ $cargos->firstItem() }} a {{ $cargos->lastItem() }} de {{ $cargos->total() }} cargos
            </div>
            <div>
                {{ $cargos->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card border-left-primary shadow">
            <div class="card-body">
                <div class="text-center">
                    <div class="text-xs fw-bold text-primary">Total de Cargos</div>
                    <div class="h3 fw-bold">{{ $cargos->total() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-success shadow">
            <div class="card-body">
                <div class="text-center">
                    <div class="text-xs fw-bold text-success">Cargos Ativos</div>
                    <div class="h3 fw-bold">{{ $cargos->where('ativo', true)->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-info shadow">
            <div class="card-body">
                <div class="text-center">
                    <div class="text-xs fw-bold text-info">Para Estudantes</div>
                    <div class="h3 fw-bold">{{ $cargos->where('categoria', 'estudante')->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-warning shadow">
            <div class="card-body">
                <div class="text-center">
                    <div class="text-xs fw-bold text-warning">Para Docentes</div>
                    <div class="h3 fw-bold">{{ $cargos->where('categoria', 'docente')->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mt-4">
    <div class="card-header">
        <h6 class="m-0 fw-bold text-primary">
            <i class="fas fa-info-circle me-2"></i>Informações sobre Categorias
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="alert alert-success">
                    <h6 class="alert-heading">
                        <i class="fas fa-user-graduate me-2"></i>Cargos para Estudantes
                    </h6>
                    <p class="mb-0 small">
                        Representantes estudantis, delegados de turma, membros do conselho acadêmico estudantil.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-primary">
                    <h6 class="alert-heading">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Cargos para Docentes
                    </h6>
                    <p class="mb-0 small">
                        Coordenadores de curso, chefes de departamento, membros do conselho acadêmico.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-secondary">
                    <h6 class="alert-heading">
                        <i class="fas fa-user-tie me-2"></i>Cargos para Técnicos
                    </hh6>
                    <p class="mb-0 small">
                        Representantes do corpo técnico-administrativo em comissões e conselhos.
                    </p>
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
@endsection