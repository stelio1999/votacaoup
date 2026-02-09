@extends('layouts.app')

@section('title', 'Sistema de Votação Eletrónica - UP Maputo')

@section('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, var(--azul-escuro) 0%, var(--azul-claro) 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .hero-content {
        z-index: 2;
        position: relative;
    }
    
    .hero-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0.1;
        background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.4' fill-rule='evenodd'/%3E%3C/svg%3E");
    }
    
    .floating-element {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    
    .feature-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 2rem;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        background: rgba(255, 255, 255, 0.2);
    }
    
    .stats-counter {
        font-size: 3rem;
        font-weight: 700;
        background: linear-gradient(45deg, #fff, #a0c4ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .btn-get-started {
        background: var(--verde-suave);
        border: none;
        padding: 1rem 2.5rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.3s ease;
    }
    
    .btn-get-started:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 25px rgba(56, 161, 105, 0.3);
    }
</style>
@endsection

@section('content')
<div class="hero-section">
    <div class="hero-bg"></div>
    
    <!-- Elementos flutuantes -->
    <div class="floating-element" style="width: 100px; height: 100px; top: 20%; left: 10%;"></div>
    <div class="floating-element" style="width: 150px; height: 150px; top: 60%; right: 15%;"></div>
    <div class="floating-element" style="width: 80px; height: 80px; bottom: 20%; left: 20%;"></div>
    
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <h1 class="display-3 fw-bold mb-4">Sistema de Votação Eletrónica</h1>
                <h3 class="mb-4">Universidade Pedagógica de Maputo</h3>
                <p class="lead mb-5">
                    Modernize o processo eleitoral da sua instituição com segurança, 
                    transparência e eficiência. Uma solução completa para eleições 
                    académicas digitais.
                </p>
                
                <div class="d-flex flex-wrap gap-3 mb-5">
                    <div class="text-center">
                        <div class="stats-counter" data-count="100">0</div>
                        <div class="text-white-50">Segurança</div>
                    </div>
                    <div class="text-center">
                        <div class="stats-counter" data-count="100">0</div>
                        <div class="text-white-50">Transparência</div>
                    </div>
                    <div class="text-center">
                        <div class="stats-counter" data-count="100">0</div>
                        <div class="text-white-50">Eficiência</div>
                    </div>
                </div>
                
                @if(auth()->check())
                    <a href="{{ route('dashboard') }}" class="btn btn-get-started">
                        <i class="fas fa-arrow-right me-2"></i>Ir para Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-get-started me-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Entrar no Sistema
                    </a>
                    <a href="#features" class="btn btn-outline-light">
                        <i class="fas fa-info-circle me-2"></i>Saiba Mais
                    </a>
                @endif
            </div>
            
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="{{ asset('images/voting-illustration.svg') }}" alt="Votação Eletrónica" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Seção de Funcionalidades -->
<section id="features" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-dark mb-3">Funcionalidades Principais</h2>
            <p class="lead text-muted">Um sistema completo para gestão eleitoral académica</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-shield-alt fa-3x text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Segurança Máxima</h4>
                    <p class="text-muted">
                        Criptografia avançada, autenticação segura e proteção 
                        contra fraudes para garantir a integridade do processo eleitoral.
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-bolt fa-3x text-success"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Rapidez e Eficiência</h4>
                    <p class="text-muted">
                        Apuração automática em tempo real, redução de erros humanos 
                        e eliminação de processos manuais morosos.
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-chart-line fa-3x text-info"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Transparência Total</h4>
                    <p class="text-muted">
                        Relatórios detalhados, auditoria completa e resultados 
                        verificáveis para aumentar a confiança no processo.
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-mobile-alt fa-3x text-warning"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Multiplataforma</h4>
                    <p class="text-muted">
                        Acessível em computadores, tablets e smartphones. 
                        Interface responsiva e adaptável a qualquer dispositivo.
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-user-check fa-3x text-danger"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Autenticação Segura</h4>
                    <p class="text-muted">
                        Sistema de verificação de identidade robusto que garante 
                        que cada eleitor vote apenas uma vez.
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-file-alt fa-3x text-secondary"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Relatórios Detalhados</h4>
                    <p class="text-muted">
                        Geração automática de estatísticas, gráficos e relatórios 
                        para análise pós-eleitoral e tomada de decisões.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Seção de Estatísticas -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="text-center">
                    <div class="stats-number display-6 fw-bold text-primary mb-2" data-count="5000">0</div>
                    <div class="text-muted">Eleitores Cadastrados</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="text-center">
                    <div class="stats-number display-6 fw-bold text-success mb-2" data-count="50">0</div>
                    <div class="text-muted">Eleições Realizadas</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="text-center">
                    <div class="stats-number display-6 fw-bold text-info mb-2" data-count="200">0</div>
                    <div class="text-muted">Candidatos Registados</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="text-center">
                    <div class="stats-number display-6 fw-bold text-warning mb-2" data-count="99.9">0</div>
                    <div class="text-muted">Taxa de Sucesso</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Seção de Benefícios -->
