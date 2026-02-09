@extends('layouts.app')

@section('title', 'Comprovante de Voto')

@section('styles')
<style>
    .receipt-container {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .receipt-card {
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        padding: 2rem;
        background: white;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }
    
    .receipt-header {
        text-align: center;
        padding-bottom: 2rem;
        border-bottom: 2px dashed #e2e8f0;
        margin-bottom: 2rem;
    }
    
    .receipt-logo {
        max-width: 120px;
        margin-bottom: 1rem;
    }
    
    .receipt-number {
        background: var(--azul-escuro);
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: bold;
        display: inline-block;
        margin-bottom: 1rem;
    }
    
    .receipt-details {
        margin-bottom: 2rem;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f1f1;
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        font-weight: 600;
        color: var(--azul-escuro);
    }
    
    .detail-value {
        color: #333;
        text-align: right;
        max-width: 60%;
    }
    
    .hash-code {
        background: var(--cinza-claro);
        padding: 1rem;
        border-radius: 8px;
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
        word-break: break-all;
        margin: 1rem 0;
        border: 1px dashed #cbd5e0;
    }
    
    .verification-qr {
        text-align: center;
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 10px;
        margin: 2rem 0;
    }
    
    .qr-placeholder {
        width: 200px;
        height: 200px;
        background: white;
        border: 2px dashed #cbd5e0;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: var(--azul-claro);
        font-size: 3rem;
    }
    
    .actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 2rem;
    }
    
    .watermark {
        position: absolute;
        opacity: 0.03;
        font-size: 10rem;
        transform: rotate(-45deg);
        z-index: 0;
        pointer-events: none;
        color: var(--azul-escuro);
    }
    
    @media print {
        .no-print {
            display: none !important;
        }
        
        .receipt-card {
            border: none;
            box-shadow: none;
        }
        
        .actions {
            display: none;
        }
    }
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
    </div>
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