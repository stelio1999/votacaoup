@extends('layouts.app')

@section('title', 'Editar Candidatura')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('candidatos.index') }}">Candidatos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('candidatos.show', $candidato) }}">Detalhes</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-dark">
                <i class="fas fa-edit me-2"></i>Editar Candidatura
            </h1>
            <div class="btn-group">
                <a href="{{ route('candidatos.show', $candidato) }}" class="btn btn-info">
                    <i class="fas fa-eye me-2"></i>Ver Detalhes
                </a>
                <a href="{{ route('candidatos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">Formulário de Edição</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('candidatos.update', $candidato) }}" 
                      class="needs-validation" novalidate enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="user_id" class="form-label">Candidato *</label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       value="{{ $candidato->user->name }} ({{ $candidato->user->email }})" 
                                       disabled>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                O candidato não pode ser alterado após o registro.
                            </small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="eleicao_id" class="form-label">Eleição *</label>
                            <select class="form-select @error('eleicao_id') is-invalid @enderror" 
                                    id="eleicao_id" 
                                    name="eleicao_id" 
                                    required>
                                <option value="" disabled>Selecione uma eleição</option>
                                @foreach($eleicoes as $eleicao)
                                <option value="{{ $eleicao->id }}" 
                                        {{ old('eleicao_id', $candidato->eleicao_id) == $eleicao->id ? 'selected' : '' }}>
                                    {{ $eleicao->titulo }} ({{ $eleicao->cargo->nome }})
                                </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Por favor, selecione uma eleição.
                            </div>
                            @error('eleicao_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero_candidato" class="form-label">Número do Candidato *</label>
                            <input type="text" 
                                   class="form-control @error('numero_candidato') is-invalid @enderror" 
                                   id="numero_candidato" 
                                   name="numero_candidato" 
                                   value="{{ old('numero_candidato', $candidato->numero_candidato) }}" 
                                   required
                                   placeholder="Ex: 001">
                            <div class="invalid-feedback">
                                Por favor, insira o número do candidato.
                            </div>
                            @error('numero_candidato')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="foto" class="form-label">Foto do Candidato</label>
                            <input type="file" 
                                   class="form-control @error('foto') is-invalid @enderror" 
                                   id="foto" 
                                   name="foto"
                                   accept="image/*">
                            <div class="invalid-feedback">
                                Por favor, selecione uma imagem válida.
                            </div>
                            @error('foto')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Tamanho máximo: 2MB. Formatos: JPG, PNG, GIF.
                            </small>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="proposta" class="form-label">Proposta do Candidato</label>
                        <textarea class="form-control @error('proposta') is-invalid @enderror" 
                                  id="proposta" 
                                  name="proposta" 
                                  rows="6"
                                  placeholder="Descreva as propostas e objetivos da candidatura...">{{ old('proposta', $candidato->proposta) }}</textarea>
                        <div class="invalid-feedback">
                            Por favor, descreva a proposta do candidato.
                        </div>
                        @error('proposta')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Esta informação será exibida para os eleitores durante a votação.
                        </small>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 fw-bold">
                    <i class="fas fa-info-circle me-2"></i>Informações Atuais
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    @if($candidato->foto)
                        <img src="{{ Storage::url($candidato->foto) }}" 
                             alt="{{ $candidato->user->name }}" 
                             class="rounded-circle mb-3"
                             style="width: 100px; height: 100px; object-fit: cover; border: 2px solid var(--azul-claro);">
                    @else
                        <div class="avatar-medium mb-3">
                            <span class="initials">{{ substr($candidato->user->name, 0, 2) }}</span>
                        </div>
                    @endif
                    
                    <h5 class="fw-bold">{{ $candidato->user->name }}</h5>
                    <p class="text-muted mb-0">{{ $candidato->user->email }}</p>
                </div>
                
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Número Atual</span>
                        <span class="fw-bold badge bg-dark">#{{ $candidato->numero_candidato }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Eleição Atual</span>
                        <span class="fw-bold text-end">{{ $candidato->eleicao->titulo }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Cargo</span>
                        <span class="fw-bold">{{ $candidato->eleicao->cargo->nome }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Status</span>
                        @if($candidato->aprovado)
                            <span class="badge bg-success">Aprovado</span>
                        @else
                            <span class="badge bg-warning">Pendente</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h6 class="m-0 fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i>Avisos Importantes
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <h6 class="alert-heading">
                        <i class="fas fa-ban me-2"></i>Restrições
                    </h6>
                    <ul class="mb-0 small">
                        <li>O candidato não pode ser alterado após o registro.</li>
                        <li>Mudar a eleição pode afetar a elegibilidade.</li>
                        <li>O número do candidato deve ser único por eleição.</li>
                        @if($candidato->eleicao->status === 'ativa')
                            <li class="text-danger fw-bold">
                                Esta eleição está ativa. Alterações podem afetar a votação.
                            </li>
                        @endif
                    </ul>
                </div>
                
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="fas fa-lightbulb me-2"></i>Dicas
                    </h6>
                    <ul class="mb-0 small">
                        <li>Mantenha a proposta clara e objetiva.</li>
                        <li>Use uma foto profissional para melhor apresentação.</li>
                        <li>Verifique se todas as informações estão corretas antes de salvar.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview da foto -->
<div class="modal fade" id="previewFotoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pré-visualização da Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewFoto" src="" alt="Pré-visualização" class="img-fluid" style="max-height: 500px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.avatar-medium {
    width: 100px;
    height: 100px;
    background: var(--azul-claro);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: bold;
    margin: 0 auto;
}
</style>
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
    
    // Preview da foto
    $('#foto').change(function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#previewFoto').attr('src', e.target.result);
                $('#previewFotoModal').modal('show');
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Validação do número do candidato
    $('#numero_candidato').on('input', function() {
        var value = $(this).val();
        $(this).val(value.replace(/\D/g, '')); // Remove não-números
    });
    
    // Verificar se a eleição permite edição
    var eleicaoStatus = '{{ $candidato->eleicao->status }}';
    if (eleicaoStatus === 'concluida') {
        alert('Atenção: Esta eleição está concluída. Edições podem não ser permitidas.');
        $('#eleicao_id').prop('disabled', true);
    }
});
</script>
@endsection