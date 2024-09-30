<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name', 'laravel') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('asset/css/login.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/nav.css') }}" rel="stylesheet">
   <!-- <link rel="shortcut icon" href="{{ asset('asset/img/remove.png') }}" type="image/png"> -->
    <!-- Styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @livewireStyles
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        #loader {
            position: absolute;
            background: #00000050;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            z-index: 100;
            width: 100%;
            height: 100vh;
            display: flex;
            opacity: 0;
            visibility: hidden;
            justify-content: center;
            align-items: center;
            transition: 0.5s ease all;
        }

        #loader.show {
            opacity: 1;
            visibility: visible;
        }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 3rem;
            height: 3rem;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div id="loader">
        <div class="loader"></div>
    </div>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                   <!-- <img src="{{ asset('asset/img/remove.png') }}" alt="{{ config('app.name', 'laravel') }}"
                        height="50"> -->
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto d-md-none">
                        @include('layouts.main-menu')
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown"
                                class="nav-link dropdown-toggle d-flex align-items-center flex-row justify-content-center text-white user-menu"
                                role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                v-pre>
                                <div
                                    style="font-size: 1.25rem;background-color: #343a40;border-radius: 50%;width: 40px;height: 40px;display: flex;align-items: center;justify-content: center;margin-right: 0.5rem;">
                                    <i class=" fas fa-user" style="color: #fff;margin-right: 0;"></i>
                                </div>
                                <span class="caret" style="font-size: 0.65rem;text-transform: capitalize;">
                                    <span style="font-size: 0.90rem;">{{ Auth::user()->name }}</span><br>
                                    {{ Auth::user()->role }}
                                </span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    Salir
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <nav id="sidebar" class="d-none col-md-3 col-lg-2 d-md-block bg-dark sidebar" style="height: 100vh;">
                    <div class="sidebar-sticky">
                        @include('layouts.main-menu')
                    </div>
                </nav>

                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 mb-2" style="height:86.5vh;
    overflow-y: auto;">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
    @livewireScripts
    <script>
        document.addEventListener('livewire:initialized', function () {
            Livewire.on('message-success', function (message) {
                Alert(
                    '¡Éxito!',
                    message,
                    'success'
                );
            });

            Livewire.on('message-error', function (message) {
                Alert(
                    '¡Error!',
                    message,
                    'error'
                );
            });
        });

        //parte para que se mantenga la actvidad del estilo en cada venta 
        document.addEventListener('DOMContentLoaded', function () {
            var currentUrl = window.location.pathname;
            var ventasLink = document.querySelector(`.nav-link[href="{{ route('ventas') }}"]`);

            if (ventasLink && currentUrl.includes('/venta')) {
                ventasLink.classList.add('active');
            }
        });
    </script>
</body>

</html>