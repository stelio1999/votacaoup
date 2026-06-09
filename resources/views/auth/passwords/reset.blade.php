@extends('layouts.app')
@section('full-width')

@section('title', 'Redefinir Palavra-passe')

@section('styles')
<style>
   
</style>
@endsection

@section('content')
<div class="reset-page">
    <!-- Elementos decorativos -->
    <div class="floating-icon" style="top: 10%; left: 5%;">
        <i class="fas fa-key"></i>
    </div>
    <div class="floating-icon" style="bottom: 15%; right: 8%;">
        <i class="fas fa-lock"></i>
    </div>
    
    <div class="reset-card">
        <div class="auth-header">
            <img src="{{ asset('images/logo-up.png') }}" alt="UP Maputo" class="auth-logo">
            <h3 class="mb-2 fw-bold">Redefinir Palavra-passe</h3>
            <p class="mb-0 opacity-75">Crie uma nova palavra-passe segura</p>
        </div>
        
        <div class="auth-body">
            <!-- Timer de expiração -->
            <div class="timer-box mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">
                        <i class="fas fa-hourglass-half me-1"></i>Link expira em:
                    </span>
                    <span class="timer-number" id="timer">60:00</span>
                </div>
            </div>
            
            <form method="POST" action="{{ route('password.update') }}" class="needs-validation" novalidate>
                @csrf
                
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="mb-3">
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
                               value="{{ $email ?? old('email') }}" 
                               readonly
                               required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">
                        <i class="fas fa-lock me-2"></i>Nova Palavra-passe
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-key"></i>
                        </span>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    
                    <!-- Força da senha -->
                    <div class="password-strength mt-2" id="passwordStrength"></div>
                    
                    <!-- Requisitos de senha -->
                    <div class="mt-2">
                        <div class="password-requirement" id="reqLength">
                            <i class="fas fa-circle me-1"></i> Mínimo 8 caracteres
                        </div>
                        <div class="password-requirement" id="reqLower">
                            <i class="fas fa-circle me-1"></i> Pelo menos 1 letra minúscula
                        </div>
                        <div class="password-requirement" id="reqUpper">
                            <i class="fas fa-circle me-1"></i> Pelo menos 1 letra maiúscula
                        </div>
                        <div class="password-requirement" id="reqNumber">
                            <i class="fas fa-circle me-1"></i> Pelo menos 1 número
                        </div>
                        <div class="password-requirement" id="reqSpecial">
                            <i class="fas fa-circle me-1"></i> Pelo menos 1 caractere especial (!@#$%^&*)
                        </div>
                    </div>
                    
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-bold">
                        <i class="fas fa-lock me-2"></i>Confirmar Palavra-passe
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                            <i class="fas fa-eye" id="toggleConfirmIcon"></i>
                        </button>
                    </div>
                    <div id="passwordMatchMessage" class="mt-2 small"></div>
                </div>
                
                <button type="submit" class="btn btn-primary mb-3" id="submitBtn" disabled>
                    <i class="fas fa-save me-2"></i>Redefinir Palavra-passe
                </button>
                
                <hr class="my-4">
                
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i>
                        Voltar para o login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    // ===== TIMER DE EXPIRAÇÃO =====
    let timeLeft = 60 * 60; // 60 minutos em segundos
    const timerElement = $('#timer');

    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.text(`${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            timerElement.text('00:00').css('color', '#dc3545');
            $('.timer-box').addClass('border-danger');
            alert('O link de redefinição expirou. Por favor, solicite um novo.');
            window.location.href = '{{ route("password.request") }}';
        }

        timeLeft--;
    }

    const timerInterval = setInterval(updateTimer, 1000);
    updateTimer();

    // ===== FUNÇÕES DE SENHA =====
    function checkPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[$@#&!]/.test(password)) strength++;
        return strength;
    }

    function updateFormState() {
        const password = $('#password').val();
        const confirm = $('#password_confirmation').val();
        const matchMessage = $('#passwordMatchMessage');
        const strengthBar = $('#passwordStrength');

        // ===== ATUALIZA REQUISITOS DE SENHA =====
        $('#reqLength').toggleClass('requirement-met', password.length >= 8).toggleClass('requirement-not-met', password.length < 8);
        $('#reqLower').toggleClass('requirement-met', /[a-z]/.test(password)).toggleClass('requirement-not-met', !/[a-z]/.test(password));
        $('#reqUpper').toggleClass('requirement-met', /[A-Z]/.test(password)).toggleClass('requirement-not-met', !/[A-Z]/.test(password));
        $('#reqNumber').toggleClass('requirement-met', /[0-9]/.test(password)).toggleClass('requirement-not-met', !/[0-9]/.test(password));
        $('#reqSpecial').toggleClass('requirement-met', /[$@#&!]/.test(password)).toggleClass('requirement-not-met', !/[$@#&!]/.test(password));

        // ===== ATUALIZA BARRA DE FORÇA =====
        const strength = checkPasswordStrength(password);
        strengthBar.removeClass('strength-weak strength-fair strength-good strength-strong');
        if (strength <= 2) strengthBar.addClass('strength-weak');
        else if (strength === 3) strengthBar.addClass('strength-fair');
        else if (strength === 4) strengthBar.addClass('strength-good');
        else if (strength >= 5) strengthBar.addClass('strength-strong');

        // ===== VERIFICA CORRESPONDÊNCIA =====
        if (confirm.length === 0) {
            matchMessage.html('').removeClass('text-success text-danger');
        } else if (password === confirm) {
            matchMessage.html('<i class="fas fa-check-circle me-1"></i> As palavras-passe coincidem').addClass('text-success').removeClass('text-danger');
        } else {
            matchMessage.html('<i class="fas fa-exclamation-circle me-1"></i> As palavras-passe não coincidem').addClass('text-danger').removeClass('text-success');
        }

        // ===== HABILITA BOTÃO =====
        const allRequirementsMet = strength === 5; // Todos requisitos atendidos
        const passwordsMatch = password === confirm && confirm.length > 0;
        $('#submitBtn').prop('disabled', !(allRequirementsMet && passwordsMatch));
    }

    // ===== EVENTOS DE INPUT =====
    $('#password, #password_confirmation').on('input', updateFormState);
});

// ===== FUNÇÃO PARA TOGGLE DE SENHA =====
function togglePassword(inputId) {
    const input = $('#' + inputId);
    const icon = input.next().find('i');
    if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        input.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
}
</script>
@endsection

@endsection