@extends('layouts.app')

@section('title', 'Criar Novo Usuário')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Usuários</a></li>
                <li class="breadcrumb-item active">Criar Usuário</li>
            </ol>
        </nav>
        
        <h1 class="h3 mb-0 text-dark">
            <i class="fas fa-user-plus me-2"></i>Criar Novo Usuário
        </h1>
        <p class="text-muted">Adicione um novo usuário ao sistema de votação</p>
    </div>
</div>

<div class="card shadow">
    <div class="card-header">
        <h6 class="m-0 fw-bold text-primary">Formulário de Criação</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('usuarios.store') }}" class="needs-validation" novalidate>
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nome Completo *</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
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
                           value="{{ old('email') }}" 
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
                    <label for="password" class="form-label">Palavra-passe *</label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           required>
                    <div class="invalid-feedback">
                        Por favor, insira uma palavra-passe.
                    </div>
                    @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Palavra-passe *</label>
                    <input type="password" 
                           class="form-control" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="role" class="form-label">Papel no Sistema *</label>
                    <select class="form-select @error('role') is-invalid @enderror" 
                            id="role" 
                            name="role" 
                            required>
                        <option value="" selected disabled>Selecione um papel</option>
                        @foreach($roles as $role)
                        <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
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
                        <option value="" selected disabled>Selecione uma categoria</option>
                        @foreach($categorias as $categoria)
                        <option value="{{ $categoria }}" {{ old('categoria') == $categoria ? 'selected' : '' }}>
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
                           value="{{ old('matricula') }}">
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
                           value="{{ old('curso') }}">
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
                           value="{{ old('telefone') }}">
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
                           {{ old('ativo', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="ativo">
                        Usuário ativo
                    </label>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Criar Usuário
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mt-4">
    <div class="card-header">
        <h6 class="m-0 fw-bold text-primary">
            <i class="fas fa-info-circle me-2"></i>Informações sobre Papéis
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="fas fa-user-shield me-2"></i>Administrador
                    </h6>
                    <p class="mb-0 small">
                        Acesso total ao sistema. Pode gerenciar todos os usuários, eleições, candidatos e configurações.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-warning">
                    <h6 class="alert-heading">
                        <i class="fas fa-clipboard-check me-2"></i>Comissão Eleitoral
                    </h6>
                    <p class="mb-0 small">
                        Pode gerenciar eleições, candidatos, visualizar resultados e gerar relatórios.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-success">
                    <h6 class="alert-heading">
                        <i class="fas fa-user me-2"></i>Eleitor
                    </h6>
                    <p class="mb-0 small">
                        Pode apenas votar nas eleições em que está habilitado e visualizar resultados públicos.
                    </p>
                </div>
            </div>
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

// Máscara para telefone
$(document).ready(function() {
    $('#telefone').inputmask('(99) 99999-9999');
    
    // Validação de senha
    $('#password, #password_confirmation').on('keyup', function() {
        var password = $('#password').val();
        var confirm = $('#password_confirmation').val();
        
        if (password !== confirm) {
            $('#password_confirmation').addClass('is-invalid');
        } else {
            $('#password_confirmation').removeClass('is-invalid');
        }
    });
});
</script>
@endsection