$(document).ready(function() {
    // Menu toggle para mobile
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("show");
        $("#page-content-wrapper").toggleClass("shrink");
    });
    
    // Inicializar DataTables
    $('.datatable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-PT.json"
        },
        "responsive": true,
        "pageLength": 10,
        "order": [[0, 'desc']]
    });
    
    // Confirmar ações importantes
    $('.confirm-action').click(function(e) {
        if (!confirm('Tem certeza que deseja realizar esta ação?')) {
            e.preventDefault();
        }
    });
    
    // Fechar alertas automaticamente após 5 segundos
     
    
    // Animações na página de boas-vindas
    if ($('.welcome-hero').length) {
        // Animar stats cards
        $('.stats-card').each(function(index) {
            $(this).css('animation-delay', (index * 0.2) + 's');
            $(this).addClass('fade-in');
        });
        
        // Contadores animados
        $('.stats-number').each(function() {
            var $this = $(this);
            var countTo = parseInt($this.text());
            
            $({ countNum: 0 }).animate({
                countNum: countTo
            }, {
                duration: 2000,
                easing: 'swing',
                step: function() {
                    $this.text(Math.floor(this.countNum));
                },
                complete: function() {
                    $this.text(this.countNum);
                }
            });
        });
    }
    
    // Validação de formulários
    $('form.needs-validation').on('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        $(this).addClass('was-validated');
    });
    
    // Atualizar data e hora em tempo real
    function updateDateTime() {
        var now = new Date();
        var options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            timeZone: 'Africa/Maputo'
        };
        var dateTimeStr = now.toLocaleDateString('pt-MZ', options);
        $('.current-datetime').text(dateTimeStr);
    }
    
    if ($('.current-datetime').length) {
        updateDateTime();
        setInterval(updateDateTime, 1000);
    }
    
    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Votação - seleção de candidato
    $('.candidate-select').click(function() {
        $('.candidate-select').removeClass('selected');
        $(this).addClass('selected');
        $('#candidate_id').val($(this).data('candidate-id'));
        $('#submit-vote').prop('disabled', false);
    });
});

// Funções utilitárias globais
function showLoading() {
    $('#loading-overlay').fadeIn();
}

function hideLoading() {
    $('#loading-overlay').fadeOut();
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-MZ', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-MZ', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Carregar overlay
$(document).ajaxStart(function() {
    showLoading();
}).ajaxStop(function() {
    hideLoading();
});