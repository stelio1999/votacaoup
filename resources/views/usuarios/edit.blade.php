@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Usuários</a></li>
                <li class="breadcrumb-item active">Editar Usuário</li>
            </ol>
        </nav>
        
        <h1 class="h3 mb-0 text-dark">
            <i class="fas fa-user-edit me-2"></i>Editar Usuário
        </h1>
        <p class="text-muted">Atualize as informações do usuário</p>
    </div>
</div>

<div class="card shadow">
    <div class="card-header">
        <h6 class="m-0 fw-bold text-primary">Formulário de Edição</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('usuarios.update', $user) }}" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nome Completo *</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           required>
                    <div class="invalid-feedback">
                        Por favor, insira o nome do usuário.
                    </div>
                    @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email Institucional *</label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}" 
                           required>
                    <div class="invalid-feedback">
                        Por favor, insira um email válido.
                    </div>
                    @error('email')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Nova Palavra-passe</label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password">
                    <small class="text-muted">Deixe em branco para manter a palavra-passe atual</small>
                    @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Nova Palavra-passe</label>
                    <input type="password" 
                           class="form-control" 
                           id="password_confirmation" 
                           name="password_confirmation">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="role" class="form-label">Papel no Sistema *</label>
                    <select class="form-select @error('role') is-invalid @enderror" 
                            id="role" 
                            name="role" 
                            required>
                        <option value="" disabled>Selecione um papel</option>
                        @foreach($roles as $role)
                        <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                            @switch($role)
                                @case('admin')
                                    Administrador
                                    @break
                                @case('comissao')
                                    Comissão Eleitoral
                                    @break
                                @default
                                    Eleitor
                            @endswitch
                        </option>
                        @endforeach
                    </select>
                    @error('role')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="categoria" class="form-label">Categoria *</label>
                    <select class="form-select @error('categoria') is-invalid @enderror" 
                            id="categoria" 
                            name="categoria" 
                            required>
                        <option value="" disabled>Selecione uma categoria</option>
                        @foreach($categorias as $categoria)
                        <option value="{{ $categoria }}" {{ old('categoria', $user->categoria) == $categoria ? 'selected' : '' }}>
                            @switch($categoria)
                                @case('estudante')
                                    Estudante
                                    @break
                                @case('docente')
                                    Docente
                                    @break
                                @default
                                    Técnico Administrativo
                            @endswitch
                        </option>
                        @endforeach
                    </select>
                    @error('categoria')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="matricula" class="form-label">Matrícula/Número</label>
                    <input type="text" 
                           class="form-control @error('matricula') is-invalid @enderror" 
                           id="matricula" 
                           name="matricula" 
                           value="{{ old('matricula', $user->matricula) }}">
                    @error('matricula')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="curso" class="form-label">Curso/Departamento</label>
                    <input type="text" 
                           class="form-control @error('curso') is-invalid @enderror" 
                           id="curso" 
                           name="curso" 
                           value="{{ old('curso', $user->curso) }}">
                    @error('curso')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" 
                           class="form-control @error('telefone') is-invalid @enderror" 
                           id="telefone" 
                           name="telefone" 
                           value="{{ old('telefone', $user->telefone) }}">
                    @error('telefone')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" 
                           type="checkbox" 
                           id="ativo" 
                           name="ativo" 
                           value="1" 
                           {{ old('ativo', $user->ativo) ? 'checked' : '' }}>
                    <label class="form-check-label" for="ativo">
                        Usuário ativo
                    </label>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
                <div class="d-flex gap-2">
                    <a href="{{ route('usuarios.show', $user) }}" class="btn btn-info">
                        <i class="fas fa-eye me-2"></i>Ver Detalhes
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Atualizar Usuário
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mt-4">
    <div class="card-header bg-warning">
        <h6 class="m-0 fw-bold text-white">
            <i class="fas fa-exclamation-triangle me-2"></i>Informações Importantes
        </h6>
    </div>
    <div class="card-body">
        <div class="alert alert-warning mb-0">
            <h6 class="alert-heading">Atenção!</h6>
            <p class="mb-2 small">
                Ao alterar o papel de um usuário, você está modificando suas permissões no sistema:
            </p>
            <ul class="small mb-0">
                <li><strong>Administrador:</strong> Acesso completo a todas as funcionalidades</li>
                <li><strong>Comissão Eleitoral:</strong> Pode gerenciar eleições e resultados</li>
                <li><strong>Eleitor:</strong> Apenas pode votar e ver resultados públicos</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Validação do formulário
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

$(document).ready(function() {
    // Máscara para telefone
    $('#telefone').inputmask('(99) 99999-9999');
    
    // Validação de senha
    $('#password, #password_confirmation').on('keyup', function() {
        var password = $('#password').val();
        var confirm = $('#password_confirmation').val();
        
        if (password && confirm && password !== confirm) {
            $('#password_confirmation').addClass('is-invalid');
        } else {
            $('#password_confirmation').removeClass('is-invalid');
        }
    });
});
</script>
@endsection