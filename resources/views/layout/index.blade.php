<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Tibia is a free massive multiplayer online role playing game (MMORPG).">
    <meta name="keywords" content="The Legends free online game, free multiplayer game, ots, open tibia server">
    <meta name="author" content="Web Design Technologies">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <!-- CSRF Token -->
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('otserver.site.serverName') }} - @yield('title')</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('images/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('stylesheets')
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top">
            <div class="bg-dark container-fluid w-75 p-3 d-flex justify-content-center">
                <a class="navbar-brand"><img src="{{ asset('images/general/logo.png') }}" class='img-fluid' width="200"/></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item active">
                            <a class="nav-link" aria-current="page" href="{{ route('index') }}">Inicio</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="dropdownGameInfoMenuButton" role="button" data-bs-toggle="dropdown" aria-expanded="false">Informação do Jogo</a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownGameInfoMenuButton">
                                <li><a class="dropdown-item" href="{{ route('shop.index') }}"><img src="{{ asset('images/icons/shop.gif') }}" height="20"> Loja</a></li>
                                <li><a class="dropdown-item" href="{{ route('vocations.index') }}"><img src="{{ asset('images/icons/vocation.gif') }}" height="25"> Vocações</a></li>
                                <li><a class="dropdown-item" href="{{ route('spells.index') }}"><img src="{{ asset('images/icons/spells.gif') }}" height="20"> Magias</a></li>
                                <li><a class="dropdown-item" href="{{ route('guilds.index') }}"><img src="{{ asset('images/icons/guilds.gif') }}" height="20"> Guilds</a></li>
                                <li><a class="dropdown-item" href="{{ route('killstatistics.index') }}"><img src="{{ asset('images/icons/deaths.gif') }}" height="20"> Mortes</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ranking.index') }}">Ranking</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Downloads</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('webshop.index') }}">WebShop</a>
                        </li>
                    </ul>
                    @if(Visitor::isLogged())
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="myAccountDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Minha Conta</a>
                            <ul class="dropdown-menu" aria-labelledby="myAccountDropdownMenuLink">
                                <li><a class="dropdown-item" href="{{ route('accountmanagement.index') }}">Painel</a></li>
                                <li><a class="dropdown-item" href="{{ route('accountmanagement.changeinfo') }}">Editar Perfil</a></li>
                                <li><a class="dropdown-item" href="{{ route('accountmanagement.changepassword') }}">Alterar Senha</a></li>
                                <li><a class="dropdown-item" href="{{ route('accountmanagement.logout') }}">Sair</a></li>
                            </ul>
                        </li>
                    </ul>
                    @endif
                </div>
            </div>
        </nav>
    </header>
    <main>@yield('content')</main>
    <footer class="py-3">
        <div class="container text-white text-center">
            <div class="row">
                <div class="col-4">
                    <p class="fs-5 fw-bold">Sobre</p>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <div class="fb-page"
                                data-href="https://www.facebook.com/Oficial.TheLegenDs"
                                data-adapt-container-width="true"
                                data-width="" data-height=""
                                data-hide-cover="false"
                                data-show-facepile="false"></div>
                        </li>
                    </ul>
                </div>
                <div class="col-4">
                    <p class="fs-5 fw-bold">Informações</p>
                    <ul class="nav flex-column col-9 mx-auto">
                        <li class="nav-item">
                            <div class="row">
                                <div class="col-6"><a href="{{ route('whoisonline.index') }}" class="text-white text-decoration-none">Status:</a></div>
                                <div class="col-6 {{ config('otserver.status.serverStatus_online') == 1 ? 'text-success' : 'text-danger' }}">
                                    {{ config('otserver.status.serverStatus_online') == 1 ? 'Online' : 'Offline' }}
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="row">
                                <div class="col-6"><a href="{{ route('whoisonline.index') }}" class="text-white text-decoration-none">Usuários:</a></div>
                                <div class="col-6">
                                    {{ config('otserver.status.serverStatus_online') == 1 ? config('otserver.status.serverStatus_players').'/'.config('otserver.status.serverStatus_playersMax') : '0/1000' }}
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="row">
                                <div class="col-6"><a href="{{ route('whoisonline.index') }}" class="text-white text-decoration-none">Uptime:</a></div>
                                <div class="col-6">
                                    {{ config('otserver.status.serverStatus_online') == 1 ? config('otserver.status.serverStatus_uptime') : '0h0m' }}
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="row">
                                <div class="col-6"><a href="{{ route('whoisonline.index') }}" class="text-white text-decoration-none">Record:</a></div>
                                <div class="col-6">
                                    @php
                                    $record = \Website::getDBHandle()->query('SELECT `record` FROM `server_record` ORDER BY `record` DESC LIMIT 1;');
                                    @endphp
                                    @foreach ($record as $result)
                                    {{$result['record']}}
                                    @endforeach
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('whoisonline.index') }}" class="{{ config('otserver.status.serverStatus_online') == 1 ? 'text-success' : 'text-danger' }} text-decoration-none">{{ config('otserver.status.serverStatus_online') == 1 ? 'Players Online' : 'Players Offline' }}</a>
                        </li>
                    </ul>
                </div>
                <div class="col-4">
                    <p class="fs-5 fw-bold">Pesquisar Personagem</p>
                    <ul class="nav flex-column">
                        <form action="{{ route('searchcharacters.redirectWithParams') }}" method="POST" class="nav-item">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-10 p-0">
                                    <input type="text" class="form-control rounded-0 rounded-start" id="search" name="name" placeholder="Informe o nome do personagem">
                                    <label for="search" class="text-dark">Informe o nome do personagem</label>
                                </div>
                                <button class="btn btn-primary text-white col rounded-0 rounded-end"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </ul>
                </div>
                <div class="col-12">
                    <div class="container">
                        <ul class="nav py-4 justify-content-center">
                            <li class="nav-item"><a href="#" class="btn btn-circle text-white" data-toggle="tooltip" title="" data-original-title="Follow us on Twitter"><i class="fab fa-twitter"></i></a></li>
                            <li class="nav-item"><a href="#" class="btn btn-circle text-white" data-toggle="tooltip" title="" data-original-title="Follow us on Facebook"><i class="fab fa-facebook"></i></a></li>
                            <li class="nav-item"><a href="#" class="btn btn-circle text-white" data-toggle="tooltip" title="" data-original-title="Follow us on Google"><i class="fab fa-google-plus"></i></a></li>
                            <li class="nav-item"><a href="#" class="btn btn-circle text-white" data-toggle="tooltip" title="" data-original-title="Follow us on Steam"><i class="fab fa-steam"></i></a></li>
                        </ul>
                        <p>© {{ config('app.copyright') }}-{{ \Carbon\Carbon::now()->format('Y') }} {{ config('otserver.server.serverName') }}. Todos os direitos Reservados.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
        $.ajaxSetup({ cache: true });
            $.getScript('https://connect.facebook.net/pt_BR/sdk.js', function(){
                FB.init({
                appId: '135494350411477',
                xfbml      : true,
                version: 'v9.0' // or v2.1, v2.2, v2.3, ...
                });
            });
        });
      </script>
    @stack('scripts')
</body>
</html>