<section class="py-5 bg-dark text-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Benefícios para a Universidade</h2>
            <p class="lead opacity-75">Modernização e eficiência no processo eleitoral</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Redução de Custos</h5>
                        <p class="opacity-75">
                            Eliminação de gastos com papel, impressão e logística 
                            do processo eleitoral manual.
                        </p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Processo Mais Rápido</h5>
                        <p class="opacity-75">
                            Resultados disponíveis em tempo real após o encerramento 
                            da votação.
                        </p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Maior Participação</h5>
                        <p class="opacity-75">
                            Facilidade de acesso aumenta o engajamento da comunidade 
                            académica nas eleições.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Transparência Garantida</h5>
                        <p class="opacity-75">
                            Auditoria completa e verificável de todo o processo 
                            eleitoral.
                        </p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Segurança Avançada</h5>
                        <p class="opacity-75">
                            Proteção contra fraudes e garantia da integridade dos votos.
                        </p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Alinhamento Tecnológico</h5>
                        <p class="opacity-75">
                            Modernização institucional e adopção de práticas 
                            tecnológicas inovadoras.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark-blue text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5 class="fw-bold mb-3">Sistema de Votação Eletrónica</h5>
                <p class="mb-3 opacity-75">
                    Universidade Pedagógica de Maputo<br>
                    Modernizando o processo eleitoral académico
                </p>
                <p class="opacity-75">
                    <i class="fas fa-map-marker-alt me-2"></i> Maputo, Moçambique
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <h5 class="fw-bold mb-3">Contactos</h5>
                <p class="mb-2 opacity-75">
                    <i class="fas fa-envelope me-2"></i> votacao@up.ac.mz
                </p>
                <p class="opacity-75">
                    <i class="fas fa-phone me-2"></i> +258 84 123 4567
                </p>
                <div class="mt-3">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
                </div>
            </div>
        </div>
        <hr class="opacity-25 my-4">
        <div class="text-center opacity-75">
            <p class="mb-0">&copy; 2026 Universidade Pedagógica de Maputo. Todos os direitos reservados.</p>
        </div>
    </div>
</footer>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Animar contadores
    $('.stats-counter').each(function() {
        var $this = $(this);
        var countTo = parseFloat($this.data('count'));
        
        $({ countNum: 0 }).animate({
            countNum: countTo
        }, {
            duration: 2000,
            easing: 'swing',
            step: function() {
                $this.text(this.countNum.toFixed(countTo % 1 === 0 ? 0 : 1));
            },
            complete: function() {
                $this.text(this.countNum.toFixed(countTo % 1 === 0 ? 0 : 1));
            }
        });
    });
    
    // Animar números de estatísticas
    $('.stats-number').each(function() {
        var $this = $(this);
        var countTo = parseInt($this.data('count'));
        
        $({ countNum: 0 }).animate({
            countNum: countTo
        }, {
            duration: 2500,
            easing: 'swing',
            step: function() {
                $this.text(Math.floor(this.countNum));
            },
            complete: function() {
                $this.text(this.countNum);
            }
        });
    });
    
    // Smooth scroll para links âncora
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if(target.length) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 70
            }, 1000);
        }
    });
});
</script>
@endsection