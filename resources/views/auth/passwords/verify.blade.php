@extends('layouts.app')

@section('title', 'Verificar Email')

@section('content')
<div class="auth-page">
    <div class="auth-card" style="max-width: 450px;">
        <div class="auth-header">
            <img src="{{ asset('images/logo-up.png') }}" alt="UP Maputo" class="auth-logo">
            <h3 class="mb-2 fw-bold">Verificação por SMS</h3>
            <p class="mb-0 opacity-75">Recupere sua palavra-passe por SMS</p>
        </div>
        
        <div class="auth-body">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif
            
            @if(session('verification_code'))
                <div class="dev-link mb-3">
                    <i class="fas fa-flask me-2"></i>
                    <strong>Código de Verificação (Desenvolvimento):</strong>
                    <div class="display-4 text-center mt-2 mb-2" style="letter-spacing: 5px;">
                        {{ session('verification_code') }}
                    </div>
                    <small class="d-block">Este código seria enviado por SMS em produção.</small>
                </div>
            @endif
            
            <div class="info-box mb-4">
                <i class="fas fa-mobile-alt fa-2x text-primary mb-2"></i>
                <h6 class="fw-bold">Como funciona?</h6>
                <p class="small text-muted mb-0">
                    Digite seu email institucional. Enviaremos um código de verificação 
                    para o número de telefone cadastrado no sistema.
                </p>
            </div>
            
            <form method="POST" action="{{ route('password.send-code') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="email" class="form-label fw-bold">Email Institucional</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="exemplo@up.ac.mz"
                               required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="fas fa-paper-plane me-2"></i>Enviar Código
                </button>
                
                <div class="text-center">
                    <a href="{{ route('password.request') }}" class="text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i>
                        Voltar para recuperação por email
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection