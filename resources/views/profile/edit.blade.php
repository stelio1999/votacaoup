@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Meu Perfil</a></li>
                <li class="breadcrumb-item active">Editar Perfil</li>
            </ol>
        </nav>
        
        <h1 class="h3 mb-0 text-dark">
            <i class="fas fa-user-edit me-2"></i>Editar Perfil
        </h1>
        <p class="text-muted">Atualize suas informações pessoais</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">Informações Pessoais</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    @method('PATCH')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nome Completo *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', auth()->user()->name) }}" 
                                   required>
                            <div class="invalid-feedback">
                                Por favor, insira seu nome.
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
                                   value="{{ old('email', auth()->user()->email) }}" 
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
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" 
                                   class="form-control @error('telefone') is-invalid @enderror" 
                                   id="telefone" 
                                   name="telefone" 
                                   value="{{ old('telefone', auth()->user()->telefone) }}">
                            @error('telefone')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="foto" class="form-label">Foto de Perfil</label>
                            <input type="file" 
                                   class="form-control @error('foto') is-invalid @enderror" 
                                   id="foto" 
                                   name="foto"
                                   accept="image/*">
                            <div class="form-text">
                                Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB
                            </div>
                            @error('foto')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Nova Senha</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password">
                            <div class="form-text">
                                Deixe em branco para manter a senha atual
                            </div>
                            @error('password')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Informações Adicionais</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-id-card me-2"></i>Papel no Sistema
                                        </h6>
                                        <p class="card-text mb-1">
                                            @switch(auth()->user()->role)
                                                @case('admin')
                                                    <span class="badge bg-danger">Administrador</span>
                                                    <div class="small text-muted mt-1">
                                                        Acesso total ao sistema
                                                    </div>
                                                    @break
                                                @case('comissao')
                                                    <span class="badge bg-warning">Comissão Eleitoral</span>
                                                    <div class="small text-muted mt-1">
                                                        Gerenciamento de eleições e resultados
                                                    </div>
                                                    @break
                                                @default
                                                    <span class="badge bg-info">Eleitor</span>
                                                    <div class="small text-muted mt-1">
                                                        Participação em eleições
                                                    </div>
                                            @endswitch
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-users me-2"></i>Categoria
                                        </h6>
                                        <p class="card-text mb-1">
                                            @switch(auth()->user()->categoria)
                                                @case('estudante')
                                                    <span class="badge bg-success">Estudante</span>
                                                    <div class="small text-muted mt-1">
                                                        {{ auth()->user()->matricula ? 'Matrícula: ' . auth()->user()->matricula : 'Sem matrícula registrada' }}
                                                    </div>
                                                    @break
                                                @case('docente')
                                                    <span class="badge bg-primary">Docente</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">Técnico Administrativo</span>
                                            @endswitch
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Prévia da Foto -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">Prévia da Foto</h6>
            </div>
            <div class="card-body text-center">
                <div id="fotoPreview" class="mb-3">
                    @if(auth()->user()->foto)
                        <img src="{{ Storage::url(auth()->user()->foto) }}" 
                             id="previewImage" 
                             alt="Foto atual"
                             class="rounded-circle"
                             style="width: 200px; height: 200px; object-fit: cover;">
                    @else
                        <div id="previewInitials" class="avatar-preview">
                            <span class="initials">{{ substr(auth()->user()->name, 0, 2) }}</span>
                        </div>
                    @endif
                </div>
                <div class="small text-muted">
                    Sua foto será exibida em todo o sistema
                </div>
            </div>
        </div>
        
        <!-- Dicas de Segurança -->
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 fw-bold">
                    <i class="fas fa-shield-alt me-2"></i>Dicas de Segurança
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Use uma senha forte com letras, números e símbolos</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Nunca compartilhe sua senha com ninguém</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Altere sua senha regularmente</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Use uma foto profissional para seu perfil</small>
                    </li>
                    <li>
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Mantenha suas informações de contato atualizadas</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-preview {
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, var(--azul-escuro) 0%, var(--azul-claro) 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    font-weight: bold;
    margin: 0 auto;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Pré-visualização da foto
    $('#foto').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Esconder as iniciais
                $('#previewInitials').hide();
                
                // Criar ou atualizar a imagem
                let img = $('#previewImage');
                if (img.length === 0) {
                    img = $('<img>', {
                        id: 'previewImage',
                        class: 'rounded-circle',
                        style: 'width: 200px; height: 200px; object-fit: cover;'
                    });
                    $('#fotoPreview').append(img);
                }
                
                img.attr('src', e.target.result);
                img.show();
            }
            
            reader.readAsDataURL(file);
        }
    });
    
    // Máscara para telefone
    $('#telefone').inputmask('(99) 99999-9999');
    
    // Validação da senha
    $('#password, #password_confirmation').on('keyup', function() {
        const password = $('#password').val();
        const confirm = $('#password_confirmation').val();
        
        if (password && confirm && password !== confirm) {
            $('#password_confirmation').addClass('is-invalid');
        } else {
            $('#password_confirmation').removeClass('is-invalid');
        }
    });
    
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
});
</script>
@endsection