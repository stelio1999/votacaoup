<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema de Votação Eletrónica - UP Maputo')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Estilos personalizados -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    @yield('styles')
</head>
@php
    $fullWidth = View::hasSection('full-width');
@endphp

<body>
   <div id="sidebar-overlay"></div>

    <!-- Menu lateral -->
    <div class="d-flex {{ $fullWidth ? '' : '' }}" id="wrapper">

        <!-- Sidebar -->

        @auth
            @if(!$fullWidth)
            <div class="bg-dark-blue border-right" id="sidebar-wrapper">

            <div class="sidebar-heading text-center py-4">
                <img src="{{ asset('images/logo-up.png') }}" alt="UP Maputo" class="img-fluid logo-img">
                <h4 class="text-white mt-3">Votação Eletrónica</h4>
            </div>
           
            <div class="list-group list-group-flush">
             
                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>

                {{-- APENAS ADMIN --}}
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('usuarios.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2"></i>Usuários
                    </a>
                    <a href="{{ route('cargos.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-briefcase me-2"></i>Cargos
                    </a>
                    <a href="{{ route('candidatos.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-tie me-2"></i>Candidatos
                    </a>
                    <a href="{{ route('eleicoes.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-vote-yea me-2"></i>Eleições
                    </a>

                    <a href="{{ route('relatorios.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-alt me-2"></i>Relatórios
                    </a>

                    <!--<a href="{{ route('configuracoes.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-cog me-2"></i>Configurações
                    </a>-->
                @endif

                {{-- APENAS ELEITOR --}}
                @if(auth()->user()->role === 'eleitor')
                    <a href="{{ route('votacao.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-vote-yea me-2"></i>Votação

                    </a>
                @endif

                {{-- COMISSÃO ELEITORAL E ADMIN --}}
                @if(auth()->user()->role === 'comissao')
                <a href="{{ route('candidatos.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-tie me-2"></i>Candidatos
                    </a>
                    <a href="{{ route('eleicoes.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-vote-yea me-2"></i>Eleições
                    </a>

                    <a href="{{ route('resultados.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-bar me-2"></i>Resultados
                    </a>
                    <a href="{{ route('relatorios.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-alt me-2"></i>Relatórios
                    </a>
                @endif

                
                
            </div>
        </div>
      @endif
@endauth

        <!-- Conteúdo da página -->
<div id="page-content-wrapper" class="{{ $fullWidth ? 'w-100 ms-0' : '' }}">
            <!-- Navbar -->
             @if(!$fullWidth)
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container-fluid">
        @auth
        <button class="btn btn-dark" id="menu-toggle">
            <i class="fas fa-bars"></i>
        </button>
        @endauth
        <div class="navbar-collapse">
            <ul class="navbar-nav ms-auto align-items-center">
                
                <!-- DARK MODE TOGGLE BUTTON -->
                <li class="nav-item me-3">
                    <div class="dark-mode-toggle">
                        <input type="checkbox" class="dark-mode-checkbox" id="darkmode-toggle">
                        <label class="dark-mode-label" for="darkmode-toggle">
                            <i class="fas fa-sun"></i>
                            <i class="fas fa-moon"></i>
                            <span class="dark-mode-ball">
                                <i class="fas fa-moon" style="color: #2c3e50; font-size: 12px;"></i>
                            </span>
                        </label>
                    </div>
                </li>
                
                @if(auth()->check())
                <!-- User dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        @if(auth()->user()->foto)
                            <img src="{{ Storage::url(auth()->user()->foto) }}" 
                                alt="{{ auth()->user()->name }}" 
                                class="rounded-circle me-2"
                                style="width: 30px; height: 30px; object-fit: cover;">
                        @else
                            <div class="avatar-circle me-2" style="width: 30px; height: 30px; font-size: 14px;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <span>{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                       <!-- <li>
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="fas fa-user me-2"></i>Meu Perfil
                            </a>
                        </li>-->
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.security') }}">
                                <i class="fas fa-shield-alt me-2"></i>Segurança
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Sair
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Entrar</a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
            @endif
            <!-- Conteúdo principal -->
            <div class="container-fluid px-4 py-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Scripts personalizados -->
    <script src="{{ asset('js/script.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar preferência salva no localStorage
        const darkModeToggle = document.getElementById('darkmode-toggle');
        const body = document.body;
        
        // Função para atualizar o ícone da bola
        function updateBallIcon(isDark) {
            const ball = document.querySelector('.dark-mode-ball i');
            if (ball) {
                ball.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
                ball.style.color = isDark ? '#f39c12' : '#2c3e50';
            }
        }
        
        // Verificar preferência salva
        if (localStorage.getItem('darkMode') === 'enabled') {
            body.classList.add('dark-mode');
            darkModeToggle.checked = true;
            updateBallIcon(true);
        } else {
            updateBallIcon(false);
        }
        
        // Evento de toggle do dark mode
        if (darkModeToggle) {
            darkModeToggle.addEventListener('change', function() {
                if (this.checked) {
                    body.classList.add('dark-mode');
                    localStorage.setItem('darkMode', 'enabled');
                    updateBallIcon(true);
                } else {
                    body.classList.remove('dark-mode');
                    localStorage.setItem('darkMode', 'disabled');
                    updateBallIcon(false);
                }
            });
        }
        
        // Detectar preferência do sistema operacional
        if (!localStorage.getItem('darkMode')) {
            const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
            
            if (prefersDarkScheme.matches) {
                body.classList.add('dark-mode');
                if (darkModeToggle) {
                    darkModeToggle.checked = true;
                    updateBallIcon(true);
                }
                localStorage.setItem('darkMode', 'enabled');
            }
            
            // Escutar mudanças na preferência do sistema
            prefersDarkScheme.addEventListener('change', function(e) {
                if (!localStorage.getItem('darkMode')) {
                    if (e.matches) {
                        body.classList.add('dark-mode');
                        if (darkModeToggle) darkModeToggle.checked = true;
                        updateBallIcon(true);
                    } else {
                        body.classList.remove('dark-mode');
                        if (darkModeToggle) darkModeToggle.checked = false;
                        updateBallIcon(false);
                    }
                }
            });
        }
        
        // Garantir que as cores do DataTables se adaptem ao dark mode
        if (typeof $.fn.DataTable !== 'undefined') {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        if (body.classList.contains('dark-mode')) {
                            $('.dataTables_wrapper').addClass('dark-mode');
                        } else {
                            $('.dataTables_wrapper').removeClass('dark-mode');
                        }
                    }
                });
            });
            
            observer.observe(body, { attributes: true });
        }
    });
</script>

    @yield('scripts')
</body>
</html>