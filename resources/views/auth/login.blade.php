@extends('layouts.app')
@section('full-width')

@section('title', 'Entrar no Sistema')

@section('content')
<div class="login-page">
    <!-- Elementos decorativos -->
    <div class="floating-element" style="width: 100px; height: 100px; top: 20%; left: 10%;"></div>
    <div class="floating-element" style="width: 150px; height: 150px; bottom: 20%; right: 10%;"></div>
    <div class="floating-element" style="width: 80px; height: 80px; top: 60%; left: 20%;"></div>
    
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('images/logo-up.png') }}" alt="UP Maputo" class="login-logo">
           <!-- <h3-- class="mb-0">Sistema de Votação Eletrónica</h3-->
            <p class="mb-0 opacity-75">Universidade Pedagógica de Maputo</p>
        </div>
        
        <div class="login-body">
            <!-- <h4-- class="text-center mb-4">Entrar no Sistema</h4-->
            
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
                    <label for="email" class="form-label">Email </label>
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
                    <a href="{{ route('password.request') }}" class="text-decoration-none">
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
@endsection