@extends('layouts.app')

@section('title', 'Entrar no Sistema')

@section('styles')
<style>
    .login-page {
        min-height: 100vh;
        background: linear-gradient(135deg, var(--azul-escuro) 0%, var(--azul-claro) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .login-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        width: 100%;
        max-width: 450px;
        overflow: hidden;
    }
    
    .login-header {
        background: var(--azul-escuro);
        color: white;
        padding: 2rem;
        text-align: center;
    }
    
    .login-body {
        padding: 2rem;
    }
    
    .login-logo {
        max-width: 120px;
        margin-bottom: 1rem;
    }
    
    .form-control {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
    }
    
    .form-control:focus {
        border-color: var(--azul-claro);
        box-shadow: 0 0 0 0.25rem rgba(49, 130, 206, 0.25);
    }
    
    .btn-login {
        background: var(--azul-claro);
        border: none;
        color: white;
        padding: 0.75rem;
        border-radius: 10px;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s ease;
    }
    
    .btn-login:hover {
        background: var(--azul-escuro);
        transform: translateY(-2px);
    }
    
    .login-footer {
        text-align: center;
        padding: 1rem;
        border-top: 1px solid #e2e8f0;
        color: #718096;
    }
    
    .floating-element {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }
</style>
@endsection

@section('content')
<div class="login-page">
    <!-- Elementos decorativos -->
    <div class="floating-element" style="width: 100px; height: 100px; top: 20%; left: 10%;"></div>
    <div class="floating-element" style="width: 150px; height: 150px; bottom: 20%; right: 10%;"></div>
    <div class="floating-element" style="width: 80px; height: 80px; top: 60%; left: 20%;"></div>
    
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('images/logo-up.png') }}" alt="UP Maputo" class="login-logo">
            <h3 class="mb-0">Sistema de Votação Eletrónica</h3>
            <p class="mb-0 opacity-75">Universidade Pedagógica de Maputo</p>
        </div>
        
        <div class="login-body">
            <h4 class="text-center mb-4">Entrar no Sistema</h4>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email Institucional</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email" 
                               autofocus
                               placeholder="seu.email@up.ac.mz">
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Palavra-passe</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="current-password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Lembrar-me
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-login mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i>Entrar
                </button>
                
                <div class="text-center">
                    <a href="#" class="text-decoration-none">
                        <small>Esqueceu a palavra-passe?</small>
                    </a>
                </div>
            </form>
        </div>
        
        <div class="login-footer">
            <small>© {{ date('Y') }} Universidade Pedagógica de Maputo. Todos os direitos reservados.</small>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Alternar visibilidade da palavra-passe
    $('#togglePassword').click(function() {
        const passwordInput = $('#password');
        const icon = $(this).find('i');
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    // Animar elementos flutuantes
    $('.floating-element').each(function(index) {
        $(this).css({
            'animation-delay': (index * 2) + 's',
            'animation-duration': (3 + index) + 's'
        });
    });
    
    // Efeito de foco no primeiro campo
    $('#email').focus();
});
</script>
@endsection