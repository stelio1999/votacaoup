@extends('layouts.app')

@section('title', 'Gestão de Usuários')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-dark">
                <i class="fas fa-users me-2"></i>Gestão de Usuários
            </h1>
            <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Novo Usuário
            </a>
        </div>
        <p class="text-muted">Gerencie os usuários do sistema de votação</p>
    </div>
</div>

<div class="card shadow">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">Lista de Usuários</h6>
            <div class="input-group" style="max-width: 300px;">
                <input type="text" class="form-control" id="searchInput" placeholder="Pesquisar usuários...">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Papel</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Último Acesso</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3">
                                    <span class="initials">{{ substr($user->name, 0, 2) }}</span>
                                </div>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->matricula)
                                    <div class="small text-muted">Matrícula: {{ $user->matricula }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @switch($user->role)
                                @case('admin')
                                    <span class="badge bg-danger">Administrador</span>
                                    @break
                                @case('comissao')
                                    <span class="badge bg-warning">Comissão</span>
                                    @break
                                @default
                                    <span class="badge bg-info">Eleitor</span>
                            @endswitch
                        </td>
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
                        <td>
                            @if($user->ativo)
                                <span class="badge bg-success">Ativo</span>
                            @else
                                <span class="badge bg-danger">Inativo</span>
                            @endif
                        </td>
                        <td>
                            @if($user->ultimo_acesso)
                                <span data-bs-toggle="tooltip" title="{{ $user->ultimo_acesso->format('d/m/Y H:i:s') }}">
                                    {{ $user->ultimo_acesso->diffForHumans() }}
                                </span>
                            @else
                                <span class="text-muted">Nunca acessou</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('usuarios.show', $user) }}" 
                                   class="btn btn-sm btn-info" 
                                   data-bs-toggle="tooltip" 
                                   title="Ver detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('usuarios.edit', $user) }}" 
                                   class="btn btn-sm btn-warning" 
                                   data-bs-toggle="tooltip" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('usuarios.toggle-status', $user) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Tem certeza que deseja alterar o status deste usuário?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="btn btn-sm {{ $user->ativo ? 'btn-secondary' : 'btn-success' }}"
                                            data-bs-toggle="tooltip" 
                                            title="{{ $user->ativo ? 'Desativar' : 'Ativar' }}">
                                        <i class="fas {{ $user->ativo ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                </form>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('usuarios.destroy', $user) }}" 
                                      method="POST" 
                                      class="d-inline confirm-action"
                                      data-confirm="Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger"
                                            data-bs-toggle="tooltip" 
                                            title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Mostrando {{ $users->firstItem() }} a {{ $users->lastItem() }} de {{ $users->total() }} usuários
            </div>
            <div>
                {{ $users->links() }}
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
                    <div class="text-xs fw-bold text-primary">Total de Usuários</div>
                    <div class="h3 fw-bold">{{ $users->total() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-success shadow">
            <div class="card-body">
                <div class="text-center">
                    <div class="text-xs fw-bold text-success">Usuários Ativos</div>
                    <div class="h3 fw-bold">{{ $users->where('ativo', true)->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-info shadow">
            <div class="card-body">
                <div class="text-center">
                    <div class="text-xs fw-bold text-info">Administradores</div>
                    <div class="h3 fw-bold">{{ $users->where('role', 'admin')->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-warning shadow">
            <div class="card-body">
                <div class="text-center">
                    <div class="text-xs fw-bold text-warning">Comissão Eleitoral</div>
                    <div class="h3 fw-bold">{{ $users->where('role', 'comissao')->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Inicializar DataTable
   
    
    // Pesquisa personalizada
    $('#searchInput').on('keyup', function() {
        $('.datatable').DataTable().search($(this).val()).draw();
    });
    
    // Tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    background: var(--azul-claro);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
</style>
@endsection