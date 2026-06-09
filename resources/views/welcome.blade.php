@extends('layouts.app')
@section('full-width')
@section('title', 'Sistema de Votação Eletrónica - UP Maputo')

@section('styles')
<style>

</style>
@endsection

@section('content')
<div class="hero-section">
    <div class="hero-bg"></div>

    <!-- Elementos flutuantes -->
    <div class="floating-element" style="width: 100px; height: 100px; top: 20%; left: 10%;"></div>
    <div class="floating-element" style="width: 150px; height: 150px; top: 60%; right: 15%;"></div>
    <div class="floating-element" style="width: 80px; height: 80px; bottom: 20%; left: 20%;"></div>

    <div class="container px-3 px-lg-0">

        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <h1 class="display-3 fw-bold mb-4">Sistema de Votação Eletrónica</h1>
                <h3 class="mb-4">Universidade Pedagógica de Maputo</h3>


                <div class="d-flex flex-wrap gap-3 mb-5 justify-content-center justify-content-lg-start">

                    <!--<div class="text-center">
                        <div class="stats-counter" data-count="100">0</div>

                        <div class="text-white-50">Segurança</div>
                    </div>
                    <div class="text-center">
                        <div class="stats-counter" data-count="100">0</div>
                        <div class="text-white-50">Transparência</div>
                    </div>
                    <div class="text-center">
                        <div class="stats-counter" data-count="100">0 </div>
                        <div class="text-white-50">Eficiência</div>
                    </div>
                    <div class="text-center">
                        <div class="stats-counter1" >0</div>
                        <div class="text-white-50">Confusão</div>
                    </div>-->
                </div>

                @if(auth()->check())
                    <a href="{{ route('dashboard') }}" class="btn btn-get-started">
                        <i class="fas fa-arrow-right me-2"></i>Ir para Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-get-started me-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Entrar
                    </a>

                @endif
            </div>

            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="{{ asset('images/vota.png') }}" alt="Votação Eletrónica" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Seção de Funcionalidades -->

<!-- Seção de Benefícios -->
  <!--
<section-- class="py-5 bg-dark text-white">
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
</section-->

<!-- Footer
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
                    <a href="https://web.facebook.com/profile.php?id=100057184250872" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="https://www.linkedin.com/company/universidade-pedag%C3%B3gica/" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
                </div>
            </div>
        </div>
        <hr class="opacity-25 my-4">
        <div class="text-center opacity-75">
            <p class="mb-0">&copy; 2026 Universidade Pedagógica de Maputo. Todos os direitos reservados.</p>
        </div>
    </div>
</footer>-->
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
@endsection
