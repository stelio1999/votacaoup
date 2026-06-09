@extends('layouts.app')

@section('title', 'Recuperar Palavra-passe')

@section('styles')
<style>
    
</style>
@endsection

@section('content')
<div class="auth-page">
    <!-- Ícones flutuantes decorativos -->
    <div class="floating-icon" style="top: 10%; left: 5%;">
        <i class="fas fa-shield-alt"></i>
    </div>
    <div class="floating-icon" style="bottom: 15%; right: 8%;">
        <i class="fas fa-vote-yea"></i>
    </div>
    <div class="floating-icon" style="top: 30%; right: 15%;">
        <i class="fas fa-lock"></i>
    </div>
    <div class="floating-icon" style="bottom: 25%; left: 10%;">
        <i class="fas fa-key"></i>
    </div>
    
    <div class="auth-card">
        <div class="auth-header">
            <img src="{{ asset('images/logo-up.png') }}" alt="UP Maputo" class="auth-logo">
            <h3 class="mb-2 fw-bold">Recuperar Palavra-passe</h3>
            <p class="mb-0 opacity-75">Sistema de Votação Eletrónica - UP Maputo</p>
        </div>
        
        <div class="auth-body">
            <!-- Mensagem de sucesso -->
            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <!-- Link de desenvolvimento (ambiente local) -->
            @if(session('reset_link'))
                <div class="dev-link">
                    <i class="fas fa-flask me-2"></i>
                    <strong>Ambiente de Desenvolvimento</strong><br>
                    <small class="d-block mt-2 mb-2">
                        Link de redefinição gerado:
                    </small>
                    <a href="{{ session('reset_link') }}" class="btn btn-sm btn-light" target="_blank">
                        <i class="fas fa-link me-1"></i> Clique aqui para redefinir
                    </a>
                    <div class="mt-2 small">
                        <strong>Token:</strong> {{ session('reset_token') }}<br>
                        <strong>Email:</strong> {{ session('reset_email') }}
                    </div>
                </div>
            @endif
            
            <!-- Caixa de informação -->
            <div class="info-box">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle fa-2x text-primary"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fw-bold text-primary mb-1">Como funciona?</h6>
                        <p class="small text-muted mb-0">
                            Digite o seu email institucional (@up.ac.mz). 
            Enviaremos um link seguro para redefinir a sua palavra-passe.
                            O link é válido por 60 minutos.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Formulário principal -->
            <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
                @csrf
                
                <div class="mb-4">
                    <label for="email" class="form-label fw-bold">
                        <i class="fas fa-envelope me-2"></i>Email Institucional
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user-graduate"></i>
                        </span>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="exemplo@up.ac.mz"
                               required 
                               autocomplete="email" 
                               autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                    <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>
                        Use o email institucional fornecido pela universidade.
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary mb-3" id="submitBtn">
                    <span id="submitText">
                        <i class="fas fa-paper-plane me-2"></i>Enviar Link de Recuperação
                    </span>
                    <span id="submitSpinner" style="display: none;">
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                        Enviando...
                    </span>
                </button>
                
                <div class="text-center mb-3">
                    <span class="text-muted">ou</span>
                </div>
                
                <!-- Método alternativo -->
                <div class="alternative-method" onclick="window.location.href='{{ route('password.verify') }}'">
                    <i class="fas fa-mobile-alt fa-2x text-primary mb-2"></i>
                    <h6 class="fw-bold mb-1">Não recebeu o email?</h6>
                    <p class="small text-muted mb-0">
                        Tente recuperar com código de verificação por SMS
                    </p>
                </div>
                
                <hr class="my-4">
                
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i>
                        Voltar para o login
                    </a>
                </div>
            </form>
        </div>
        
        <div class="auth-footer text-center py-3 bg-light">
            <small class="text-muted">
                <i class="fas fa-shield-alt me-1"></i>
                Sua segurança é nossa prioridade
            </small>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Validação do formulário
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        // Mostrar spinner no botão
                        $('#submitText').hide();
                        $('#submitSpinner').show();
                        $('#submitBtn').prop('disabled', true);
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
    
    // Autopreenchimento do domínio @up.ac.mz
    $('#email').on('blur', function() {
        let email = $(this).val();
        if (email && !email.includes('@')) {
            $(this).val(email + '@up.ac.mz');
        }
    });
    
    // Animar ícones flutuantes
    $('.floating-icon').each(function(index) {
        $(this).css({
            'animation-delay': (index * 0.5) + 's',
            'animation-duration': (5 + index) + 's'
        });
    });
    
    // Contador de caracteres para o email
    $('#email').on('input', function() {
        let length = $(this).val().length;
        if (length > 0) {
            $('.form-text').html(`<i class="fas fa-check-circle me-1 text-success"></i> Email inserido (${length} caracteres)`);
        } else {
            $('.form-text').html(`<i class="fas fa-info-circle me-1"></i> Use o email institucional fornecido pela universidade.`);
        }
    });
    
    // Efeito de hover no botão
    $('#submitBtn').hover(
        function() { $(this).css('transform', 'translateY(-2px)'); },
        function() { $(this).css('transform', 'translateY(0)'); }
    );
});
</script>
@endsection