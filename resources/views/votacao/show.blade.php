@extends('layouts.app')

@section('title', 'Votação - ' . $eleicao->titulo)

@section('styles')
<style>
</style>
@endsection

@section('content')
<div class="voting-container">
    <!-- Cabeçalho da Votação -->
    <div class="text-center mb-4">
        <h1 class="h2 mb-3 text-dark">{{ $eleicao->titulo }}</h1>
        <p class="text-muted mb-4">
            <i class="fas fa-briefcase me-2"></i>{{ $eleicao->cargo->nome }}
            • 
            <i class="fas fa-clock me-2"></i>Tempo restante: <span id="countdown">{{ $eleicao->tempo_restante }}</span>
        </p>
        
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Instruções:</strong> Selecione um candidato abaixo e confirme seu voto. Esta ação não pode ser desfeita.
        </div>
    </div>
    
    <!-- Passos da Votação -->
    <div class="voting-steps">
        <div class="step-indicator">
            <div class="step-circle active">1</div>
            <div>
                <strong>Selecione um Candidato</strong>
                <div class="small text-muted">Escolha entre os candidatos abaixo</div>
            </div>
        </div>
        <div class="step-indicator">
            <div class="step-circle">2</div>
            <div>
                <strong>Confirme sua Escolha</strong>
                <div class="small text-muted">Revise e confirme seu voto</div>
            </div>
        </div>
        <div class="step-indicator">
            <div class="step-circle">3</div>
            <div>
                <strong>Voto Registrado</strong>
                <div class="small text-muted">Receba o comprovante de votação</div>
            </div>
        </div>
    </div>
    
    <!-- Lista de Candidatos -->
    <h4 class="mb-4 text-dark">Candidatos</h4>
    
    @if($candidatos->isEmpty())
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-user-slash fa-3x text-muted mb-4"></i>
                <h4 class="text-muted">Nenhum Candidato Disponível</h4>
                <p class="text-muted mb-0">
                    Não há candidatos aprovados para esta eleição no momento.
                </p>
            </div>
        </div>
    @else
        <form id="votingForm" method="POST" action="{{ route('votacao.votar', $eleicao) }}">
            @csrf
            
            <div class="row">
                @foreach($candidatos as $candidato)
                <div class="col-md-6 mb-4">
                    <div class="candidate-card candidate-select" 
                         data-candidate-id="{{ $candidato->id }}"
                         data-candidate-name="{{ $candidato->user->name }}">
                        <div class="position-relative">
                            <div class="candidate-number">#{{ $candidato->numero_candidato }}</div>
                            
                            @if($candidato->foto)
                                <img src="{{ Storage::url($candidato->foto) }}" 
                                     alt="{{ $candidato->user->name }}" 
                                     class="candidate-photo">
                            @else
                                <div class="candidate-photo d-flex align-items-center justify-content-center bg-light">
                                    <i class="fas fa-user fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="text-center">
                            <h5 class="fw-bold mb-2">{{ $candidato->user->name }}</h5>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-graduation-cap me-1"></i>
                                {{ $candidato->user->curso ?? 'Curso não informado' }}
                            </p>
                            
                            @if($candidato->proposta)
                            <button type="button" 
                                    class="btn btn-sm btn-outline-info mb-3"
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#proposta{{ $candidato->id }}">
                                <i class="fas fa-file-alt me-1"></i>Ver Proposta
                            </button>
                            
                            <div class="collapse" id="proposta{{ $candidato->id }}">
                                <div class="proposal-content">
                                    {{ $candidato->proposta }}
                                </div>
                            </div>
                            @endif
                            
                            <div class="form-check">
                                <input class="form-check-input visually-hidden" 
                                       type="radio" 
                                       name="candidato_id" 
                                       id="candidato{{ $candidato->id }}" 
                                       value="{{ $candidato->id }}"
                                       required>
                                <label class="form-check-label visually-hidden" for="candidato{{ $candidato->id }}">
                                    Selecionar {{ $candidato->user->name }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Campo oculto para validação -->
            <input type="hidden" name="candidato_id" id="selectedCandidate" required>
            
            <!-- Campo de Confirmação -->
            <div class="card shadow mb-4" id="confirmationCard" style="display: none;">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-check-circle me-2"></i>Confirmação do Voto
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Atenção:</strong> Após confirmar, não será possível alterar seu voto.
                    </div>
                    
                    <div class="text-center mb-4">
                        <h5 id="selectedCandidateName" class="mb-3"></h5>
                        <div class="d-flex justify-content-center align-items-center">
                            @if(isset($candidato->foto))
                                <img src="{{ Storage::url($candidato->foto) }}" 
                                     alt="Candidato selecionado" 
                                     class="rounded-circle me-3"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="avatar-circle me-3">
                                    <span class="initials" id="candidateInitials"></span>
                                </div>
                            @endif
                            <div class="text-start">
                                <div class="fw-bold" id="confirmationCandidateName"></div>
                                <div class="small text-muted" id="confirmationCandidateNumber"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input @error('confirmacao') is-invalid @enderror" 
                                   type="checkbox" 
                                   name="confirmacao" 
                                   id="confirmacao" 
                                   value="1" 
                                   required>
                            <label class="form-check-label" for="confirmacao">
                                <strong>Confirmo que desejo votar neste candidato.</strong><br>
                                <span class="small text-muted">
                                    Declaro que esta é minha escolha final e não serei capaz de alterá-la posteriormente.
                                </span>
                            </label>
                            @error('confirmacao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-check-circle me-2"></i>Confirmar Voto
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Botões de Ação -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('votacao.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
                
                <button type="button" 
                        class="btn btn-primary" 
                        id="confirmSelection"
                        style="display: none;"
                        data-bs-toggle="modal" 
                        data-bs-target="#confirmationModal">
                    <i class="fas fa-arrow-right me-2"></i>Confirmar Seleção
                </button>
            </div>
        </form>
    @endif
</div>

<!-- Modal de Confirmação -->
<div class="modal fade confirmation-modal" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Seleção</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-question-circle fa-4x text-warning mb-4"></i>
                <h4 class="mb-3">Tem certeza da sua escolha?</h4>
                <p class="text-muted mb-4">
                    Você selecionou: <strong id="modalCandidateName"></strong><br>
                    Número: <span id="modalCandidateNumber" class="badge bg-dark"></span>
                </p>
                <p class="small text-muted">
                    Após confirmar, você será redirecionado para a página de confirmação final.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="finalConfirmation">
                    <i class="fas fa-check me-2"></i>Sim, Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Sucesso -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-check-circle fa-5x text-success"></i>
                </div>
                <h3 class="mb-3">Voto Registrado!</h3>
                <p class="text-muted mb-4">
                    Seu voto foi registrado com sucesso. Você será redirecionado para o comprovante.
                </p>
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let selectedCandidate = null;
    
    // Seleção de candidato
    $('.candidate-select').click(function() {
        // Remover seleção anterior
        $('.candidate-select').removeClass('selected');
        $('.candidate-select').find('.form-check-input').prop('checked', false);
        
        // Adicionar seleção atual
        $(this).addClass('selected');
        $(this).find('.form-check-input').prop('checked', true);
        
        // Obter dados do candidato
        selectedCandidate = {
            id: $(this).data('candidate-id'),
            name: $(this).data('candidate-name'),
            number: $(this).find('.candidate-number').text().replace('#', '')
        };
        
        // Preencher dados na confirmação
        $('#selectedCandidate').val(selectedCandidate.id);
        $('#selectedCandidateName').text('Você selecionou: ' + selectedCandidate.name);
        $('#confirmationCandidateName').text(selectedCandidate.name);
        $('#confirmationCandidateNumber').text('Número: ' + selectedCandidate.number);
        
        // Calcular iniciais para avatar
        const initials = selectedCandidate.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        $('#candidateInitials').text(initials);
        
        // Mostrar botão de confirmação
        $('#confirmSelection').show();
        $('#confirmationCard').hide();
    });
    
    // Modal de confirmação
    $('#confirmSelection').click(function() {
        if (!selectedCandidate) {
            alert('Por favor, selecione um candidato primeiro.');
            return;
        }
        
        $('#modalCandidateName').text(selectedCandidate.name);
        $('#modalCandidateNumber').text('#' + selectedCandidate.number);
    });
    
    // Confirmação final
    $('#finalConfirmation').click(function() {
        $('#confirmationModal').modal('hide');
        $('#confirmationCard').slideDown('slow');
        
        // Atualizar passos
        $('.step-indicator:nth-child(1) .step-circle').removeClass('active').addClass('completed');
        $('.step-indicator:nth-child(2) .step-circle').addClass('active');
    });
    
    // Contador regressivo
    function updateCountdown() {
        const endTime = new Date('{{ $eleicao->data_fim }}').getTime();
        const now = new Date().getTime();
        const distance = endTime - now;
        
        if (distance < 0) {
            $('#countdown').text('Eleição encerrada');
            $('.candidate-select').off('click');
            $('#confirmSelection').prop('disabled', true);
            return;
        }
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        let countdownText = '';
        if (days > 0) countdownText += `${days}d `;
        if (hours > 0) countdownText += `${hours}h `;
        if (minutes > 0) countdownText += `${minutes}m `;
        countdownText += `${seconds}s`;
        
        $('#countdown').text(countdownText);
    }
    
    // Atualizar contador a cada segundo
    updateCountdown();
    setInterval(updateCountdown, 1000);
    
    // Validação do formulário
    $('#votingForm').submit(function(e) {
        if (!selectedCandidate) {
            e.preventDefault();
            alert('Por favor, selecione um candidato.');
            return false;
        }
        
        if (!$('#confirmacao').is(':checked')) {
            e.preventDefault();
            alert('Por favor, confirme que deseja votar neste candidato.');
            return false;
        }
        
        // Mostrar modal de sucesso
        $('#successModal').modal('show');
        
        // Redirecionar após 3 segundos
        setTimeout(() => {
            window.location.href = "{{ route('votacao.comprovante', ':voto') }}".replace(':voto', '1');
        }, 3000);
        
        return true;
    });
    
    // Prevenir duplo clique no formulário
    let formSubmitted = false;
    $('#votingForm').on('submit', function() {
        if (formSubmitted) {
            return false;
        }
        formSubmitted = true;
        return true;
    });
});
</script>
@endsection