<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <!-- End Google Tag Manager -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="api-token" content="{{ optional(auth()->user())->api_token ?? '' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name')}}

        {{-- Extract the page title from our (possibly) breadcrumped title section --}}
        @php($pageTitle = strip_tags($__env->yieldContent('title')))
        @unless(empty($pageTitle)) - {{ $pageTitle }}@endunless
    </title>

    <!-- Scripts -->
    <script src="{{ mix('js/manifest.js') }}" defer></script>
    <script src="{{ mix('js/vendor.js') }}" defer></script>
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{config('services.gtm.key')}}');</script>

    <script>
        @auth
        dataLayer = [{
            userId: @json(auth()->id()),
            parentId: @json(auth()->user()->parent_id),
        }];
        @endauth

        @foreach([
            'trackUserActivation',
            'trackUserLogin',
            'trackUserRegistration',
        ] as $event)
            @if(session()->pull($event))
            window.dataLayer.push({ event: @json($event) });
            @endif
        @endforeach
    </script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id={{config('services.gtm.key')}}"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div id="app" class="d-flex flex-column wrapper">
        <div class="pt-2 px-2 bg-white">
            <div class="container px-0 px-md-4 d-flex align-items-center justify-content-end">
                <div class="text-right small">
                    <span class="d-none d-sm-inline">Unsere</span>
                    Service-Hotline:
                    <a class="bold text-dark font-weight-bold mx-1" href="tel:+4940210917370">040 / 210 91 73 - 70</a>
                    Mo - Fr, 9 bis 18 Uhr
                </div>
            </div>
        </div>
        <nav class="navbar navbar-expand-md navbar-light navbar-exporo sticky-top">
            <div class="container">
                <a class="navbar-brand text-dark" href="{{ route('home') }}">
                    @include('vendor.nova.partials.logo', ['color' => '#333'])
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
                            <li class="nav-item">
                                Angemeldet als

                                <strong>
                                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                </strong>
                            </li>
                            <li>
                                <a class="nav-link" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @if(session('adminLoginLink'))
            <div class="alert alert-danger m-0">
                <div class="container d-flex justify-content-between align-items-baseline">
                    <div>
                        <b>Achtung: Admin-Login wird genutzt!</b>
                        Sie befinden Sich in einem Profil-Bereich eines anderen Users.
                    </div>

                    <a href="{{ session('adminLoginLink') }}" class="btn btn-outline-dark btn-sm">
                        Zurückkehren
                    </a>
                </div>
            </div>
        @endif

        @if(session(\App\Http\Middleware\UserHasFilledPersonalData::USER_HAS_MISSING_DATA))
            <div class="alert alert-warning m-0">
                <div class="container d-flex justify-content-between align-items-baseline">
                    <div>
                        <b>Bitte hinterlegen Sie Ihre IBAN und BIC.</b>
                        Es werden keine Provisionen ausgezahlt, wenn diese fehlen.
                    </div>

                    <a href="{{ route('users.edit', request()->user()) }}" class="btn btn-outline-dark btn-sm">
                        Jetzt eintragen
                    </a>
                </div>
            </div>
        @endif

        <main class="flex-fill @yield('content-class', 'py-4')">
            @yield('content')
        </main>

        <footer class="py-5">
            <div class="container text-center text-white">
                <h5 class="text-uppercase text-white">Copyright &copy; Exporo AG {{ now()->format('Y') }}</h5>
                <ul class="list-inline mb-0 small">
                    <li class="list-inline-item">
                        <a href="https://exporo.de/impressum/" class="text-white">Impressum</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="https://exporo.de/datenschutz/" class="text-white">Datenschutz</a>
                    </li>
                </ul>
            </div>
        </footer>
    </div>

    @hasSection('scripts')
        @section('scripts')
    @else
        {{-- Transition flickering fix (https://github.com/twbs/bootstrap/issues/22014) --}}
        <script>var __flickerFix = true;</script>
    @endif
</body>
</html>
