@extends('layouts.app')

@section('title', 'Segurança da Conta')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Meu Perfil</a></li>
                <li class="breadcrumb-item active">Segurança</li>
            </ol>
        </nav>
        
        <h1 class="h3 mb-0 text-dark">
            <i class="fas fa-shield-alt me-2"></i>Segurança da Conta
        </h1>
        <p class="text-muted">Gerencie a segurança da sua conta</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Alterar Senha -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 fw-bold">
                    <i class="fas fa-key me-2"></i>Alterar Senha
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password.update') }}" class="needs-validation" novalidate>
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Senha Atual *</label>
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password" 
                               required>
                        <div class="invalid-feedback">
                            Por favor, insira sua senha atual.
                        </div>
                        @error('current_password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Nova Senha *</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required
                               pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$">
                        <div class="invalid-feedback">
                            A senha deve ter no mínimo 8 caracteres, incluindo letras maiúsculas, minúsculas, números e símbolos.
                        </div>
                        @error('password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <small>Dicas para uma senha segura:</small>
                            <ul class="mb-0 small">
                                <li>Mínimo de 8 caracteres</li>
                                <li>Letras maiúsculas e minúsculas</li>
                                <li>Pelo menos um número</li>
                                <li>Pelo menos um símbolo (@$!%*?&)</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirmar Nova Senha *</label>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>Atenção
                        </h6>
                        <p class="mb-0 small">
                            Ao alterar sua senha, todas as suas outras sessões serão desconectadas automaticamente.
                        </p>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Alterar Senha
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Sessões Ativas -->
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-desktop me-2"></i>Sessões Ativas
                </h6>
            </div>
            <div class="card-body">
                @if(count($sessoes) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Dispositivo</th>
                                    <th>Navegador</th>
                                    <th>IP</th>
                                    <th>Última Atividade</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessoes as $sessao)
                                <tr>
                                    <td>
                                        {{ $sessao['device'] }}
                                        @if($sessao['current'])
                                            <span class="badge bg-success">Atual</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit($sessao['browser'], 30) }}</small>
                                    </td>
                                    <td>
                                        <code>{{ $sessao['ip'] }}</code>
                                    </td>
                                    <td>
                                        @if($sessao['last_active'] instanceof \Carbon\Carbon)
                                            {{ $sessao['last_active']->diffForHumans() }}
                                        @else
                                            {{ $sessao['last_active'] }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($sessao['current'])
                                            <span class="badge bg-success">Ativa</span>
                                        @else
                                            <span class="badge bg-warning">Inativa</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="alert alert-warning mt-3">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-triangle me-2"></i>Importante
                        </h6>
                        <p class="mb-0 small">
                            Se você não reconhece alguma sessão ativa, altere sua senha imediatamente.
                        </p>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-shield fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Nenhuma sessão ativa encontrada</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Autenticação de Dois Fatores -->
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 fw-bold">
                    <i class="fas fa-mobile-alt me-2"></i>Autenticação em Dois Fatores
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="fas fa-shield-check fa-3x text-success mb-3"></i>
                    <h6 class="fw-bold">Proteção Extra</h6>
                    <p class="small text-muted">
                        Adicione uma camada extra de segurança à sua conta
                    </p>
                </div>
                
                <div class="d-grid">
                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#twoFactorModal">
                        <i class="fas fa-cog me-2"></i>Configurar 2FA
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Histórico de Logins -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-history me-2"></i>Últimos Logins
                </h6>
            </div>
            <div class="card-body">
                @php
                    $logins = auth()->user()->logs()
                                          ->where('acao', 'login')
                                          ->latest()
                                          ->take(5)
                                          ->get();
                @endphp
                
                @if($logins->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($logins as $login)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="fw-bold">
                                        <i class="fas fa-sign-in-alt text-success me-2"></i>
                                        Login realizado
                                    </div>
                                    <div class="small text-muted">
                                        {{ $login->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="small text-muted">
                                        {{ $login->ip_address }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('profile.activity') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-list me-1"></i>Ver Histórico Completo
                        </a>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-history fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Nenhum login registrado</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Dicas de Segurança -->
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 fw-bold">
                    <i class="fas fa-lightbulb me-2"></i>Melhores Práticas
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Use senhas diferentes para cada serviço</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Ative a autenticação em dois fatores</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Nunca use redes Wi-Fi públicas sem VPN</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Mantenha seu dispositivo atualizado</small>
                    </li>
                    <li>
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Desconecte-se ao usar dispositivos compartilhados</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Autenticação em Dois Fatores -->
<div class="modal fade" id="twoFactorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Autenticação em Dois Fatores</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                    <p class="mb-0">
                        Escaneie o código QR com seu aplicativo autenticador
                    </p>
                </div>
                
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="fas fa-info-circle me-2"></i>Como funciona
                    </h6>
                    <p class="mb-0 small">
                        1. Instale um aplicativo autenticador (Google Authenticator, Authy, etc.)<br>
                        2. Escaneie o código QR abaixo<br>
                        3. Digite o código de 6 dígitos gerado
                    </p>
                </div>
                
                <!-- Aqui iria o código QR gerado (implementação real) -->
                <div class="text-center my-4">
                    <div class="bg-light p-4 d-inline-block">
                        <i class="fas fa-qrcode fa-5x text-muted"></i>
                        <div class="small text-muted mt-2">Código QR seria gerado aqui</div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="verificationCode" class="form-label">Código de Verificação</label>
                    <input type="text" 
                           class="form-control text-center" 
                           id="verificationCode" 
                           placeholder="000000"
                           maxlength="6"
                           pattern="\d{6}">
                    <div class="form-text">
                        Digite o código de 6 dígitos do seu aplicativo
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-check me-2"></i>Verificar e Ativar
                </button>
            </div>
        </div>
    </div>
</div>

<style>

</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Validação do formulário de senha
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
    
    // Validação da força da senha
    $('#password').on('keyup', function() {
        const password = $(this).val();
        const feedback = $(this).next('.invalid-feedback');
        
        // Verificar requisitos
        const hasMinLength = password.length >= 8;
        const hasUpperCase = /[A-Z]/.test(password);
        const hasLowerCase = /[a-z]/.test(password);
        const hasNumbers = /\d/.test(password);
        const hasSpecial = /[@$!%*?&]/.test(password);
        
        // Atualizar mensagem de feedback
        let messages = [];
        if (!hasMinLength) messages.push('Mínimo de 8 caracteres');
        if (!hasUpperCase) messages.push('Letras maiúsculas');
        if (!hasLowerCase) messages.push('Letras minúsculas');
        if (!hasNumbers) messages.push('Números');
        if (!hasSpecial) messages.push('Símbolos (@$!%*?&)');
        
        if (messages.length > 0) {
            feedback.text('A senha deve ter: ' + messages.join(', '));
        }
    });
    
    // Validação da confirmação de senha
    $('#password_confirmation').on('keyup', function() {
        const password = $('#password').val();
        const confirm = $(this).val();
        
        if (password && confirm && password !== confirm) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    // Configurar código de verificação 2FA
    $('#verificationCode').inputmask('999999');
});
</script>
@endsection