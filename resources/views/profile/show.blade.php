@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-dark">
                <i class="fas fa-user-circle me-2"></i>Meu Perfil
            </h1>
            <div class="btn-group">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Editar Perfil
                </a>
                <a href="{{ route('profile.security') }}" class="btn btn-outline-primary">
                    <i class="fas fa-shield-alt me-2"></i>Segurança
                </a>
            </div>
        </div>
        <p class="text-muted">Gerencie suas informações pessoais e preferências</p>
    </div>
</div>

<div class="row">
    <!-- Informações do Usuário -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-body text-center">
                <!-- Foto do Perfil -->
                <div class="profile-photo mb-4">
                    @if(auth()->user()->foto)
                        <img src="{{ Storage::url(auth()->user()->foto) }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="rounded-circle"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="avatar-large">
                            <span class="initials">{{ substr(auth()->user()->name, 0, 2) }}</span>
                        </div>
                    @endif
                </div>
                
                <h4 class="fw-bold mb-1">{{ auth()->user()->name }}</h4>
                <p class="text-muted mb-3">{{ auth()->user()->email }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-4">
                    @switch(auth()->user()->role)
                        @case('admin')
                            <span class="badge bg-danger">Administrador</span>
                            @break
                        @case('comissao')
                            <span class="badge bg-warning">Comissão Eleitoral</span>
                            @break
                        @default
                            <span class="badge bg-info">Eleitor</span>
                    @endswitch
                    
                    @switch(auth()->user()->categoria)
                        @case('estudante')
                            <span class="badge bg-success">Estudante</span>
                            @break
                        @case('docente')
                            <span class="badge bg-primary">Docente</span>
                            @break
                        @default
                            <span class="badge bg-secondary">Técnico</span>
                    @endswitch
                </div>
                
                <div class="list-group list-group-flush text-start">
                    @if(auth()->user()->matricula)
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Matrícula</span>
                        <span class="fw-bold">{{ auth()->user()->matricula }}</span>
                    </div>
                    @endif
                    
                    @if(auth()->user()->telefone)
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Telefone</span>
                        <span class="fw-bold">{{ auth()->user()->telefone }}</span>
                    </div>
                    @endif
                    
                    @if(auth()->user()->curso)
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Curso/Depto</span>
                        <span class="fw-bold">{{ auth()->user()->curso }}</span>
                    </div>
                    @endif
                    
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Membro desde</span>
                        <span class="fw-bold">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Último acesso</span>
                        <span class="fw-bold">
                            @if(auth()->user()->ultimo_acesso)
                                {{ auth()->user()->ultimo_acesso->diffForHumans() }}
                            @else
                                Nunca
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Estatísticas Rápidas -->
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-chart-bar me-2"></i>Minhas Estatísticas
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="stat-number text-primary">{{ $estatisticas['total_votos'] }}</div>
                        <div class="stat-label">Votos Registrados</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-number text-success">{{ $estatisticas['eleicoes_participadas'] }}</div>
                        <div class="stat-label">Eleições Participadas</div>
                    </div>
                    <div class="col-6">
                        <div class="stat-number text-warning">{{ $estatisticas['candidaturas'] }}</div>
                        <div class="stat-label">Candidaturas</div>
                    </div>
                    <div class="col-6">
                        <div class="stat-number text-info">{{ $estatisticas['eleicoes_vencidas'] }}</div>
                        <div class="stat-label">Eleições Vencidas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Conteúdo Principal -->
    <div class="col-lg-8">
        <!-- Abas -->
        <div class="card shadow">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity">
                            <i class="fas fa-history me-2"></i>Atividade
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="votes-tab" data-bs-toggle="tab" data-bs-target="#votes">
                            <i class="fas fa-vote-yea me-2"></i>Meus Votos
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="candidacies-tab" data-bs-toggle="tab" data-bs-target="#candidacies">
                            <i class="fas fa-user-tie me-2"></i>Minhas Candidaturas
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences">
                            <i class="fas fa-cog me-2"></i>Preferências
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="profileTabsContent">
                    <!-- Atividade -->
                    <div class="tab-pane fade show active" id="activity" role="tabpanel">
                        <h6 class="fw-bold mb-3">Últimas Atividades</h6>
                        <div class="activity-timeline">
                            @php
                                $atividades = auth()->user()->logs()->latest()->take(10)->get();
                            @endphp
                            
                            @if($atividades->count() > 0)
                                @foreach($atividades as $atividade)
                                <div class="activity-item mb-3">
                                    <div class="d-flex">
                                        <div class="activity-icon me-3">
                                            @switch($atividade->acao)
                                                @case('login')
                                                    <i class="fas fa-sign-in-alt text-success"></i>
                                                    @break
                                                @case('logout')
                                                    <i class="fas fa-sign-out-alt text-danger"></i>
                                                    @break
                                                @case('registrar_voto')
                                                    <i class="fas fa-vote-yea text-primary"></i>
                                                    @break
                                                @case('alterar_senha')
                                                    <i class="fas fa-key text-warning"></i>
                                                    @break
                                                @default
                                                    <i class="fas fa-info-circle text-info"></i>
                                            @endswitch
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">{{ $atividade->descricao }}</div>
                                            <div class="small text-muted">
                                                {{ $atividade->created_at->format('d/m/Y H:i:s') }} • 
                                                IP: {{ $atividade->ip_address }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                                <div class="text-center mt-3">
                                    <a href="{{ route('profile.activity') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-list me-1"></i>Ver Todas as Atividades
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-history fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Nenhuma atividade registrada</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Meus Votos -->
                    <div class="tab-pane fade" id="votes" role="tabpanel">
                        <h6 class="fw-bold mb-3">Histórico de Votos</h6>
                        
                        @if($votos->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Eleição</th>
                                            <th>Cargo</th>
                                            <th>Candidato</th>
                                            <th>Data</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($votos as $voto)
                                        <tr>
                                            <td>{{ $voto->eleicao->titulo }}</td>
                                            <td>{{ $voto->eleicao->cargo->nome }}</td>
                                            <td>{{ $voto->candidato->user->name }}</td>
                                            <td>{{ $voto->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('votacao.comprovante', $voto) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-file-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Mostrando {{ $votos->firstItem() }} a {{ $votos->lastItem() }} de {{ $votos->total() }} votos
                                </div>
                                <div>
                                    {{ $votos->links() }}
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-vote-yea fa-2x text-muted mb-3"></i>
                                <p class="text-muted mb-3">Você ainda não registrou nenhum voto</p>
                                <a href="{{ route('votacao.index') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-right me-2"></i>Ir para Votação
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Minhas Candidaturas -->
                    <div class="tab-pane fade" id="candidacies" role="tabpanel">
                        <h6 class="fw-bold mb-3">Minhas Candidaturas</h6>
                        
                        @if($candidaturas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Eleição</th>
                                            <th>Cargo</th>
                                            <th>Número</th>
                                            <th>Status</th>
                                            <th>Votos</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($candidaturas as $candidatura)
                                        <tr>
                                            <td>{{ $candidatura->eleicao->titulo }}</td>
                                            <td>{{ $candidatura->eleicao->cargo->nome }}</td>
                                            <td>
                                                <span class="badge bg-dark">#{{ $candidatura->numero_candidato }}</span>
                                            </td>
                                            <td>
                                                @if($candidatura->aprovado)
                                                    <span class="badge bg-success">Aprovado</span>
                                                @else
                                                    <span class="badge bg-warning">Pendente</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $candidatura->votos()->count() }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="#" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Mostrando {{ $candidaturas->firstItem() }} a {{ $candidaturas->lastItem() }} de {{ $candidaturas->total() }} candidaturas
                                </div>
                                <div>
                                    {{ $candidaturas->links() }}
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-user-tie fa-2x text-muted mb-3"></i>
                                <p class="text-muted mb-3">Você ainda não se candidatou a nenhuma eleição</p>
                                <a href="{{ route('candidatos.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Nova Candidatura
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Preferências -->
                    <div class="tab-pane fade" id="preferences" role="tabpanel">
                        <h6 class="fw-bold mb-3">Configurações de Preferências</h6>
                        
                        <form method="POST" action="{{ route('profile.preferences.update') }}">
                            @csrf
                            @method('PATCH')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h6 class="fw-bold mb-3">Notificações</h6>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="notificacoes_email" 
                                               name="notificacoes_email"
                                               value="1"
                                               {{ auth()->user()->preferencias['notificacoes_email'] ?? true ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notificacoes_email">
                                            Notificações por Email
                                        </label>
                                        <div class="form-text">
                                            Receba notificações importantes por email
                                        </div>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="notificacoes_sistema" 
                                               name="notificacoes_sistema"
                                               value="1"
                                               {{ auth()->user()->preferencias['notificacoes_sistema'] ?? true ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notificacoes_sistema">
                                            Notificações no Sistema
                                        </label>
                                        <div class="form-text">
                                            Mostrar notificações dentro do sistema
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <h6 class="fw-bold mb-3">Aparência</h6>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="tema_escuro" 
                                               name="tema_escuro"
                                               value="1"
                                               {{ auth()->user()->preferencias['tema_escuro'] ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tema_escuro">
                                            Tema Escuro
                                        </label>
                                        <div class="form-text">
                                            Alternar entre tema claro e escuro
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="idioma" class="form-label">Idioma</label>
                                        <select class="form-select" id="idioma" name="idioma">
                                            <option value="pt_MZ" {{ (auth()->user()->preferencias['idioma'] ?? 'pt_MZ') == 'pt_MZ' ? 'selected' : '' }}>
                                                Português (Moçambique)
                                            </option>
                                            <option value="en" {{ (auth()->user()->preferencias['idioma'] ?? 'pt_MZ') == 'en' ? 'selected' : '' }}>
                                                English
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Salvar Preferências
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Ações da Conta -->
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-cog me-2"></i>Ações da Conta
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-grid">
                            <a href="{{ route('profile.export') }}" class="btn btn-outline-info">
                                <i class="fas fa-download me-2"></i>Exportar Meus Dados
                            </a>
                            <div class="form-text mt-1">
                                Baixe todos os seus dados em formato JSON
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="d-grid">
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteAccountModal">
                                <i class="fas fa-user-slash me-2"></i>Desativar Conta
                            </button>
                            <div class="form-text mt-1">
                                Desative sua conta permanentemente
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Desativar Conta -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Desativar Conta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.delete') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <h6 class="alert-heading">Atenção!</h6>
                        <p class="mb-0 small">
                            Esta ação é permanente e não pode ser desfeita. Todos os seus dados serão removidos.
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="passwordConfirm" class="form-label">Confirme sua senha *</label>
                        <input type="password" 
                               class="form-control" 
                               id="passwordConfirm" 
                               name="password"
                               required>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="confirmDelete" 
                               name="confirmacao"
                               value="1"
                               required>
                        <label class="form-check-label" for="confirmDelete">
                            Eu entendo que esta ação é permanente e não pode ser desfeita.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-user-slash me-2"></i>Desativar Minha Conta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.avatar-large {
    width: 150px;
    height: 150px;
    background: linear-gradient(135deg, var(--azul-escuro) 0%, var(--azul-claro) 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: bold;
    margin: 0 auto;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    line-height: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: var(--cinza-texto);
    margin-top: 0.5rem;
}

.activity-timeline {
    position: relative;
    padding-left: 30px;
}

.activity-timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--azul-claro);
    opacity: 0.3;
}

.activity-item {
    position: relative;
}

.activity-item::before {
    content: '';
    position: absolute;
    left: -30px;
    top: 8px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--azul-claro);
    border: 2px solid white;
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: rgba(49, 130, 206, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.form-check.form-switch .form-check-input {
    width: 3em;
    height: 1.5em;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Inicializar as abas
    const triggerTabList = document.querySelectorAll('#profileTabs button');
    triggerTabList.forEach(triggerEl => {
        const tabTrigger = new bootstrap.Tab(triggerEl);
        triggerEl.addEventListener('click', event => {
            event.preventDefault();
            tabTrigger.show();
        });
    });
    
    // Validação para desativar conta
    $('#deleteAccountModal form').submit(function(e) {
        const password = $('#passwordConfirm').val();
        const confirmed = $('#confirmDelete').is(':checked');
        
        if (!password) {
            e.preventDefault();
            alert('Por favor, insira sua senha para confirmar.');
            return false;
        }
        
        if (!confirmed) {
            e.preventDefault();
            alert('Por favor, confirme que entende as consequências.');
            return false;
        }
        
        if (!confirm('Tem certeza absoluta que deseja desativar sua conta? Esta ação é permanente!')) {
            e.preventDefault();
            return false;
        }
        
        return true;
    });
    
    // Aplicar tema escuro se configurado
    @if(auth()->user()->preferencias['tema_escuro'] ?? false)
    $('body').addClass('theme-dark');
    @endif
});
</script>
@endsection