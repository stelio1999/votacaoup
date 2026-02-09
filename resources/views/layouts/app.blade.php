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
<body>
   
    <!-- Menu lateral -->
    <div class="d-flex" id="wrapper">
        @auth
        <!-- Sidebar -->
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

                    <a href="{{ route('configuracoes.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-cog me-2"></i>Configurações
                    </a>
                @endif

                {{-- APENAS ELEITOR --}}
                @if(auth()->user()->role === 'eleitor')
                    <a href="{{ route('votacao.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-ballot me-2"></i>Votação

                    </a>
                @endif

                {{-- COMISSÃO ELEITORAL E ADMIN --}}
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'comissao')
                    <a href="{{ route('resultados.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-bar me-2"></i>Resultados
                    </a>
                    <a href="{{ route('relatorios.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-alt me-2"></i>Relatórios
                    </a>
                @endif

                
                
            </div>
        </div>
        @endauth

        <!-- Conteúdo da página -->
        <div id="page-content-wrapper">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-dark" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="navbar-collapse">
                        <ul class="navbar-nav ms-auto">
                            @if(auth()->check())
                            <!-- No navbar -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                        @if(auth()->user()->foto)
                                            <img src="{{ Storage::url(auth()->user()->foto) }}" 
                                                alt="{{ auth()->user()->name }}" 
                                                class="rounded-circle me-1"
                                                style="width: 30px; height: 30px; object-fit: cover;">
                                        @else
                                            <i class="fas fa-user-circle me-1"></i>
                                        @endif
                                        {{ auth()->user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                                <i class="fas fa-user me-2"></i>Meu Perfil
                                            </a>
                                        </li>
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
    
    @yield('scripts')
</body>
</html>