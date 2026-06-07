<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ env('APP_NAME', 'IFRS') }} - Login">
    <title>Login · {{ config('app.name', 'IFRS') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="{{ asset('assets/css/login.css') }}" rel="stylesheet">

</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">

<main class="form-signin w-100 m-auto text-center">
    <form method="POST" action="{{ route('loginSubmit') }}">
        @csrf

        <img class="mb-2 img-fluid" src="{{ asset('assets/img/logo-sisgem.png') }}" alt="Logo SISGEM" style="max-width: 260px; height: auto;">

        <h1 class="h4 mb-4 fw-normal text-secondary">Por favor, faça login</h1>

        @if ($errors->any())
            <div class="alert alert-danger p-2 small text-start">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-floating text-start">
            <input
                type="text"
                name="username" class="form-control @error('username') is-invalid @enderror"
                id="floatingInput"
                placeholder="Nome de usuário"
                value="{{ old('username') }}"
                autofocus
                required
            >
            <label for="floatingInput">Nome de usuário</label>
        </div>

        <div class="form-floating text-start">
            <input
                type="password"
                name="password" class="form-control @error('password') is-invalid @enderror"
                id="floatingPassword"
                placeholder="Senha"
                required
            >
            <label for="floatingPassword">Senha</label>
        </div>

        <div class="form-check text-start my-3">
            <input
                class="form-check-input"
                type="checkbox"
                name="remember"
                id="checkDefault"
            >
            <label class="form-check-label text-secondary" for="checkDefault">
                Lembrar-me
            </label>
        </div>

        <button class="btn btn-primary w-100 py-2 fw-bold" type="submit">
            Entrar
        </button>

        <p class="mt-5 mb-3 text-body-secondary small">&copy; {{ date('Y') }} SISGEM - IFRS PoA</p>
    </form>
</main>

</body>
</html>
