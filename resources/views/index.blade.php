<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Cardápio de Merenda · {{ env('APP_NAME', 'IFRS') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

            --green: #117A43;
            --green-deep: #0C5E33;
            --ink: #171A17;
            --muted: #9AA09B;
            --hairline: #EEF1EF;
            --surface: #F4F6F4;
            --card: #ffffff;
        }

        * { box-sizing: border-box; -webkit-font-smoothing: antialiased; text-rendering: optimizeLegibility; }

        body {
            font-family: 'Rawline', sans-serif !important;
            color: var(--ink);
            background: var(--surface);
            display: flex;
            justify-content: center;
            margin: 0;
        }

        h1, h2, h3, h4, h5, h6 { font-family: 'Rawline', sans-serif !important; }

        .app {
            width: 100%;
            max-width: 100%;
            min-height: 100dvh;
            background: var(--surface);
            position: relative;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        .container-app {
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
        }

        .hero {
            background: linear-gradient(165deg, #159150 0%, var(--green-deep) 100%);
            padding-bottom: 26px;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .hero-blob { position: absolute; border-radius: 50%; pointer-events: none; }
        .hero-blob--a { right: -40px; top: -30px; width: 170px; height: 170px; background: rgba(255,255,255,.07); }
        .hero-blob--b { left: -30px; bottom: -50px; width: 130px; height: 130px; background: rgba(255,255,255,.05); }

        .appbar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 24px 22px 18px; position: relative;
        }

        .brand { display: flex; align-items: center; gap: 10px; }

        .brand-mark {
            width: 30px; height: 30px; border-radius: 9px; background: rgba(255,255,255,.18);
            color: #fff; font-size: 12px; font-weight: 800; display: flex; align-items: center; justify-content: center;
        }

        .brand-name { font-size: 14px; font-weight: 700; color: #fff; }

        .login {
            font-size: 13px; font-weight: 700; color: var(--green-deep);
            background: #fff; border-radius: 999px; padding: 7px 16px; text-decoration: none;
            transition: transform .15s ease;
        }

        .login:active { transform: scale(.96); }

        .hero-title { padding: 12px 24px 0; position: relative; text-align: center; }
        .hero-eyebrow { margin: 0; font-size: 13px; font-weight: 700; color: #A9E0C0; letter-spacing: .1em; text-transform: uppercase; }
        .hero-title h1 { margin: 6px 0 0; font-size: 34px; font-weight: 800; color: #fff; letter-spacing: -.02em; line-height: 1.05; }
        .hero-date { margin: 8px 0 0; font-size: 15px; font-weight: 600; color: #CDEBD8; text-transform: capitalize; }

        .datestrip {
            display: flex; gap: 9px; overflow-x: auto;
            padding: 18px 22px 15px;
            scrollbar-width: none;
            border-bottom: 1px solid var(--hairline);
            justify-content: flex-start;
            padding-top: 2rem;
        }

        .datestrip::-webkit-scrollbar { display: none; }

        @media (min-width: 768px) {
            .datestrip { justify-content: center; }
        }

        .day {
            flex: 0 0 auto; width: 60px; text-align: center;
            border-radius: 16px; background: #fff; border: 1px solid #E3E7E4;
            padding: 10px 0; cursor: pointer; font-family: inherit;
            box-shadow: 0 8px 18px -12px rgba(0,0,0,.15);
            transition: transform .15s ease, background-color .2s, border-color .2s;
        }
        .day:active { transform: scale(.95); }
        .day-wd { display: block; font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; }
        .day-n  { display: block; font-size: 18px; font-weight: 800; color: #3B413C; margin-top: 2px; }

        .day--active {
            background: var(--green); border-color: var(--green);
            box-shadow: 0 12px 22px -10px rgba(17,122,67,.5);
        }

        .day--active .day-wd { color: #A9E0C0; }
        .day--active .day-n  { color: #fff; }

        .day-panel { display: none; flex: 1; overflow-y: auto; padding-bottom: 20px;}
        .day-panel.active { display: block; animation: fadeIn .3s ease; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

        .menu {
            padding: 30px 20px 8px;
            display: flex;
            flex-direction: column;
            gap: 30px;
            max-width: 700px;
            margin: 0 auto;
            width: 100%;
        }

        .period { width: 100%; }

        .p-0 { --accent: #E08A2B; --accent-tint: #FBEFDD; --accent-ink: #9A5E14; }
        .p-1 { --accent: #1F9E8C; --accent-tint: #E2F3F0; --accent-ink: #0E5F54; }
        .p-2 { --accent: #5A57D1; --accent-tint: #ECEBFB; --accent-ink: #3B399A; }
        .p-3 { --accent: #D9483B; --accent-tint: #FBEAE8; --accent-ink: #9E2B21; }

        .period-head {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        .period-ico {
            width: 44px; height: 44px; border-radius: 13px; flex: 0 0 auto;
            display: flex; align-items: center; justify-content: center;
            background: var(--accent); color: #fff;
            box-shadow: 0 10px 18px -8px color-mix(in srgb, var(--accent) 70%, transparent);
        }
        .period-meta { text-align: left; text-transform: lowercase;}
        .period-meta h2 { margin: 0; font-size: 20px; font-weight: 800; letter-spacing: -.01em; }
        .period-meta p  { margin: 0; font-size: 12px; font-weight: 600; color: var(--muted); }
        .period-meta::first-letter{ text-transform: uppercase;}
        .period-time {
            font-size: 13px; font-weight: 800; white-space: nowrap;
            color: var(--accent-ink); background: var(--accent-tint);
            padding: 6px 12px; border-radius: 999px;
        }

        @media (max-width: 450px) {
            .period-head { flex-direction: column; text-align: center; gap: 8px;}
            .period-meta { text-align: center; }
            .period-time { margin-left: 0; margin-top: 5px; }
        }

        .row-wrap {
            position: relative;
            display: flex;
            justify-content: center;
            width: 100%; /* Força o limite de largura no iOS */
        }

        .row {
            display: flex; /* Mudado de inline-flex para flex */
            flex-direction: row; /* Força a direção horizontal no Safari */
            flex-wrap: nowrap; /* Proíbe expressamente o Safari de empilhar (quebrar linha) */
            gap: 16px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch; /* Essencial para a rolagem fluida no iOS */
            scrollbar-width: none;
            padding: 4px 4px 12px;
            max-width: 100%;
        }
        .row::-webkit-scrollbar { display: none; }

        .item {
            flex: 0 0 150px;
            width: 150px; /* Ajuda o Safari a entender o tamanho exato */
            min-width: 150px; /* Impede o Safari de tentar esmagar o item */
            scroll-snap-align: start;
            background: var(--card); border: 1px solid var(--hairline); border-radius: 22px;
            padding: 20px 12px; text-align: center; cursor: pointer;
            box-shadow: 0 8px 24px -16px rgba(0,0,0,.15);
            transition: transform .18s ease, box-shadow .18s ease;
            position: relative;
            overflow: hidden;
        }

        .item:hover { transform: translateY(-3px); box-shadow: 0 12px 28px -16px rgba(0,0,0,.25); }

        .item-badge-excecao {
            position: absolute; top: 0; left: 0; width: 100%;
            background: #ffc107; color: #000; font-size: 10px; font-weight: 800;
            padding: 3px 0; text-transform: uppercase;
        }

        .item-ico {
            width: 55px; height: 55px; border-radius: 50%; margin: 0 auto 12px;
            display: flex; align-items: center; justify-content: center;
            background: var(--t); color: var(--c);
        }
        .item-name { display: block; font-size: 15px; font-weight: 800; line-height: 1.2; }
        .item-cat {
            display: inline-block; margin-top: 10px;
            font-size: 10px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase;
            color: var(--c); background: var(--t); padding: 4px 10px; border-radius: 999px;
        }

        .row-fade {
            position: absolute; top: 0; right: 0; bottom: 8px; width: 50px;
            pointer-events: none; border-radius: 0 22px 22px 0;
            background: linear-gradient(to right, rgba(244,246,244,0), var(--surface) 85%);
            opacity: 1; transition: opacity .25s ease;
        }
        .row-wrap.at-end .row-fade { opacity: 0; }

        .row-scroll {
            position: absolute; right: -10px; top: calc(50% - 4px); transform: translateY(-50%);
            width: 38px; height: 38px; border-radius: 50%; border: none; cursor: pointer;
            background: var(--accent); color: #fff;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 18px -6px color-mix(in srgb, var(--accent) 80%, transparent);
            transition: opacity .25s ease, transform .35s ease, background .2s ease;
            animation: nudge 1.8s ease-in-out infinite;
        }
        .row-scroll:active { transform: translateY(-50%) scale(.9); }
        .row-wrap.at-end .row-scroll { transform: translateY(-50%) rotate(180deg); animation: none; }
        .row-wrap.interacted .row-scroll { animation: none; }

        @keyframes nudge {
            0%, 100% { transform: translateY(-50%) translateX(0); }
            50%      { transform: translateY(-50%) translateX(4px); }
        }

        .foot {
            text-align: center; font-size: 13px; font-weight: 600; color: var(--muted);
            padding: 30px 0 max(30px, env(safe-area-inset-bottom));
            margin-top: auto;
        }

        /* Botão do dia SEM cardápio cadastrado (um pouco mais escuro) */
        .day--empty {
            background: #e9ecef;
            border-color: #dee2e6;
            opacity: 0.85; /* Aumentado para não ficar tão "apagado" */
        }

        .day--empty .day-wd {
            color: #868e96; /* Mais escuro */
        }

        .day--empty .day-n {
            color: #495057; /* Mais escuro */
        }

        /* Estado ativo E vazio */
        .day--active.day--empty {
            background: #dce0de;
            border-color: #c4c9c6;
            box-shadow: none;
            opacity: 1;
        }

        .day--active.day--empty .day-wd,
        .day--active.day--empty .day-n {
            color: #3b413c;
        }
    </style>
</head>
<body>

@php
    // ==============================================================================
    // HELPER FUNCIONAL: Retorna Cores, Categoria e Ícone (SVG) baseado no nome
    // ==============================================================================
    if (!function_exists('getEstiloAlimento')) {
        function getEstiloAlimento($nomeAlimento) {
            // 1. Prepara a string (minúsculas e sem acentos) para facilitar a busca
            $nome = mb_strtolower(trim($nomeAlimento), 'UTF-8');
            $acentos = ['á'=>'a','à'=>'a','ã'=>'a','â'=>'a','é'=>'e','ê'=>'e','í'=>'i','ó'=>'o','õ'=>'o','ô'=>'o','ú'=>'u','ç'=>'c'];
            $nome = strtr($nome, $acentos);

            // 2. Frutas (Banana, Maçã, Bergamota)
            if (str_contains($nome, 'banana')) {
                return ['c' => '#C99016', 't' => '#FBF1D5', 'cat' => 'Frutas', 'svg' => '<path d="M4 13c3.5-2 8-2 10 2a5.5 5.5 0 0 1 8 5"/><path d="M5.15 17.89c5.52-1.52 8.65-6.89 7-12C11.55 4 11.5 2 13 2c3.22 0 5 5.5 5 8 0 6.5-4.2 12-10.49 12C5.11 22 2 22 2 20c0-1.5 1.14-1.55 3.15-2.11Z"/>'];
            }
            if (str_contains($nome, 'maca') || str_contains($nome, 'bergamota') || str_contains($nome, 'laranja')) {
                return ['c' => '#D9483B', 't' => '#FBEAE8', 'cat' => 'Frutas', 'svg' => '<path d="M12 20.94c1.5 0 2.75 1.06 4 1.06 3 0 6-8 6-12.22A4.91 4.91 0 0 0 17 5c-2.22 0-4 1.44-5 2-1-.56-2.78-2-5-2a4.9 4.9 0 0 0-5 4.78C2 14 5 22 8 22c1.25 0 2.5-1.06 4-1.06Z"/><path d="M10 2c1 .5 2 2 2 5"/>'];
            }

            // 3. Bebidas & Laticínios (Iogurte, Suco, Achocolatado)
            if (str_contains($nome, 'iogurte') || str_contains($nome, 'suco') || str_contains($nome, 'achocolatado') || str_contains($nome, 'leite')) {
                return ['c' => '#2F76C9', 't' => '#E7F0FB', 'cat' => 'Bebidas', 'svg' => '<path d="M8 2h8"/><path d="M9 2v2.789a4 4 0 0 1-.672 2.219l-.656.984A4 4 0 0 0 7 10.212V20a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-9.789a4 4 0 0 0-.672-2.219l-.656-.984A4 4 0 0 1 15 4.788V2"/><path d="M7 15a6.472 6.472 0 0 1 5 0 6.47 6.47 0 0 0 5 0"/>'];
            }

            // 4. Salgados, Sanduíches & Pães (Pastel, Sanduíche, Pão)
            if (str_contains($nome, 'pastel') || str_contains($nome, 'sanduiche') || str_contains($nome, 'pao')) {
                return ['c' => '#D35400', 't' => '#FAD7A1', 'cat' => 'Salgados', 'svg' => '<path d="M3 12h18"/><path d="M4 18v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/><path d="M3 12c0-3.31 2.69-6 6-6h6c3.31 0 6 2.69 6 6"/>'];
            }

            // 5. Doces e Assados (Bolo, Cookie, Biscoito, Bolacha)
            if (str_contains($nome, 'bolo') || str_contains($nome, 'cookie') || str_contains($nome, 'biscoito') || str_contains($nome, 'bolacha')) {
                return ['c' => '#8E44AD', 't' => '#F5EEF8', 'cat' => 'Doces', 'svg' => '<circle cx="12" cy="12" r="9"/><path d="M8 9.5v.01"/><path d="M12 7v.01"/><path d="M15.5 11.5v.01"/><path d="M10.5 15.5v.01"/><path d="M14 16v.01"/>'];
            }

            // 6. Default (Caso não encontre a palavra, usa ícone de prato genérico)
            return ['c' => '#1F9E8C', 't' => '#E2F3F0', 'cat' => 'Geral', 'svg' => '<path d="M2 14h20"/><path d="M12 4a8 8 0 0 0-8 8h16a8 8 0 0 0-8-8z"/><path d="M12 2v2"/>'];
        }
    }
@endphp

<main class="app">

    <header class="hero">
        <div class="hero-blob hero-blob--a"></div>
        <div class="hero-blob hero-blob--b"></div>

        <div class="container-app">
            <div class="appbar">
                <div class="brand">
                    <svg viewBox="0 0 708 204" style="height: 50px; width: auto;" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M288.881 90.2261C286.775 87.8963 283.191 85.6562 278.128 83.5056C273.065 81.355 267.846 80.2797 262.469 80.2797C256.107 80.2797 251.336 81.467 248.155 83.8416C245.018 86.1714 243.45 89.4869 243.45 93.788C243.45 95.1321 243.629 96.3418 243.988 97.4171C244.346 98.4924 244.794 99.4557 245.332 100.307C245.914 101.113 246.766 101.897 247.886 102.659C249.006 103.421 250.104 104.07 251.179 104.608C252.299 105.146 253.8 105.728 255.682 106.355C257.608 106.938 259.4 107.453 261.058 107.901C262.716 108.304 264.889 108.842 267.577 109.514C271.878 110.545 275.552 111.575 278.599 112.606C281.645 113.591 284.58 114.868 287.403 116.436C290.225 117.96 292.488 119.684 294.19 121.611C295.893 123.493 297.237 125.8 298.223 128.533C299.253 131.266 299.768 134.38 299.768 137.875C299.768 141.773 299.141 145.29 297.887 148.426C296.677 151.562 294.997 154.206 292.846 156.356C290.696 158.507 288.097 160.321 285.05 161.8C282.004 163.234 278.733 164.286 275.238 164.958C271.744 165.586 267.98 165.899 263.948 165.899C256.51 165.899 249.319 164.779 242.375 162.539C235.43 160.254 229.18 156.983 223.625 152.727L230.681 139.488C233.504 142.4 238.096 145.223 244.458 147.956C250.865 150.644 257.451 151.988 264.217 151.988C270.31 151.988 274.992 150.913 278.263 148.762C281.578 146.611 283.236 143.52 283.236 139.488C283.236 137.875 282.989 136.419 282.497 135.119C282.004 133.82 281.152 132.655 279.943 131.625C278.733 130.549 277.479 129.631 276.179 128.869C274.925 128.108 273.11 127.323 270.736 126.517C268.406 125.711 266.255 125.039 264.284 124.501C262.357 123.963 259.736 123.269 256.421 122.417C251.268 121.073 247.012 119.707 243.652 118.318C240.292 116.929 237.312 115.227 234.714 113.21C232.16 111.194 230.3 108.82 229.135 106.087C227.971 103.309 227.388 100.038 227.388 96.2746C227.388 91.5702 228.284 87.3363 230.076 83.5728C231.913 79.7645 234.422 76.6282 237.603 74.164C240.784 71.6998 244.525 69.8181 248.827 68.5188C253.128 67.2195 257.81 66.5698 262.873 66.5698C274.88 66.5698 285.879 70.2437 295.87 77.5915L288.881 90.2261ZM311.096 164.958V67.2419H326.486V164.958H311.096ZM402.265 90.2261C400.159 87.8963 396.575 85.6562 391.512 83.5056C386.449 81.355 381.229 80.2797 375.853 80.2797C369.491 80.2797 364.719 81.467 361.538 83.8416C358.402 86.1714 356.834 89.4869 356.834 93.788C356.834 95.1321 357.013 96.3418 357.371 97.4171C357.73 98.4924 358.178 99.4557 358.715 100.307C359.298 101.113 360.149 101.897 361.269 102.659C362.389 103.421 363.487 104.07 364.562 104.608C365.682 105.146 367.183 105.728 369.065 106.355C370.992 106.938 372.784 107.453 374.442 107.901C376.099 108.304 378.272 108.842 380.96 109.514C385.262 110.545 388.935 111.575 391.982 112.606C395.029 113.591 397.963 114.868 400.786 116.436C403.609 117.96 405.871 119.684 407.574 121.611C409.276 123.493 410.62 125.8 411.606 128.533C412.637 131.266 413.152 134.38 413.152 137.875C413.152 141.773 412.525 145.29 411.27 148.426C410.06 151.562 408.38 154.206 406.23 156.356C404.079 158.507 401.481 160.321 398.434 161.8C395.387 163.234 392.117 164.286 388.622 164.958C385.127 165.586 381.364 165.899 377.331 165.899C369.894 165.899 362.703 164.779 355.758 162.539C348.814 160.254 342.564 156.983 337.008 152.727L344.065 139.488C346.887 142.4 351.48 145.223 357.842 147.956C364.249 150.644 370.835 151.988 377.6 151.988C383.693 151.988 388.375 150.913 391.646 148.762C394.962 146.611 396.619 143.52 396.619 139.488C396.619 137.875 396.373 136.419 395.88 135.119C395.387 133.82 394.536 132.655 393.326 131.625C392.117 130.549 390.862 129.631 389.563 128.869C388.308 128.108 386.494 127.323 384.119 126.517C381.789 125.711 379.639 125.039 377.667 124.501C375.741 123.963 373.12 123.269 369.804 122.417C364.652 121.073 360.396 119.707 357.035 118.318C353.675 116.929 350.696 115.227 348.097 113.21C345.543 111.194 343.684 108.82 342.519 106.087C341.354 103.309 340.772 100.038 340.772 96.2746C340.772 91.5702 341.668 87.3363 343.46 83.5728C345.297 79.7645 347.806 76.6282 350.987 74.164C354.168 71.6998 357.909 69.8181 362.21 68.5188C366.511 67.2195 371.193 66.5698 376.256 66.5698C388.263 66.5698 399.263 70.2437 409.254 77.5915L402.265 90.2261ZM472.397 115.563H505.798V164.958H492.895V152.862C484.786 161.374 475.287 165.631 464.4 165.631C458.083 165.631 452.057 164.264 446.322 161.531C440.632 158.798 435.748 155.169 431.671 150.644C427.639 146.119 424.413 140.809 421.993 134.716C419.619 128.578 418.431 122.238 418.431 115.697C418.431 109.156 419.619 102.883 421.993 96.8795C424.368 90.831 427.571 85.589 431.604 81.1534C435.681 76.7178 440.609 73.1784 446.389 70.535C452.213 67.8915 458.396 66.5698 464.938 66.5698C473.943 66.5698 481.649 68.474 488.056 72.2823C494.508 76.0458 499.414 81.243 502.774 87.8739L490.946 96.409C488.303 91.2118 484.674 87.2691 480.059 84.5809C475.444 81.8479 470.269 80.4813 464.534 80.4813C458.575 80.4813 453.244 82.1615 448.539 85.5217C443.835 88.8372 440.251 93.2056 437.787 98.6268C435.322 104.003 434.09 109.828 434.09 116.1C434.09 126.002 437.115 134.425 443.163 141.369C449.212 148.269 456.694 151.719 465.61 151.719C475.735 151.719 484.83 146.947 492.895 137.404V126.987H472.397V115.563ZM586.281 151.316V164.958H519.277V67.2419H585.071V80.8846H534.667V108.64H578.283V121.477H534.667V151.316H586.281ZM680.204 164.958V95.3337L651.441 148.157H642.368L613.47 95.3337V164.958H598.08V67.2419H614.612L646.938 126.987L679.264 67.2419H695.796V164.958H680.204Z" fill="white"/>
                        <path d="M9.06274 89.1167L9.06275 153.764" stroke="white" stroke-width="18.1254"/>
                        <path d="M178.233 94.8564L178.233 139.566" stroke="white" stroke-width="18.1254"/>
                        <path d="M33.23 50.1472L33.23 190.921" stroke="white" stroke-width="18.1254"/>
                        <path d="M154.066 61.6267L154.066 174.608" stroke="white" stroke-width="18.1254"/>
                        <path d="M57.397 40.4802L57.397 203.609" stroke="white" stroke-width="18.1254"/>
                        <path d="M105.731 50.1472L105.731 203.609" stroke="white" stroke-width="18.1254"/>
                        <path d="M129.899 40.4802L129.899 199.379" stroke="white" stroke-width="18.1254"/>
                        <path d="M81.5645 53.168L81.5645 196.358" stroke="white" stroke-width="18.1254"/>
                        <path d="M103.143 12.286C94.8605 20.5039 93.186 40.4544 93.186 40.4544C93.186 40.4544 114.642 39.4096 123.246 30.5333C131.224 22.3021 140.947 -2.67399e-05 140.947 -2.67399e-05C140.947 -2.67399e-05 111.736 3.7597 103.143 12.286Z" fill="white"/>
                    </svg>
                </div>
                <a class="login" href="{{ route('login') }}">Login</a>
            </div>

            <div class="hero-title">
                <p class="hero-eyebrow">Cardápio de Merenda</p>
                <h1 id="hero-day-name">
                    {{ $dias->isNotEmpty() ? $dias[$indiceAtivo]['nome_dia'] : 'Sem Cardápio' }}
                </h1>
                <p class="hero-date" id="hero-day-date">
                    @if($dias->isNotEmpty())
                        {{ $dias[$indiceAtivo]['data']->translatedFormat('d \d\e F \d\e Y') }}
                    @endif
                </p>
            </div>
        </div>
    </header>

    @if($dias->isNotEmpty())
        <nav class="datestrip" aria-label="Selecionar dia">
            @foreach($dias as $index => $dia)
                <button class="day {{ $index === $indiceAtivo ? 'day--active' : '' }} {{ !$dia['possui_cardapio'] ? 'day--empty' : '' }}"
                        onclick="selectDay({{ $index }})"
                        id="btn-day-{{ $index }}"
                        data-name="{{ $dia['nome_dia'] }}"
                        data-date="{{ $dia['data']->translatedFormat('d \d\e F \d\e Y') }}">
                    <span class="day-wd">{{ mb_substr($dia['nome_dia'], 0, 3, 'UTF-8') }}</span>
                    <span class="day-n">{{ $dia['data']->format('d') }}</span>
                </button>
            @endforeach
        </nav>
    @endif

    @if($dias->isEmpty())
        <div style="padding: 60px 20px; text-align: center; color: var(--muted); margin: 0 auto; width: 90%;">
            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-calendar-x mb-4 opacity-50" viewBox="0 0 16 16"><path d="M6.146 7.146a.5.5 0 0 1 .708 0L8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 0 1 0-.708z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
            <p style="font-size: 1.1rem;">Nenhum cardápio disponível no momento.</p>
        </div>
    @else
        @foreach($dias as $index => $dia)
            <div id="panel-day-{{ $index }}" class="day-panel {{ $index === $indiceAtivo ? 'active' : '' }}">

                @if(!$dia['possui_cardapio'])
                    <div style="padding: 40px; text-align: center; color: #a96a0a; margin: 40px auto; width: calc(100% - 32px); max-width: 300px; border: 1px solid orange; border-radius: 8px; font-size: 14px; font-weight: 600; background-color: #fffdf5; box-sizing: border-box;">
                        ⚠️ Sem previsão de cardápio.
                    </div>
                @else
                    <section class="menu">
                        @foreach($dia['horarios'] as $hIndex => $horario)
                            <article class="period p-{{ $hIndex % 4 }}">
                                <div class="period-head">
                                        <span class="period-ico">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16"><path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/></svg>
                                        </span>
                                    <div class="period-meta">
                                        <h2>{{ $horario['nome'] }}</h2>
                                    </div>
                                    <span class="period-time">{{ substr($horario['hora_inicio'], 0, 5) }}–{{ substr($horario['hora_fim'], 0, 5) }}</span>
                                </div>

                                <div class="row-wrap">
                                    <div class="row" tabindex="0">

                                        @foreach($horario['itens'] as $iIndex => $item)
                                            @php $estilo = getEstiloAlimento($item['nome']); @endphp

                                            <div class="item" style="--c:{{ $estilo['c'] }};--t:{{ $estilo['t'] }}">
                                                @if($item['origem'] !== 'padrao')
                                                    <div class="item-badge-excecao">Extra</div>
                                                @endif

                                                <span class="item-ico">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                        {!! $estilo['svg'] !!}
                                                    </svg>
                                                </span>
                                                <span class="item-name">{{ $item['nome'] }}</span>

                                                <span class="item-cat">
                                                    {{ $estilo['cat'] }}
                                                    @if($item['quantidade_estimada_porcao'])
                                                        · {{ number_format($item['quantidade_estimada_porcao'], 2, ',', '') }}
                                                    @endif
                                                </span>
                                            </div>
                                        @endforeach

                                    </div>
                                    <span class="row-fade" aria-hidden="true"></span>
                                    <button class="row-scroll" type="button" aria-label="Ver mais itens">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    </section>
                @endif
            </div>
        @endforeach
    @endif

    <footer class="foot d-flex align-items-center justify-content-center">
{{--        {{ env('IFRS_CAMPUS', 'Campus') }} · SISGEM--}}
        <img src="{{ asset('assets/img/logo_ifrs.png') }}" alt="Logo IFRS" style="height: 50px; margin-left: 15px;">
    </footer>
</main>

<script>
    function selectDay(index) {
        document.querySelectorAll('.day').forEach(btn => btn.classList.remove('day--active'));
        const btnClicado = document.getElementById('btn-day-' + index);
        if(btnClicado) btnClicado.classList.add('day--active');

        if(btnClicado) {
            document.getElementById('hero-day-name').innerText = btnClicado.getAttribute('data-name');
            document.getElementById('hero-day-date').innerText = btnClicado.getAttribute('data-date');
        }

        document.querySelectorAll('.day-panel').forEach(panel => panel.classList.remove('active'));
        const panelAtivo = document.getElementById('panel-day-' + index);
        if(panelAtivo) {
            panelAtivo.classList.add('active');
            setTimeout(() => {
                panelAtivo.querySelectorAll('.row-wrap').forEach(setupScroll);
            }, 50);
        }
    }

    function setupScroll(wrap) {
        var row = wrap.querySelector('.row');
        var btn = wrap.querySelector('.row-scroll');
        if (!row || !btn) return;

        function step() {
            var card = row.querySelector('.item');
            return card ? card.getBoundingClientRect().width + 16 : 160;
        }

        function maxScroll() { return row.scrollWidth - row.clientWidth; }
        function atEnd() { return row.scrollLeft >= maxScroll() - 2; }

        function update() {
            if (maxScroll() <= 0) {
                wrap.classList.add('at-end');
                btn.style.display = 'none';
            } else {
                btn.style.display = 'flex';
                wrap.classList.toggle('at-end', atEnd());
            }
        }

        btn.replaceWith(btn.cloneNode(true));
        btn = wrap.querySelector('.row-scroll');

        btn.addEventListener('click', function () {
            wrap.classList.add('interacted');
            if (atEnd()) {
                row.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                row.scrollBy({ left: step(), behavior: 'smooth' });
            }
        });

        row.addEventListener('pointerdown', function () { wrap.classList.add('interacted'); }, { passive: true });
        row.addEventListener('scroll', update, { passive: true });

        update();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.day-panel.active .row-wrap').forEach(setupScroll);
        window.addEventListener('resize', () => {
            document.querySelectorAll('.day-panel.active .row-wrap').forEach(setupScroll);
        });
    });
</script>
</body>
</html>
