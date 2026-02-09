@extends('layouts.app')

@section('title', 'Configurações do Sistema')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0 text-dark">
            <i class="fas fa-cogs me-2"></i>Configurações do Sistema
        </h1>
        <p class="text-muted">Configure e monitore o sistema de votação</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100">
            <div class="card-body">
                <div class="text-center">
                    <i class="fas fa-server fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Sistema</h5>
                    <p class="card-text small text-muted">
                        Configurações gerais do sistema
                    </p>
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-1"></i>Configurar
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100">
            <div class="card-body">
                <div class="text-center">
                    <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Segurança</h5>
                    <p class="card-text small text-muted">
                        Configurações de segurança e acesso
                    </p>
                    <a href="#" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-lock me-1"></i>Configurar
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100">
            <div class="card-body">
                <div class="text-center">
                    <i class="fas fa-envelope fa-3x text-info mb-3"></i>
                    <h5 class="card-title">Email</h5>
                    <p class="card-text small text-muted">
                        Configurações de envio de email
                    </p>
                    <a href="#" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-at me-1"></i>Configurar
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100">
            <div class="card-body">
                <div class="text-center">
                    <i class="fas fa-bell fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Notificações</h5>
                    <p class="card-text small text-muted">
                        Configurações de notificações
                    </p>
                    <a href="#" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-comment-alt me-1"></i>Configurar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header">
        <h6 class="m-0 fw-bold text-primary">
            <i class="fas fa-info-circle me-2"></i>Informações do Sistema
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($configuracoes as $categoria => $dados)
            <div class="col-md-6 mb-4">
                <h6 class="fw-bold text-dark mb-3">{{ ucfirst($categoria) }}</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tbody>
                            @foreach($dados as $chave => $valor)
                            <tr>
                                <td class="fw-bold text-muted" style="width: 40%;">{{ ucfirst(str_replace('_', ' ', $chave)) }}</td>
                                <td>
                                    @if(is_array($valor))
                                        <span class="badge bg-info">{{ count($valor) }} itens</span>
                                    @elseif(is_bool($valor))
                                        @if($valor)
                                            <span class="badge bg-success">Sim</span>
                                        @else
                                            <span class="badge bg-danger">Não</span>
                                        @endif
                                    @else
                                        <span class="text-dark">{{ $valor }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-tools me-2"></i>Manutenção
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('configuracoes.limpar-cache') }}" 
                       class="btn btn-outline-primary"
                       onclick="return confirm('Tem certeza que deseja limpar o cache do sistema?')">
                        <i class="fas fa-broom me-2"></i>Limpar Cache do Sistema
                    </a>
                    
                    <a href="{{ route('configuracoes.backup') }}" 
                       class="btn btn-outline-success">
                        <i class="fas fa-database me-2"></i>Criar Backup do Banco de Dados
                    </a>
                    
                    <a href="{{ route('configuracoes.logs') }}" 
                       class="btn btn-outline-info">
                        <i class="fas fa-file-alt me-2"></i>Ver Logs do Sistema
                    </a>
                    
                    <a href="{{ route('configuracoes.sistema') }}" 
                       class="btn btn-outline-warning">
                        <i class="fas fa-terminal me-2"></i>Informações Técnicas
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-chart-line me-2"></i>Estatísticas Rápidas
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 fw-bold text-primary mb-1">
                                {{ \App\Models\User::count() }}
                            </div>
                            <div class="small text-muted">Usuários</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 fw-bold text-success mb-1">
                                {{ \App\Models\Eleicao::count() }}
                            </div>
                            <div class="small text-muted">Eleições</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h4 fw-bold text-info mb-1">
                                {{ \App\Models\Voto::count() }}
                            </div>
                            <div class="small text-muted">Votos</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            @php
                                $hoje = \App\Models\Voto::whereDate('created_at', today())->count();
                            @endphp
                            <div class="h4 fw-bold text-warning mb-1">
                                {{ $hoje }}
                            </div>
                            <div class="small text-muted">Votos Hoje</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-header bg-dark text-white">
        <h6 class="m-0 fw-bold">
            <i class="fas fa-exclamation-triangle me-2"></i>Ações Críticas
        </h6>
    </div>
    <div class="card-body">
        <div class="alert alert-danger">
            <h6 class="alert-heading">
                <i class="fas fa-skull-crossbones me-2"></i>Atenção!
            </h6>
            <p class="mb-2 small">
                As ações abaixo podem afetar significativamente o funcionamento do sistema. 
                Execute-as apenas se tiver certeza do que está fazendo.
            </p>
        </div>
        
        <div class="d-grid gap-2">
            <button class="btn btn-danger" 
                    onclick="return confirm('ATENÇÃO: Esta ação irá reiniciar todo o sistema. Tem certeza absoluta?')">
                <i class="fas fa-power-off me-2"></i>Reiniciar Sistema
            </button>
            
            <button class="btn btn-danger" 
                    onclick="return confirm('ATENÇÃO: Esta ação irá limpar todos os dados de teste. Esta ação não pode ser desfeita.')">
                <i class="fas fa-trash-alt me-2"></i>Limpar Dados de Teste
            </button>
            
            <button class="btn btn-danger" 
                    onclick="return confirm('ATENÇÃO: Esta ação irá desativar todos os usuários. Tem certeza absoluta?')">
                <i class="fas fa-user-slash me-2"></i>Desativar Todos os Usuários
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Adicionar confirmação para todas as ações críticas
    $('.btn-danger').click(function() {
        return confirm('Esta é uma ação crítica. Tem certeza que deseja continuar?');
    });
});
</script>
@endsection