@extends('layouts.app')

@section('title', 'Editar Cargo: ' . $cargo->nome)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cargos.index') }}">Cargos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cargos.show', $cargo) }}">{{ $cargo->nome }}</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-dark">
                <i class="fas fa-edit me-2"></i>Editar Cargo
            </h1>
            <a href="{{ route('cargos.show', $cargo) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Voltar
            </a>
        </div>
        <p class="text-muted">Atualize as informações do cargo</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">Formulário de Edição</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('cargos.update', $cargo) }}" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome" class="form-label">Nome do Cargo *</label>
                            <input type="text" 
                                   class="form-control @error('nome') is-invalid @enderror" 
                                   id="nome" 
                                   name="nome" 
                                   value="{{ old('nome', $cargo->nome) }}" 
                                   required
                                   maxlength="255">
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
                                <option value="" disabled>Selecione uma categoria</option>
                                @foreach($categorias as $categoria)
                                <option value="{{ $categoria }}" 
                                    {{ old('categoria', $cargo->categoria) == $categoria ? 'selected' : '' }}>
                                    @switch($categoria)
                                        @case('estudante')
                                            Estudante
                                            @break
                                        @case('docente')
                                            Docente
                                            @break
                                        @default
                                            Técnico Administrativo
                                    @endswitch
                                </option>
                                @endforeach
                            </select>
                            @error('categoria')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mandato_meses" class="form-label">Duração do Mandato (meses) *</label>
                            <input type="number" 
                                   class="form-control @error('mandato_meses') is-invalid @enderror" 
                                   id="mandato_meses" 
                                   name="mandato_meses" 
                                   value="{{ old('mandato_meses', $cargo->mandato_meses) }}" 
                                   required
                                   min="1"
                                   max="48">
                            <div class="invalid-feedback">
                                Por favor, insira a duração do mandato em meses (1-48).
                            </div>
                            @error('mandato_meses')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-block">&nbsp;</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="ativo" 
                                       name="ativo" 
                                       value="1" 
                                       {{ old('ativo', $cargo->ativo) ? 'checked' : '' }}>
                                <label class="form-check-label" for="ativo">
                                    Cargo ativo
                                </label>
                            </div>
                            <small class="text-muted">
                                Cargos inativos não podem ter novas eleições criadas.
                            </small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                  id="descricao" 
                                  name="descricao" 
                                  rows="4">{{ old('descricao', $cargo->descricao) }}</textarea>
                        <div class="form-text">
                            Descreva as responsabilidades e atribuições deste cargo.
                        </div>
                        @error('descricao')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Atualizar Cargo
                        </button>
                        <a href="{{ route('cargos.show', $cargo) }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-history me-2"></i>Histórico de Alterações
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Campo</th>
                                <th>Antes</th>
                                <th>Depois</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $cargo->created_at->format('d/m/Y H:i') }}</td>
                                <td>Criação</td>
                                <td>-</td>
                                <td>Cargo criado</td>
                            </tr>
                            <tr>
                                <td>{{ $cargo->updated_at->format('d/m/Y H:i') }}</td>
                                <td>Última atualização</td>
                                <td>-</td>
                                <td>Informações atualizadas</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>Informações Atuais
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Nome Atual</label>
                    <p class="mb-0">{{ $cargo->nome }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Categoria Atual</label>
                    <p class="mb-0">
                        @switch($cargo->categoria)
                            @case('estudante')
                                <span class="badge bg-success">Estudante</span>
                                @break
                            @case('docente')
                                <span class="badge bg-primary">Docente</span>
                                @break
                            @default
                                <span class="badge bg-secondary">Técnico Administrativo</span>
                        @endswitch
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Mandato Atual</label>
                    <p class="mb-0">{{ $cargo->mandato_meses }} meses</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Status Atual</label>
                    <p class="mb-0">
                        @if($cargo->ativo)
                            <span class="badge bg-success">Ativo</span>
                        @else
                            <span class="badge bg-danger">Inativo</span>
                        @endif
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Eleições Ativas</label>
                    <p class="mb-0">
                        {{ $cargo->eleicoes()->where('status', 'ativa')->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-exclamation-triangle me-2"></i>Orientações
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <h6 class="alert-heading">
                        <i class="fas fa-lightbulb me-2"></i>Importante
                    </h6>
                    <ul class="mb-0 small">
                        <li>Alterar a categoria pode afetar eleições existentes</li>
                        <li>Cargos inativos não aparecem para criação de novas eleições</li>
                        <li>A duração do mandato deve ser realista e condizente com a função</li>
                        <li>Mantenha a descrição clara e objetiva</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-trash-alt me-2"></i>Excluir Cargo
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    <h6 class="alert-heading">
                        <i class="fas fa-exclamation-circle me-2"></i>Atenção
                    </h6>
                    <p class="mb-2 small">
                        Esta ação não pode ser desfeita. Ao excluir o cargo:
                    </p>
                    <ul class="mb-0 small">
                        <li>Todas as eleições associadas serão afetadas</li>
                        <li>Candidaturas relacionadas serão removidas</li>
                        <li>Esta ação requer confirmação adicional</li>
                    </ul>
                </div>
                
                <button type="button" 
                        class="btn btn-danger w-100"
                        data-bs-toggle="modal" 
                        data-bs-target="#excluirModal">
                    <i class="fas fa-trash me-2"></i>Excluir Cargo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="excluirModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h6 class="alert-heading">
                        <i class="fas fa-exclamation-triangle me-2"></i>ATENÇÃO
                    </h6>
                    <p class="mb-0">
                        Você está prestes a excluir o cargo <strong>"{{ $cargo->nome }}"</strong>.
                        Esta ação não pode ser desfeita.
                    </p>
                </div>
                
                @if($cargo->eleicoes()->exists())
                <div class="alert alert-warning">
                    <h6 class="alert-heading">
                        <i class="fas fa-info-circle me-2"></i>Eleições Encontradas
                    </h6>
                    <p class="mb-0">
                        Este cargo está associado a {{ $cargo->eleicoes()->count() }} eleição(ões).
                        A exclusão do cargo pode afetar essas eleições.
                    </p>
                </div>
                @endif
                
                <p>Para confirmar, digite <strong>"EXCLUIR"</strong> no campo abaixo:</p>
                <input type="text" class="form-control" id="confirmacaoExclusao" 
                       placeholder="Digite EXCLUIR para confirmar">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('cargos.destroy', $cargo) }}" method="POST" id="formExcluir">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="btnConfirmarExclusao" disabled>
                        <i class="fas fa-trash me-2"></i>Confirmar Exclusão
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
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
    
    // Validação da exclusão
    $('#confirmacaoExclusao').on('keyup', function() {
        const confirmacao = $(this).val().toUpperCase();
        const btnConfirmar = $('#btnConfirmarExclusao');
        
        if (confirmacao === 'EXCLUIR') {
            btnConfirmar.prop('disabled', false);
        } else {
            btnConfirmar.prop('disabled', true);
        }
    });
    
    // Prevenir envio do formulário de exclusão se não confirmado
    $('#formExcluir').submit(function(e) {
        const confirmacao = $('#confirmacaoExclusao').val().toUpperCase();
        
        if (confirmacao !== 'EXCLUIR') {
            e.preventDefault();
            alert('Por favor, digite "EXCLUIR" para confirmar a exclusão.');
        } else {
            return confirm('Tem certeza absoluta que deseja excluir este cargo?');
        }
    });
});
</script>
@endsection