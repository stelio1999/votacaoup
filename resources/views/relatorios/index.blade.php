@extends('layouts.app')

@section('title', 'Relatórios do Sistema')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0 text-dark">
            <i class="fas fa-file-alt me-2"></i>Relatórios do Sistema
        </h1>
        <p class="text-muted">Gere relatórios detalhados sobre o sistema de votação</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 fw-bold">
                    <i class="fas fa-vote-yea me-2"></i>Relatório de Eleições
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted">
                    Relatório completo sobre todas as eleições realizadas, incluindo estatísticas de participação e resultados.
                </p>
                <ul class="small text-muted mb-3">
                    <li>Número total de eleições</li>
                    <li>Taxa de participação</li>
                    <li>Resultados por eleição</li>
                    <li>Custos e eficiência</li>
                </ul>
                <button class="btn btn-outline-primary w-100" data-tipo="eleicoes">
                    <i class="fas fa-chart-line me-2"></i>Gerar Relatório
                </button>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 fw-bold">
                    <i class="fas fa-users me-2"></i>Relatório de Usuários
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted">
                    Análise detalhada dos usuários do sistema, incluindo padrões de acesso e participação.
                </p>
                <ul class="small text-muted mb-3">
                    <li>Total de usuários por categoria</li>
                    <li>Taxa de participação por grupo</li>
                    <li>Padrões de acesso e atividade</li>
                    <li>Usuários mais ativos</li>
                </ul>
                <button class="btn btn-outline-success w-100" data-tipo="usuarios">
                    <i class="fas fa-user-chart me-2"></i>Gerar Relatório
                </button>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 fw-bold">
                    <i class="fas fa-check-circle me-2"></i>Relatório de Votos
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted">
                    Estatísticas detalhadas sobre os votos registrados, incluindo padrões temporais e distribuição.
                </p>
                <ul class="small text-muted mb-3">
                    <li>Total de votos por período</li>
                    <li>Horários de pico de votação</li>
                    <li>Distribuição por dispositivo</li>
                    <li>Análise de tendências</li>
                </ul>
                <button class="btn btn-outline-info w-100" data-tipo="votos">
                    <i class="fas fa-chart-bar me-2"></i>Gerar Relatório
                </button>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h6 class="m-0 fw-bold">
                    <i class="fas fa-shield-alt me-2"></i>Relatório de Auditoria
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted">
                    Log completo de todas as ações realizadas no sistema para fins de auditoria e segurança.
                </p>
                <ul class="small text-muted mb-3">
                    <li>Registro de todas as ações</li>
                    <li>Acesso por IP e dispositivo</li>
                    <li>Alterações nos dados</li>
                    <li>Conformidade com regulamentações</li>
                </ul>
                <button class="btn btn-outline-warning w-100" data-tipo="auditoria">
                    <i class="fas fa-clipboard-check me-2"></i>Gerar Relatório
                </button>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-secondary text-white">
                <h6 class="m-0 fw-bold">
                    <i class="fas fa-cogs me-2"></i>Relatório Personalizado
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted">
                    Crie um relatório personalizado combinando diferentes tipos de dados e critérios específicos.
                </p>
                <ul class="small text-muted mb-3">
                    <li>Combine múltiplos tipos de dados</li>
                    <li>Filtros avançados por período</li>
                    <li>Seleção de campos específicos</li>
                    <li>Comparações temporais</li>
                </ul>
                <button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#personalizadoModal">
                    <i class="fas fa-sliders-h me-2"></i>Criar Relatório
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Relatório Personalizado -->
<div class="modal fade" id="personalizadoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Criar Relatório Personalizado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formRelatorio" method="POST" action="{{ route('relatorios.gerar') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo" class="form-label">Tipo de Relatório *</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="">Selecione um tipo</option>
                                <option value="eleicoes">Eleições</option>
                                <option value="usuarios">Usuários</option>
                                <option value="votos">Votos</option>
                                <option value="auditoria">Auditoria</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="periodo" class="form-label">Período *</label>
                            <select class="form-select" id="periodo" name="periodo" required>
                                <option value="">Selecione um período</option>
                                @foreach($periodos as $valor => $nome)
                                    <option value="{{ $valor }}">{{ $nome }}</option>
                                @endforeach
                                <option value="personalizado">Personalizado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row" id="periodoPersonalizado" style="display: none;">
                        <div class="col-md-6 mb-3">
                            <label for="data_inicio" class="form-label">Data de Início</label>
                            <input type="date" class="form-control" id="data_inicio" name="data_inicio">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="data_fim" class="form-label">Data de Fim</label>
                            <input type="date" class="form-control" id="data_fim" name="data_fim">
                        </div>
                    </div>
                    
                    <div class="row" id="filtroEleicao" style="display: none;">
                        <div class="col-12 mb-3">
                            <label for="eleicao_id" class="form-label">Filtrar por Eleição</label>
                            <select class="form-select" id="eleicao_id" name="eleicao_id">
                                <option value="">Todas as eleições</option>
                                @foreach(\App\Models\Eleicao::all() as $eleicao)
                                    <option value="{{ $eleicao->id }}">{{ $eleicao->titulo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Formato de Saída *</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="formato" id="formatoTela" value="tela" checked>
                                <label class="form-check-label" for="formatoTela">
                                    <i class="fas fa-desktop me-2"></i>Visualizar na Tela
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="formato" id="formatoPDF" value="pdf">
                                <label class="form-check-label" for="formatoPDF">
                                    <i class="fas fa-file-pdf me-2"></i>Download PDF
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="formato" id="formatoExcel" value="excel">
                                <label class="form-check-label" for="formatoExcel">
                                    <i class="fas fa-file-excel me-2"></i>Download Excel
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="formato" id="formatoCSV" value="csv">
                                <label class="form-check-label" for="formatoCSV">
                                    <i class="fas fa-file-csv me-2"></i>Download CSV
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-play me-2"></i>Gerar Relatório
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow mt-4">
    <div class="card-header">
        <h6 class="m-0 fw-bold text-primary">
            <i class="fas fa-history me-2"></i>Relatórios Recentes
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Período</th>
                        <th>Gerado por</th>
                        <th>Data</th>
                        <th>Formato</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aqui seriam listados os relatórios gerados anteriormente -->
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-file-alt fa-2x mb-3"></i><br>
                            Nenhum relatório gerado recentemente
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Configurar botões de tipo de relatório
    $('[data-tipo]').click(function() {
        const tipo = $(this).data('tipo');
        $('#tipo').val(tipo);
        $('#personalizadoModal').modal('show');
    });
    
    // Mostrar/ocultar período personalizado
    $('#periodo').change(function() {
        if ($(this).val() === 'personalizado') {
            $('#periodoPersonalizado').show();
        } else {
            $('#periodoPersonalizado').hide();
        }
    });
    
    // Mostrar/ocultar filtro de eleição para relatórios específicos
    $('#tipo').change(function() {
        const tiposComEleicao = ['eleicoes', 'votos'];
        if (tiposComEleicao.includes($(this).val())) {
            $('#filtroEleicao').show();
        } else {
            $('#filtroEleicao').hide();
        }
    });
    
    // Validação do formulário
    $('#formRelatorio').submit(function(e) {
        const periodo = $('#periodo').val();
        const dataInicio = $('#data_inicio').val();
        const dataFim = $('#data_fim').val();
        
        if (periodo === 'personalizado' && (!dataInicio || !dataFim)) {
            e.preventDefault();
            alert('Por favor, preencha as datas de início e fim para o período personalizado.');
            return false;
        }
        
        if (periodo === 'personalizado' && dataInicio && dataFim && dataInicio > dataFim) {
            e.preventDefault();
            alert('A data de início não pode ser posterior à data de fim.');
            return false;
        }
        
        return true;
    });
});
</script>
@endsection