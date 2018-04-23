<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ mix('js/manifest.js') }}" defer></script>
    <script src="{{ mix('js/vendor.js') }}" defer></script>
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" class="d-flex flex-column wrapper">
        <div class="p-2 bg-white border-bottom">
            <div class="container px-0 px-md-4 text-right small">
                <img class="mr-3 hidden-xs" src="{{ asset('images/MAM-Partner-logo.svg') }}" height="20">
                <span class="d-none d-sm-inline">Unsere</span>
                Service-Hotline:&nbsp;&nbsp;
                <strong>
                    <a class="bold text-dark" href="tel:040210917300">040 / 210 91 73 - 00</a>&nbsp;&nbsp;
                </strong>
                Mo - Fr, 9 bis 20 Uhr
            </div>
        </div>
        <nav class="navbar navbar-expand-md navbar-light navbar-exporo">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo_exporo_blue.svg') }}" alt="{{ config('app.name', 'Laravel') }}">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="flex-fill">
            @yield('content')
        </main>

        <footer class="py-5">
            <div class="container text-center text-white">
                <h5 class="text-uppercase text-white">Copyright &copy; Exporo AG {{ now()->format('Y') }}</h5>
                <ul class="list-inline mb-0 small">
                    <li class="list-inline-item"><a href="#" class="text-white">Impressum</a></li>
                    <li class="list-inline-item"><a href="#" class="text-white">Datenschutz</a></li>
                </ul>
            </div>
        </footer>
    </div>
</body>
</html>
