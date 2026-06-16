<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'IFRS') }} - Cardápio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/rawline" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Rawline';
            src: url('{{ asset('fonts/rawline/rawline-400.ttf') }}') format('truetype');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Rawline';
            src: url('{{ asset('fonts/rawline/rawline-700.ttf') }}') format('truetype');
            font-weight: 700;
            font-style: normal;
            font-display: swap;
        }
        :root {
            --bs-font-sans-serif: 'Rawline', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            --bs-body-font-family: var(--bs-font-sans-serif);
        }
        body, h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
            font-family: 'Rawline', sans-serif !important;
        }
        body { background-color: #f8f9fa; }

        /* Botão Login: Sem arredondamento extremo, texto "Login" */
        .btn-login-float {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            font-weight: 500;
        }

        .carousel-control-prev, .carousel-control-next {
            filter: invert(1);
        }
    </style>
</head>
<body>

{{-- Botão Flutuante (FAB) --}}
<a href="{{ route('login') }}" class="btn btn-dark btn-login-float px-4">
    Login
</a>

<main class="container py-5">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
