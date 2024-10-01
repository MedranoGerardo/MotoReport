@extends('layouts.login')

@section('title', 'Login')

@section('content')

<head>
    <!-- CSS de Bootstrap primero -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Luego tus estilos personalizados -->
    <style>
        .btn-custom {
            background-color: #203E4F;
            color: white;
            border: none;
        }

        .btn-custom:hover {
            background-color: #182F3E;
            color: white;
        }
    </style>
</head>

<div class="login-container">
    <div class="login-card">
        <img src="/asset/img/MotoReport.png" alt="MotoReport" class="login-logo">
        <h3>{{ __('Acceder') }}</h3>
        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="username" class="col-form-label usuario">Usuario</label>
                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                        name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                    @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="col-form-label contraseña">Contraseña</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-custom btn-block">
                        {{ __('Acceder') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection