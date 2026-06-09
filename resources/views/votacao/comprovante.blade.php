@extends('layouts.app')

@section('title', 'Comprovante de Voto')

@section('styles')
<style>
    
</style>
@endsection

@section('content')
<div class="receipt-container">
    <div class="watermark">COMPROVANTE</div>
    
    <div class="receipt-card position-relative">
        <div class="receipt-header">
            <div class="receipt-number">
                COMPROVANTE #{{ str_pad($voto->id, 6, '0', STR_PAD_LEFT) }}
            </div>
            <h2 class="h4 mb-3 text-dark">Comprovante de Voto</h2>
            <p class="text-muted mb-0">
                Sistema de Votação Eletrónica - Universidade Pedagógica de Maputo
            </p>
        </div>
        
        <div class="receipt-details">
            <div class="detail-row">
                <div class="detail-label">Eleição:</div>
                <div class="detail-value">{{ $voto->eleicao->titulo }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Cargo:</div>
                <div class="detail-value">{{ $voto->eleicao->cargo->nome }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Candidato Votado:</div>
                <div class="detail-value">
                    <strong>{{ $voto->candidato->user->name }}</strong><br>
                    <small class="text-muted">Número: {{ $voto->candidato->numero_candidato }}</small>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Eleitor:</div>
                <div class="detail-value">
                    {{ auth()->user()->name }}<br>
                    <small class="text-muted">{{ auth()->user()->matricula ?? 'Sem matrícula' }}</small>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Data e Hora:</div>
                <div class="detail-value">{{ $voto->created_at->format('d/m/Y H:i:s') }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Endereço IP:</div>
                <div class="detail-value">{{ $voto->ip_address }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Status:</div>
                <div class="detail-value">
                    @if($voto->valido)
                        <span class="badge bg-success">VOTO VÁLIDO</span>
                    @else
                        <span class="badge bg-danger">VOTO NULO</span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="verification-qr">
            <div class="qr-placeholder">
                <i class="fas fa-qrcode"></i>
            </div>
            <p class="text-muted small mb-0">
                Código QR para verificação<br>
                <small>Escaneie para validar este comprovante</small>
            </p>
        </div>
        
        <div class="text-center">
            <h6 class="mb-3">Código de Verificação Único</h6>
            <div class="hash-code">{{ $voto->hash_voto }}</div>
            <p class="text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                Este código é único e pode ser usado para verificar a autenticidade do voto.
            </p>
        </div>
        
        <div class="mt-4 pt-4 border-top text-center">
            <p class="small text-muted mb-0">
                <i class="fas fa-shield-alt me-1"></i>
                Este sistema garante o anonimato do voto. O comprovante não revela a escolha do eleitor para terceiros.
            </p>
            <p class="small text-muted">
                Comprovante gerado em: {{ now()->format('d/m/Y H:i:s') }}
            </p>
        </div>
    </div>
    
    <div class="actions no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print me-2"></i>Imprimir Comprovante
        </button>
        
        <button id="downloadPDF" class="btn btn-success">
            <i class="fas fa-file-pdf me-2"></i>Download PDF
        </button>
        
        <button id="sendEmail" class="btn btn-info">
            <i class="fas fa-envelope me-2"></i>Enviar por Email
        </button>
        
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-home me-2"></i>Voltar ao Início
        </a>
    </div>
    <!--
    <div class="alert alert-info mt-4 no-print">
        <div class="d-flex">
            <div class="me-3">
                <i class="fas fa-lock fa-2x"></i>
            </div>
            <div>
                <h6 class="alert-heading">Segurança do Comprovante</h6>
                <p class="mb-0 small">
                    Este comprovante contém um código hash único gerado por criptografia SHA-256. 
                    Ele pode ser usado para verificar a integridade do seu voto sem revelar sua escolha.
                    Guarde este comprovante em local seguro.
                </p>
            </div>
        </div>
    </div>-->
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Download PDF
    $('#downloadPDF').click(function() {
        // Implementar geração de PDF
        alert('Funcionalidade de download PDF em desenvolvimento.');
    });
    
    // Enviar por email
    $('#sendEmail').click(function() {
        // Implementar envio por email
        alert('Funcionalidade de envio por email em desenvolvimento.');
    });
    
    // Copiar código hash para área de transferência
    $('.hash-code').click(function() {
        const text = $(this).text();
        navigator.clipboard.writeText(text).then(() => {
            const originalText = $(this).text();
            $(this).text('Código copiado!');
            setTimeout(() => {
                $(this).text(originalText);
            }, 2000);
        });
    });
    
    // Configurações de impressão
    window.onbeforeprint = function() {
        $('body').addClass('printing');
    };
    
    window.onafterprint = function() {
        $('body').removeClass('printing');
    };
});
</script>
@endsection