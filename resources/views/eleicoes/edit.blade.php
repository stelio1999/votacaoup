@extends('layouts.app')

@section('title', 'Editar Eleição')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('eleicoes.index') }}">Eleições</a></li>
                <li class="breadcrumb-item active">Editar Eleição</li>
            </ol>
        </nav>
        
        <h1 class="h3 mb-0 text-dark">
            <i class="fas fa-edit me-2"></i>Editar Eleição
        </h1>
        <p class="text-muted">Atualize os dados da eleição</p>
    </div>
</div>

<div class="card shadow">
    <div class="card-header">
        <h6 class="m-0 fw-bold text-primary">Formulário de Edição</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('eleicoes.update', $eleicao) }}" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="titulo" class="form-label">Título da Eleição *</label>
                    <input type="text" 
                           class="form-control @error('titulo') is-invalid @enderror" 
                           id="titulo" 
                           name="titulo" 
                           value="{{ old('titulo', $eleicao->titulo) }}" 
                           required>
                    <div class="invalid-feedback">
                        Por favor, insira o título da eleição.
                    </div>
                    @error('titulo')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="cargo_id" class="form-label">Cargo *</label>
                    <select class="form-select @error('cargo_id') is-invalid @enderror" 
                            id="cargo_id" 
                            name="cargo_id" 
                            required>
                        <option value="" disabled>Selecione um cargo</option>
                        @foreach($cargos as $cargo)
                        <option value="{{ $cargo->id }}" {{ old('cargo_id', $eleicao->cargo_id) == $cargo->id ? 'selected' : '' }}>
                            {{ $cargo->nome }} ({{ $cargo->categoria }})
                        </option>
                        @endforeach
                    </select>
                    @error('cargo_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                          id="descricao" 
                          name="descricao" 
                          rows="3">{{ old('descricao', $eleicao->descricao) }}</textarea>
                @error('descricao')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="data_inicio" class="form-label">Data de Início *</label>
                    <input type="datetime-local" 
                           class="form-control @error('data_inicio') is-invalid @enderror" 
                           id="data_inicio" 
                           name="data_inicio" 
                           value="{{ old('data_inicio', $eleicao->data_inicio->format('Y-m-d\TH:i')) }}" 
                           required>
                    @error('data_inicio')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="data_fim" class="form-label">Data de Fim *</label>
                    <input type="datetime-local" 
                           class="form-control @error('data_fim') is-invalid @enderror" 
                           id="data_fim" 
                           name="data_fim" 
                           value="{{ old('data_fim', $eleicao->data_fim->format('Y-m-d\TH:i')) }}" 
                           required>
                    @error('data_fim')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Status *</label>
                <select class="form-select @error('status') is-invalid @enderror" 
                        id="status" 
                        name="status" 
                        required>
                    <option value="agendada" {{ old('status', $eleicao->status) == 'agendada' ? 'selected' : '' }}>Agendada</option>
                    <option value="ativa" {{ old('status', $eleicao->status) == 'ativa' ? 'selected' : '' }}>Ativa</option>
                    <option value="concluida" {{ old('status', $eleicao->status) == 'concluida' ? 'selected' : '' }}>Concluída</option>
                    <option value="cancelada" {{ old('status', $eleicao->status) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
                @error('status')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="observacoes" class="form-label">Observações</label>
                <textarea class="form-control @error('observacoes') is-invalid @enderror" 
                          id="observacoes" 
                          name="observacoes" 
                          rows="2">{{ old('observacoes', $eleicao->observacoes) }}</textarea>
                @error('observacoes')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('eleicoes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Atualizar Eleição
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mt-4">
    <div class="card-header">
        <h6 class="m-0 fw-bold text-primary">
            <i class="fas fa-info-circle me-2"></i>Informações Importantes
        </h6>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <h6 class="alert-heading">
                <i class="fas fa-exclamation-triangle me-2"></i>Atenção
            </h6>
            <ul class="mb-0 small">
                <li>A alteração do status da eleição pode afetar o processo eleitoral.</li>
                <li>Eleições "Ativas" permitem que os eleitores votem.</li>
                <li>Eleições "Concluídas" não podem mais receber votos.</li>
                <li>Ajuste as datas com cuidado para não interferir em votos já registrados.</li>
            </ul>
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
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Validação de datas
$(document).ready(function() {
    $('#data_inicio, #data_fim').change(function() {
        var inicio = new Date($('#data_inicio').val());
        var fim = new Date($('#data_fim').val());
        
        if (inicio && fim && inicio >= fim) {
            $('#data_fim').addClass('is-invalid');
            $('<div class="invalid-feedback">A data de fim deve ser posterior à data de início.</div>').insertAfter('#data_fim');
        } else {
            $('#data_fim').removeClass('is-invalid');
            $('#data_fim').next('.invalid-feedback').remove();
        }
    });
});
</script>
@endsection