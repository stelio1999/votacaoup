@extends('layouts.app')

@section('title', 'Criar Nova Eleição')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('eleicoes.index') }}">Eleições</a></li>
                <li class="breadcrumb-item active">Criar Eleição</li>
            </ol>
        </nav>
        
        <h1 class="h3 mb-0 text-dark">
            <i class="fas fa-vote-yea me-2"></i>Criar Nova Eleição
        </h1>
        <p class="text-muted">Configure uma nova eleição para a universidade</p>
    </div>
</div>

<div class="card shadow">
    <div class="card-header">
        <h6 class="m-0 fw-bold text-primary">Formulário de Criação de Eleição</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('eleicoes.store') }}" class="needs-validation" novalidate>
            @csrf
            
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="titulo" class="form-label">Título da Eleição *</label>
                    <input type="text" 
                           class="form-control @error('titulo') is-invalid @enderror" 
                           id="titulo" 
                           name="titulo" 
                           value="{{ old('titulo') }}" 
                           required
                           placeholder="Ex: Eleição para Representante dos Estudantes 2025">
                    <div class="invalid-feedback">
                        Por favor, insira o título da eleição.
                    </div>
                    @error('titulo')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="cargo_id" class="form-label">Cargo *</label>
                    <select class="form-select @error('cargo_id') is-invalid @enderror" 
                            id="cargo_id" 
                            name="cargo_id" 
                            required>
                        <option value="" selected disabled>Selecione um cargo</option>
                        @foreach($cargos as $cargo)
                        <option value="{{ $cargo->id }}" {{ old('cargo_id') == $cargo->id ? 'selected' : '' }}>
                            {{ $cargo->nome }} ({{ $cargo->categoria }})
                        </option>
                        @endforeach
                    </select>
                    @error('cargo_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="data_inicio" class="form-label">Data e Hora de Início *</label>
                    <input type="datetime-local" 
                           class="form-control @error('data_inicio') is-invalid @enderror" 
                           id="data_inicio" 
                           name="data_inicio" 
                           value="{{ old('data_inicio') }}" 
                           required>
                    <div class="invalid-feedback">
                        Por favor, selecione a data e hora de início.
                    </div>
                    @error('data_inicio')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="data_fim" class="form-label">Data e Hora de Término *</label>
                    <input type="datetime-local" 
                           class="form-control @error('data_fim') is-invalid @enderror" 
                           id="data_fim" 
                           name="data_fim" 
                           value="{{ old('data_fim') }}" 
                           required>
                    <div class="invalid-feedback">
                        Por favor, selecione a data e hora de término.
                    </div>
                    @error('data_fim')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                          id="descricao" 
                          name="descricao" 
                          rows="3"
                          placeholder="Descreva o propósito desta eleição...">{{ old('descricao') }}</textarea>
                @error('descricao')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="observacoes" class="form-label">Observações Internas</label>
                <textarea class="form-control @error('observacoes') is-invalid @enderror" 
                          id="observacoes" 
                          name="observacoes" 
                          rows="2"
                          placeholder="Observações para a comissão eleitoral...">{{ old('observacoes') }}</textarea>
                <small class="text-muted">Apenas visível para administradores e comissão eleitoral</small>
                @error('observacoes')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="fas fa-info-circle me-2"></i>Informações Importantes
                </h6>
                <ul class="mb-0 small">
                    <li>A eleição será criada com status "Agendada"</li>
                    <li>Será necessário cadastrar candidatos antes de iniciar a eleição</li>
                    <li>A eleição só poderá ser iniciada após a data de início</li>
                    <li>Após o término, os resultados serão calculados automaticamente</li>
                </ul>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('eleicoes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Criar Eleição
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-calendar-check me-2"></i>Próximas Etapas
                </h6>
            </div>
            <div class="card-body">
                <ol class="mb-0">
                    <li class="mb-2">
                        <strong>Cadastrar Candidatos</strong>
                        <div class="small text-muted">Adicione os candidatos à eleição criada</div>
                    </li>
                    <li class="mb-2">
                        <strong>Revisar e Aprovar Candidaturas</strong>
                        <div class="small text-muted">Verifique e aprove as candidaturas</div>
                    </li>
                    <li class="mb-2">
                        <strong>Iniciar Eleição</strong>
                        <div class="small text-muted">Ative a eleição na data programada</div>
                    </li>
                    <li>
                        <strong>Monitorar Resultados</strong>
                        <div class="small text-muted">Acompanhe os resultados em tempo real</div>
                    </li>
                </ol>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-clock me-2"></i>Recomendações de Duração
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-bold">Eleições de Representantes Estudantis</h6>
                    <p class="small text-muted mb-2">Recomendado: 24-48 horas</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Eleições de Coordenadores de Curso</h6>
                    <p class="small text-muted mb-2">Recomendado: 72 horas</p>
                </div>
                <div>
                    <h6 class="fw-bold">Eleições para Órgãos Colegiais</h6>
                    <p class="small text-muted mb-0">Recomendado: 5-7 dias</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Validação do formulário
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                var dataInicio = new Date(document.getElementById('data_inicio').value);
                var dataFim = new Date(document.getElementById('data_fim').value);
                var agora = new Date();
                
                if (dataInicio <= agora) {
                    event.preventDefault();
                    alert('A data de início deve ser futura.');
                    return false;
                }
                
                if (dataFim <= dataInicio) {
                    event.preventDefault();
                    alert('A data de término deve ser posterior à data de início.');
                    return false;
                }
                
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

$(document).ready(function() {
    // Configurar datas mínimas
    var agora = new Date();
    var amanha = new Date(agora);
    amanha.setDate(agora.getDate() + 1);
    
    // Formatar para input datetime-local
    var formatarData = function(data) {
        return data.toISOString().slice(0, 16);
    };
    
    // Definir valores padrão
    $('#data_inicio').attr('min', formatarData(amanha));
    $('#data_fim').attr('min', formatarData(amanha));
    
    // Quando data_inicio mudar, atualizar min de data_fim
    $('#data_inicio').change(function() {
        $('#data_fim').attr('min', $(this).val());
        
        // Se data_fim for anterior à nova data_inicio, limpar
        if ($('#data_fim').val() && $('#data_fim').val() < $(this).val()) {
            $('#data_fim').val('');
        }
    });
});
</script>
@endsection