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
 <!-- <link rel="shortcut icon" href="{{ asset('asset/img/remove.png') }}" type="image/png"> -->


  @livewireStyles
  <!-- Scripts -->
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
  <!-- Custom CSS -->
  <link href="{{ asset('asset/css/login.css') }}" rel="stylesheet">
</head>

<body style="background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));">
  <div class="container d-flex justify-content-center align-items-center" style="height: 100vh; b">
    @yield('content')
  </div>
</body>

</html>