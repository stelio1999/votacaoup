@extends('layouts.app')

@section('title', 'Criar Novo Cargo')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cargos.index') }}">Cargos</a></li>
                <li class="breadcrumb-item active">Criar Cargo</li>
            </ol>
        </nav>
        
        <h1 class="h3 mb-0 text-dark">
            <i class="fas fa-briefcase me-2"></i>Criar Novo Cargo
        </h1>
        <p class="text-muted">Adicione um novo cargo para eleição</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">Formulário de Criação</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('cargos.store') }}" class="needs-validation" novalidate>
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome" class="form-label">Nome do Cargo *</label>
                            <input type="text" 
                                   class="form-control @error('nome') is-invalid @enderror" 
                                   id="nome" 
                                   name="nome" 
                                   value="{{ old('nome') }}" 
                                   required
                                   placeholder="Ex: Representante dos Estudantes">
                            <div class="invalid-feedback">
                                Por favor, insira o nome do cargo.
                            </div>
                            @error('nome')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="categoria" class="form-label">Categoria *</label>
                            <select class="form-select @error('categoria') is-invalid @enderror" 
                                    id="categoria" 
                                    name="categoria" 
                                    required>
                                <option value="" selected disabled>Selecione uma categoria</option>
                                <option value="estudante" {{ old('categoria') == 'estudante' ? 'selected' : '' }}>
                                    Estudante
                                </option>
                                <option value="docente" {{ old('categoria') == 'docente' ? 'selected' : '' }}>
                                    Docente
                                </option>
                                <option value="tecnico_administrativo" {{ old('categoria') == 'tecnico_administrativo' ? 'selected' : '' }}>
                                    Técnico Administrativo
                                </option>
                            </select>
                            <div class="invalid-feedback">
                                Por favor, selecione uma categoria.
                            </div>
                            @error('categoria')
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
                                  placeholder="Descreva as funções e responsabilidades do cargo">{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mandato_meses" class="form-label">Duração do Mandato (meses) *</label>
                            <input type="number" 
                                   class="form-control @error('mandato_meses') is-invalid @enderror" 
                                   id="mandato_meses" 
                                   name="mandato_meses" 
                                   value="{{ old('mandato_meses', 24) }}" 
                                   required
                                   min="1"
                                   max="48">
                            <div class="form-text">
                                Duração do mandato em meses (1 a 48 meses)
                            </div>
                            <div class="invalid-feedback">
                                Por favor, insira uma duração válida.
                            </div>
                            @error('mandato_meses')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="ordem" class="form-label">Ordem de Exibição</label>
                            <input type="number" 
                                   class="form-control @error('ordem') is-invalid @enderror" 
                                   id="ordem" 
                                   name="ordem" 
                                   value="{{ old('ordem', 0) }}"
                                   min="0"
                                   max="999">
                            <div class="form-text">
                                Define a ordem de exibição (menor número aparece primeiro)
                            </div>
                            @error('ordem')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="responsabilidades" class="form-label">Responsabilidades</label>
                        <textarea class="form-control @error('responsabilidades') is-invalid @enderror" 
                                  id="responsabilidades" 
                                  name="responsabilidades" 
                                  rows="4"
                                  placeholder="Liste as principais responsabilidades do cargo">{{ old('responsabilidades') }}</textarea>
                        @error('responsabilidades')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="requisitos" class="form-label">Requisitos</label>
                        <textarea class="form-control @error('requisitos') is-invalid @enderror" 
                                  id="requisitos" 
                                  name="requisitos" 
                                  rows="4"
                                  placeholder="Liste os requisitos necessários para o cargo">{{ old('requisitos') }}</textarea>
                        @error('requisitos')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="beneficios" class="form-label">Benefícios</label>
                        <textarea class="form-control @error('beneficios') is-invalid @enderror" 
                                  id="beneficios" 
                                  name="beneficios" 
                                  rows="3"
                                  placeholder="Liste os benefícios do cargo">{{ old('beneficios') }}</textarea>
                        @error('beneficios')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="ativo" 
                                   name="ativo" 
                                   value="1" 
                                   {{ old('ativo', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="ativo">
                                Cargo ativo
                            </label>
                            <div class="form-text">
                                Cargos inativos não aparecem nas eleições
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('cargos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Criar Cargo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>Informações
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6 class="alert-heading">Sobre Categorias</h6>
                    <p class="small mb-2">
                        <strong>Estudante:</strong> Cargos para representação estudantil
                    </p>
                    <p class="small mb-2">
                        <strong>Docente:</strong> Cargos para corpo docente
                    </p>
                    <p class="small mb-0">
                        <strong>Técnico Administrativo:</strong> Cargos para funcionários técnicos
                    </p>
                </div>
                
                <div class="alert alert-warning">
                    <h6 class="alert-heading">Duração do Mandato</h6>
                    <p class="small mb-0">
                        A duração do mandato é em meses. Recomenda-se:
                        <br>• Estudantes: 12 meses
                        <br>• Docentes/Técnicos: 24 meses
                    </p>
                </div>
                
                <div class="alert alert-success">
                    <h6 class="alert-heading">Cargos Ativos</h6>
                    <p class="small mb-0">
                        Apenas cargos ativos aparecem como opções para novas eleições.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-history me-2"></i>Cargos Recentes
                </h6>
            </div>
            <div class="card-body">
                @php
                    $cargosRecentes = \App\Models\Cargo::latest()->take(5)->get();
                @endphp
                
                @if($cargosRecentes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($cargosRecentes as $cargo)
                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $cargo->nome }}</h6>
                                    <small class="text-muted">
                                        {{ $cargo->categoria_formatada }} • {{ $cargo->mandato_meses }} meses
                                    </small>
                                </div>
                                <span class="badge bg-{{ $cargo->ativo ? 'success' : 'danger' }}">
                                    {{ $cargo->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center mb-0">
                        <i class="fas fa-inbox fa-2x mb-2"></i><br>
                        Nenhum cargo criado ainda
                    </p>
                @endif
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
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Contador de caracteres para descrição
document.addEventListener('DOMContentLoaded', function() {
    const descricao = document.getElementById('descricao');
    const responsabilidades = document.getElementById('responsabilidades');
    const requisitos = document.getElementById('requisitos');
    const beneficios = document.getElementById('beneficios');
    
    function setupCounter(textarea, maxLength = 2000) {
        const counterId = textarea.id + '-counter';
        const counter = document.createElement('div');
        counter.className = 'form-text text-end small';
        counter.id = counterId;
        counter.textContent = `0/${maxLength} caracteres`;
        textarea.parentNode.insertBefore(counter, textarea.nextSibling);
        
        textarea.addEventListener('input', function() {
            const length = this.value.length;
            counter.textContent = `${length}/${maxLength} caracteres`;
            
            if (length > maxLength) {
                counter.classList.add('text-danger');
            } else {
                counter.classList.remove('text-danger');
            }
        });
        
        // Trigger initial count
        textarea.dispatchEvent(new Event('input'));
    }
    
    if (descricao) setupCounter(descricao, 500);
    if (responsabilidades) setupCounter(responsabilidades, 1000);
    if (requisitos) setupCounter(requisitos, 1000);
    if (beneficios) setupCounter(beneficios, 500);
});
</script>
@endsection