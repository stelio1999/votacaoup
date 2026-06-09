@extends('layouts.app')

@section('title', 'Verificar Código')

@section('styles')
<style>
    
</style>
@endsection

@section('content')
<div class="auth-page">
    <div class="auth-card" style="max-width: 500px;">
        <div class="auth-header">
            <img src="{{ asset('images/logo-up.png') }}" alt="UP Maputo" class="auth-logo">
            <h3 class="mb-2 fw-bold">Código de Verificação</h3>
            <p class="mb-0 opacity-75">Digite o código enviado para seu telefone</p>
        </div>
        
        <div class="auth-body">
            <div class="text-center mb-4">
                <div class="display-6 mb-2">
                    <i class="fas fa-shield-alt text-primary"></i>
                </div>
                <p class="text-muted mb-1">
                    Enviamos um código de 6 dígitos para:
                </p>
                <h6 class="fw-bold">
                    {{ substr(session('reset_email'), 0, 3) }}****{{ substr(session('reset_email'), strpos(session('reset_email'), '@')) }}
                </h6>
            </div>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('password.verify-code') }}" id="codeForm">
                @csrf
                
                <div class="d-flex justify-content-center mb-4">
                    <input type="text" class="code-input" name="code[]" maxlength="1" pattern="[0-9]" inputmode="numeric" autofocus>
                    <input type="text" class="code-input" name="code[]" maxlength="1" pattern="[0-9]" inputmode="numeric">
                    <input type="text" class="code-input" name="code[]" maxlength="1" pattern="[0-9]" inputmode="numeric">
                    <input type="text" class="code-input" name="code[]" maxlength="1" pattern="[0-9]" inputmode="numeric">
                    <input type="text" class="code-input" name="code[]" maxlength="1" pattern="[0-9]" inputmode="numeric">
                    <input type="text" class="code-input" name="code[]" maxlength="1" pattern="[0-9]" inputmode="numeric">
                </div>
                
                <input type="hidden" name="code" id="fullCode">
                
                <button type="submit" class="btn btn-primary w-100 mb-3" id="verifyBtn" disabled>
                    <i class="fas fa-check-circle me-2"></i>Verificar Código
                </button>
                
                <div class="text-center">
                    <p class="text-muted mb-2">
                        Não recebeu o código?
                        <a href="#" class="resend-link" id="resendCode">
                            Reenviar código
                        </a>
                    </p>
                    <div id="timerDisplay" class="small text-muted"></div>
                </div>
                
                <hr class="my-4">
                
                <div class="text-center">
                    <a href="{{ route('password.request') }}" class="text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i>
                        Voltar
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
    // Gerenciar campos de código
    const codeInputs = $('.code-input');
    let resendTimer = 60;
    let resendInterval;
    
    function checkAllFields() {
        let allFilled = true;
        codeInputs.each(function() {
            if ($(this).val() === '') {
                allFilled = false;
                return false;
            }
        });
        
        $('#verifyBtn').prop('disabled', !allFilled);
        
        if (allFilled) {
            let code = '';
            codeInputs.each(function() {
                code += $(this).val();
            });
            $('#fullCode').val(code);
        }
    }
    
    // Avançar para próximo campo
    codeInputs.on('input', function() {
        let maxLength = parseInt($(this).attr('maxlength'));
        let currentLength = $(this).val().length;
        
        if (currentLength >= maxLength) {
            let next = $(this).next('.code-input');
            if (next.length) {
                next.focus();
            }
        }
        
        // Apenas números
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
        
        checkAllFields();
    });
    
    // Mover para campo anterior com backspace
    codeInputs.on('keydown', function(e) {
        if (e.key === 'Backspace' && $(this).val() === '') {
            let prev = $(this).prev('.code-input');
            if (prev.length) {
                prev.focus();
            }
        }
    });
    
    // Colar código completo
    codeInputs.on('paste', function(e) {
        e.preventDefault();
        let pasteData = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
        let codeDigits = pasteData.replace(/[^0-9]/g, '').split('');
        
        codeInputs.each(function(index) {
            if (codeDigits[index]) {
                $(this).val(codeDigits[index]);
            }
        });
        
        checkAllFields();
    });
    
    // Timer para reenvio
    function startResendTimer() {
        $('#resendCode').addClass('disabled');
        resendTimer = 60;
        
        resendInterval = setInterval(function() {
            resendTimer--;
            $('#timerDisplay').text(`Aguarde ${resendTimer} segundos para reenviar`);
            
            if (resendTimer <= 0) {
                clearInterval(resendInterval);
                $('#resendCode').removeClass('disabled');
                $('#timerDisplay').text('');
            }
        }, 1000);
    }
    
    startResendTimer();
    
    // Reenviar código
    $('#resendCode').click(function(e) {
        e.preventDefault();
        
        if ($(this).hasClass('disabled')) {
            return false;
        }
        
        $.ajax({
            url: '{{ route("password.send-code") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                email: '{{ session("reset_email") }}'
            },
            success: function() {
                startResendTimer();
                alert('Novo código enviado com sucesso!');
            },
            error: function() {
                alert('Erro ao reenviar código. Tente novamente.');
            }
        });
    });
    
    // Submeter formulário
    $('#codeForm').submit(function(e) {
        let code = '';
        codeInputs.each(function() {
            code += $(this).val();
        });
        
        if (code.length !== 6) {
            e.preventDefault();
            alert('Por favor, insira o código completo de 6 dígitos.');
            return false;
        }
    });
});
</script>
@endsection