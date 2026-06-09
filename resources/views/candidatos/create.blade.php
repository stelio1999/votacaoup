@extends('layouts.app')

@section('title', 'Criar Nova Candidatura')

@section('styles')
<style>
   
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('candidatos.index') }}">Candidatos</a></li>
                <li class="breadcrumb-item active">Nova Candidatura</li>
            </ol>
        </nav>
        
        <h1 class="h3 mb-0 text-dark">
            <i class="fas fa-user-tie me-2"></i>Criar Nova Candidatura
        </h1>
        <p class="text-muted">Registre uma nova candidatura para uma eleição</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">Formulário de Candidatura</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('candidatos.store') }}" class="needs-validation" novalidate enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Passo 1: Selecionar Usuário -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 text-primary">1. Selecionar Candidato</h6>
                        
                        <div class="mb-3">
                            <label for="user_search" class="form-label">Buscar Usuário *</label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       id="user_search" 
                                       placeholder="Digite nome, email ou matrícula..."
                                       autocomplete="off">
                                <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                Busque pelo nome, email ou matrícula do usuário
                            </div>
                            <div id="searchResults" class="candidate-search-results"></div>
                        </div>
                        
                        <input type="hidden" name="user_id" id="user_id" required>
                        
                        <div id="userPreview" class="candidate-preview">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center">
                                    <div class="candidate-avatar" id="userAvatar">
                                        <span id="userInitials">U</span>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <h5 id="userName" class="mb-2"></h5>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-envelope me-1"></i>
                                                <span id="userEmail"></span>
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-id-card me-1"></i>
                                                <span id="userMatricula"></span>
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-graduation-cap me-1"></i>
                                                <span id="userCategoria"></span>
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-briefcase me-1"></i>
                                                <span id="userCurso"></span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="noUserSelected" class="alert alert-warning" style="display: none;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Nenhum usuário selecionado. Por favor, selecione um usuário da lista.
                        </div>
                    </div>
                    
                    <!-- Passo 2: Selecionar Eleição -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 text-primary">2. Selecionar Eleição</h6>
                        
                        <div class="mb-3">
                            <label for="eleicao_id" class="form-label">Eleição *</label>
                            <select class="form-select @error('eleicao_id') is-invalid @enderror" 
                                    id="eleicao_id" 
                                    name="eleicao_id" 
                                    required>
                                <option value="" selected disabled>Selecione uma eleição</option>
                                @foreach($eleicoes as $eleicao)
                                <option value="{{ $eleicao->id }}" 
                                        data-cargo="{{ $eleicao->cargo->nome }}"
                                        data-categoria="{{ $eleicao->cargo->categoria }}"
                                        {{ old('eleicao_id') == $eleicao->id ? 'selected' : '' }}>
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
                        
                        <div id="eleicaoInfo" class="alert alert-info" style="display: none;">
                            <h6 class="alert-heading mb-2">Informações da Eleição</h6>
                            <p class="mb-1" id="eleicaoCargo"></p>
                            <p class="mb-1" id="eleicaoCategoria"></p>
                            <p class="mb-0" id="eleicaoPeriodo"></p>
                        </div>
                    </div>
                    
                    <!-- Passo 3: Informações da Candidatura -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 text-primary">3. Informações da Candidatura</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="numero_candidato" class="form-label">Número do Candidato *</label>
                                <input type="text" 
                                       class="form-control @error('numero_candidato') is-invalid @enderror" 
                                       id="numero_candidato" 
                                       name="numero_candidato" 
                                       value="{{ old('numero_candidato') }}" 
                                       required
                                       placeholder="Ex: 12345">
                                <div class="form-text">
                                    Número único que aparecerá na urna
                                </div>
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
                                <div class="form-text">
                                    Formato: JPEG, PNG, GIF (máx. 2MB)
                                </div>
                                @error('foto')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                                
                                <div class="photo-preview mt-2" id="photoPreview">
                                    <i class="fas fa-user fa-3x text-muted"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="proposta" class="form-label">Proposta de Campanha</label>
                            <textarea class="form-control @error('proposta') is-invalid @enderror" 
                                      id="proposta" 
                                      name="proposta" 
                                      rows="6"
                                      placeholder="Descreva suas propostas e planos para o cargo">{{ old('proposta') }}</textarea>
                            <div class="form-text">
                                Apresente suas ideias e propostas para os eleitores
                            </div>
                            @error('proposta')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="curriculo" class="form-label">Currículo/Experiência</label>
                                <textarea class="form-control @error('curriculo') is-invalid @enderror" 
                                          id="curriculo" 
                                          name="curriculo" 
                                          rows="4"
                                          placeholder="Descreva sua experiência e qualificações">{{ old('curriculo') }}</textarea>
                                @error('curriculo')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="video_url" class="form-label">Link do Vídeo (Opcional)</label>
                                <input type="url" 
                                       class="form-control @error('video_url') is-invalid @enderror" 
                                       id="video_url" 
                                       name="video_url" 
                                       value="{{ old('video_url') }}"
                                       placeholder="https://youtube.com/...">
                                <div class="form-text">
                                    Link para vídeo de campanha (YouTube, Vimeo, etc.)
                                </div>
                                @error('video_url')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="website" class="form-label">Website/Redes Sociais</label>
                            <input type="url" 
                                   class="form-control @error('website') is-invalid @enderror" 
                                   id="website" 
                                   name="website" 
                                   value="{{ old('website') }}"
                                   placeholder="https://seusite.com">
                            <div class="form-text">
                                Site pessoal ou perfil nas redes sociais
                            </div>
                            @error('website')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Validações -->
                    <div class="mb-4">
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-clipboard-check me-2"></i>Validações
                            </h6>
                            <div id="validationMessages">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-question-circle text-warning me-2"></i>
                                    <span>Selecione um usuário para verificar as validações</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('candidatos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                            <i class="fas fa-check-circle me-2"></i>Criar Candidatura
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4"><!--
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>Instruções
                </h6>
            </div>
            <div class="card-body">
                <div class="steps">
                    <div class="step mb-4">
                        <div class="step-number mb-2">1</div>
                        <h6>Selecionar Candidato</h6>
                        <p class="small text-muted mb-0">
                            Busque pelo usuário que será candidato. Certifique-se de que ele está ativo no sistema.
                        </p>
                    </div>
                    
                    <div class="step mb-4">
                        <div class="step-number mb-2">2</div>
                        <h6>Selecionar Eleição</h6>
                        <p class="small text-muted mb-0">
                            Escolha a eleição para a qual o usuário será candidato.
                        </p>
                    </div>
                    
                    <div class="step mb-4">
                        <div class="step-number mb-2">3</div>
                        <h6>Preencher Informações</h6>
                        <p class="small text-muted mb-0">
                            Complete os dados da candidatura, incluindo proposta e foto.
                        </p>
                    </div>
                    
                    <div class="step">
                        <div class="step-number mb-2">4</div>
                        <h6>Validações</h6>
                        <p class="small text-muted mb-0">
                            O sistema verificará automaticamente se o candidato atende aos requisitos.
                        </p>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="alert alert-warning">
                    <h6 class="alert-heading">
                        <i class="fas fa-exclamation-triangle me-2"></i>Atenção
                    </h6>
                    <p class="small mb-0">
                        Após criar a candidatura, será necessário aprová-la antes que o candidato possa participar da eleição.
                    </p>
                </div>
            </div>
        </div>-->
        
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-history me-2"></i>Candidaturas Recentes
                </h6>
            </div>
            <div class="card-body">
                @php
                    $candidaturasRecentes = \App\Models\Candidato::with(['user', 'eleicao'])
                        ->latest()
                        ->take(5)
                        ->get();
                @endphp
                
                @if($candidaturasRecentes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($candidaturasRecentes as $candidatura)
                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    @if($candidatura->foto)
                                        <img src="{{ Storage::url($candidatura->foto) }}" 
                                             alt="{{ $candidatura->user->name }}" 
                                             class="rounded-circle"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="avatar-circle-sm">
                                            <span class="initials-sm">{{ $candidatura->iniciais }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $candidatura->user->name }}</h6>
                                    <small class="text-muted">
                                        {{ $candidatura->eleicao->titulo }}
                                    </small>
                                </div>
                                <span class="badge bg-{{ $candidatura->aprovado ? 'success' : 'warning' }}">
                                    {{ $candidatura->aprovado ? 'Aprovado' : 'Pendente' }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center mb-0">
                        <i class="fas fa-user-tie fa-2x mb-2"></i><br>
                        Nenhuma candidatura registrada
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let selectedUser = null;
    let selectedEleicao = null;
    
    // Buscar usuários
    $('#user_search').on('input', function() {
        const query = $(this).val().trim();
        
        if (query.length < 2) {
            $('#searchResults').hide().empty();
            return;
        }
        
        $.ajax({
            url: '{{ route("candidatos.buscar-usuarios") }}',
            method: 'GET',
            data: { q: query },
            success: function(data) {
                const results = $('#searchResults');
                results.empty();
                
                if (data.length === 0) {
                    results.append(`
                        <div class="candidate-result-item text-center text-muted">
                            Nenhum usuário encontrado
                        </div>
                    `);
                } else {
                    data.forEach(function(user) {
                        const item = $(`
                            <div class="candidate-result-item" 
                                 data-user-id="${user.id}"
                                 data-user-name="${user.name}"
                                 data-user-email="${user.email}"
                                 data-user-matricula="${user.matricula || ''}"
                                 data-user-categoria="${user.categoria}"
                                 data-user-curso="${user.curso || ''}">
                                <div class="fw-bold">${user.name}</div>
                                <small class="text-muted">
                                    ${user.email} • ${user.matricula || 'Sem matrícula'}
                                </small>
                            </div>
                        `);
                        
                        item.click(function() {
                            selectUser(user);
                            results.hide();
                        });
                        
                        results.append(item);
                    });
                }
                
                results.show();
            }
        });
    });
    
    // Limpar busca
    $('#clearSearch').click(function() {
        $('#user_search').val('');
        $('#searchResults').hide().empty();
        clearUserSelection();
    });
    
    // Selecionar usuário
    function selectUser(user) {
        selectedUser = user;
        
        // Preencher campos ocultos
        $('#user_id').val(user.id);
        $('#user_search').val(user.name);
        
        // Preencher preview
        $('#userName').text(user.name);
        $('#userEmail').text(user.email);
        $('#userMatricula').text(user.matricula || 'Não informada');
        $('#userCategoria').text(formatCategoria(user.categoria));
        $('#userCurso').text(user.curso || 'Não informado');
        
        // Calcular iniciais para avatar
        const initials = user.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        $('#userInitials').text(initials);
        
        // Mostrar preview
        $('#userPreview').show();
        $('#noUserSelected').hide();
        
        // Verificar validações
        checkValidations();
    }
    
    // Limpar seleção de usuário
    function clearUserSelection() {
        selectedUser = null;
        $('#user_id').val('');
        $('#userPreview').hide();
        $('#noUserSelected').show();
        $('#validationMessages').html(`
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-question-circle text-warning me-2"></i>
                <span>Selecione um usuário para verificar as validações</span>
            </div>
        `);
        $('#submitBtn').prop('disabled', true);
    }
    
    // Formatar categoria
    function formatCategoria(categoria) {
        switch(categoria) {
            case 'estudante': return 'Estudante';
            case 'docente': return 'Docente';
            case 'tecnico_administrativo': return 'Técnico Administrativo';
            default: return categoria;
        }
    }
    
    // Selecionar eleição
    $('#eleicao_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        selectedEleicao = {
            id: $(this).val(),
            cargo: selectedOption.data('cargo'),
            categoria: selectedOption.data('categoria')
        };
        
        if (selectedEleicao.id) {
            // Preencher informações da eleição
            $('#eleicaoCargo').html(`<strong>Cargo:</strong> ${selectedEleicao.cargo}`);
            $('#eleicaoCategoria').html(`<strong>Categoria:</strong> ${formatCategoria(selectedEleicao.categoria)}`);
            $('#eleicaoInfo').show();
            
            // Verificar validações
            checkValidations();
        } else {
            selectedEleicao = null;
            $('#eleicaoInfo').hide();
        }
    });
    
    // Verificar validações
    function checkValidations() {
        const validationDiv = $('#validationMessages');
        validationDiv.empty();
        
        let isValid = true;
        let messages = [];
        
        // Verificar se usuário foi selecionado
        if (!selectedUser) {
            isValid = false;
            messages.push({
                icon: 'times-circle',
                color: 'danger',
                text: 'Nenhum usuário selecionado'
            });
        }
        
        // Verificar se eleição foi selecionada
        if (!selectedEleicao) {
            isValid = false;
            messages.push({
                icon: 'times-circle',
                color: 'danger',
                text: 'Nenhuma eleição selecionada'
            });
        }
        
        // Verificar compatibilidade categoria
        if (selectedUser && selectedEleicao) {
            if (selectedUser.categoria !== selectedEleicao.categoria) {
                isValid = false;
                messages.push({
                    icon: 'times-circle',
                    color: 'danger',
                    text: `Categoria incompatível. Usuário é ${formatCategoria(selectedUser.categoria)}, mas a eleição é para ${formatCategoria(selectedEleicao.categoria)}`
                });
            } else {
                messages.push({
                    icon: 'check-circle',
                    color: 'success',
                    text: `Categoria compatível: ${formatCategoria(selectedUser.categoria)}`
                });
            }
        }
        
        // Verificar se número foi preenchido
        const numero = $('#numero_candidato').val().trim();
        if (!numero) {
            isValid = false;
            messages.push({
                icon: 'times-circle',
                color: 'danger',
                text: 'Número do candidato não preenchido'
            });
        } else {
            messages.push({
                icon: 'check-circle',
                color: 'success',
                text: 'Número do candidato preenchido'
            });
        }
        
        // Exibir mensagens
        messages.forEach(function(msg) {
            validationDiv.append(`
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-${msg.icon} text-${msg.color} me-2"></i>
                    <span>${msg.text}</span>
                </div>
            `);
        });
        
        // Ativar/desativar botão de envio
        $('#submitBtn').prop('disabled', !isValid);
        
        return isValid;
    }
    
    // Verificar validações em tempo real
    $('#numero_candidato').on('input', checkValidations);
    $('#user_search').on('input', function() {
        if (!$(this).val().trim()) {
            clearUserSelection();
        }
    });
    
    // Preview de foto
    $('#foto').change(function() {
        const file = this.files[0];
        const preview = $('#photoPreview');
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.html(`<img src="${e.target.result}" alt="Preview">`);
            }
            
            reader.readAsDataURL(file);
        } else {
            preview.html('<i class="fas fa-user fa-3x text-muted"></i>');
        }
    });
    
    // Validação do formulário
    $('form.needs-validation').on('submit', function(e) {
        if (!checkValidations()) {
            e.preventDefault();
            alert('Por favor, corrija os erros antes de enviar o formulário.');
            return false;
        }
        
        if (!selectedUser) {
            e.preventDefault();
            alert('Por favor, selecione um usuário.');
            return false;
        }
        
        if (!selectedEleicao) {
            e.preventDefault();
            alert('Por favor, selecione uma eleição.');
            return false;
        }
        
        return true;
    });
    
    // Fechar resultados ao clicar fora
    $(document).click(function(e) {
        if (!$(e.target).closest('#user_search, #searchResults').length) {
            $('#searchResults').hide();
        }
    });
    
    // Inicializar
    clearUserSelection();
    if ($('#eleicao_id').val()) {
        $('#eleicao_id').trigger('change');
    }
});

// Estilos
const style = document.createElement('style');
style.textContent = `
.step-number {
    width: 30px;
    height: 30px;
    background: var(--azul-claro);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.avatar-circle-sm {
    width: 40px;
    height: 40px;
    background: var(--azul-claro);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
}

.initials-sm {
    font-size: 0.9rem;
}
`;
document.head.appendChild(style);
</script>
@endsection