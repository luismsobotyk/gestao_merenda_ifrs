<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Cardápio de Merenda · {{ env('APP_NAME', 'IFRS') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ============================================================
           FONTE RAWLINE (Padrão Gov.br)
           ============================================================ */
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

        /* ============================================================
           VARIÁVEIS GLOBAIS E ESTILOS BASE
           ============================================================ */
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

        /* ---------- App shell (Ocupa a tela inteira) ---------- */
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

        /* Contêiner para centralizar os limites em monitores muito largos */
        .container-app {
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
        }

        /* ===================== HERO ===================== */
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

        /* ===================== DATE STRIP ===================== */
        .datestrip {
            display: flex; gap: 9px; overflow-x: auto;
            padding: 18px 22px 15px;
            scrollbar-width: none;
            border-bottom: 1px solid var(--hairline);
            justify-content: flex-start; /* Alinhamento padrão (mobile) */
        }
        .datestrip::-webkit-scrollbar { display: none; }

        @media (min-width: 768px) {
            .datestrip {
                justify-content: center; /* Centraliza as abas no Desktop */
            }
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

        /* ===================== MENU CONTENT ===================== */
        .day-panel { display: none; flex: 1; overflow-y: auto; padding-bottom: 20px;}
        .day-panel.active { display: block; animation: fadeIn .3s ease; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

        /* O conteúdo do cardápio centralizado e um abaixo do outro */
        .menu {
            padding: 30px 20px 8px;
            display: flex;
            flex-direction: column;
            gap: 30px;
            max-width: 700px;
            margin: 0 auto; /* Centraliza a coluna principal */
            width: 100%;
        }

        .period { width: 100%; }

        /* Cores dinâmicas para os períodos */
        .p-0 { --accent: #E08A2B; --accent-tint: #FBEFDD; --accent-ink: #9A5E14; }
        .p-1 { --accent: #1F9E8C; --accent-tint: #E2F3F0; --accent-ink: #0E5F54; }
        .p-2 { --accent: #5A57D1; --accent-tint: #ECEBFB; --accent-ink: #3B399A; }
        .p-3 { --accent: #D9483B; --accent-tint: #FBEAE8; --accent-ink: #9E2B21; }

        .period-head {
            display: flex;
            align-items: center;
            justify-content: center; /* Cabeçalho do turno centralizado */
            gap: 15px;
            margin-bottom: 20px;
        }
        .period-ico {
            width: 44px; height: 44px; border-radius: 13px; flex: 0 0 auto;
            display: flex; align-items: center; justify-content: center;
            background: var(--accent); color: #fff;
            box-shadow: 0 10px 18px -8px color-mix(in srgb, var(--accent) 70%, transparent);
        }
        .period-meta { text-align: left; }
        .period-meta h2 { margin: 0; font-size: 20px; font-weight: 800; letter-spacing: -.01em; }
        .period-meta p  { margin: 0; font-size: 12px; font-weight: 600; color: var(--muted); }
        .period-time {
            font-size: 13px; font-weight: 800; white-space: nowrap;
            color: var(--accent-ink); background: var(--accent-tint);
            padding: 6px 12px; border-radius: 999px;
            margin-left: auto; /* Empurra o horário para a direita se houver espaço, ou centraliza */
        }

        @media (max-width: 450px) {
            .period-head { flex-direction: column; text-align: center; gap: 8px;}
            .period-meta { text-align: center; }
            .period-time { margin-left: 0; margin-top: 5px; }
        }

        /* ---------- Horizontal scroller (Centralizado) ---------- */
        .row-wrap {
            position: relative;
            display: flex;
            justify-content: center; /* Centraliza a fila de alimentos sempre (desktop e mobile) */
        }
        .row {
            display: inline-flex;
            gap: 16px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding: 4px 4px 12px;
            max-width: 100%;
        }
        .row::-webkit-scrollbar { display: none; }

        .item {
            flex: 0 0 150px; scroll-snap-align: start;
            background: var(--card); border: 1px solid var(--hairline); border-radius: 22px;
            padding: 20px 12px; text-align: center; cursor: pointer;
            box-shadow: 0 8px 24px -16px rgba(0,0,0,.15);
            transition: transform .18s ease, box-shadow .18s ease;
            position: relative;
            overflow: hidden;
        }
        .item:hover { transform: translateY(-3px); box-shadow: 0 12px 28px -16px rgba(0,0,0,.25); }

        /* Faixa de Exceção */
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

        /* right-edge fade hint */
        .row-fade {
            position: absolute; top: 0; right: 0; bottom: 8px; width: 50px;
            pointer-events: none; border-radius: 0 22px 22px 0;
            background: linear-gradient(to right, rgba(244,246,244,0), var(--surface) 85%);
            opacity: 1; transition: opacity .25s ease;
        }
        .row-wrap.at-end .row-fade { opacity: 0; }

        /* click-to-scroll affordance */
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

        /* ===================== FOOTER ===================== */
        .foot {
            text-align: center; font-size: 13px; font-weight: 600; color: var(--muted);
            padding: 30px 0 max(30px, env(safe-area-inset-bottom));
            margin-top: auto;
        }
    </style>
</head>
<body>
<main class="app">

    <header class="hero">
        <div class="hero-blob hero-blob--a"></div>
        <div class="hero-blob hero-blob--b"></div>

        <div class="container-app">
            <div class="appbar">
                <div class="brand">
                    <span class="brand-mark">IF</span>
                    <span class="brand-name">SISGEM · {{ env('IFRS_CAMPUS', 'Campus') }}</span>
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
                <button class="day {{ $index === $indiceAtivo ? 'day--active' : '' }}"
                        onclick="selectDay({{ $index }})"
                        id="btn-day-{{ $index }}"
                        data-name="{{ $dia['nome_dia'] }}"
                        data-date="{{ $dia['data']->translatedFormat('d \d\e F \d\e Y') }}">
                    <span class="day-wd">{{ substr($dia['nome_dia'], 0, 3) }}</span>
                    <span class="day-n">{{ $dia['data']->format('d') }}</span>
                </button>
            @endforeach
        </nav>
    @endif

    @if($dias->isEmpty())
        <div style="padding: 60px 20px; text-align: center; color: var(--muted); margin: 0 auto;">
            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-calendar-x mb-4 opacity-50" viewBox="0 0 16 16"><path d="M6.146 7.146a.5.5 0 0 1 .708 0L8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 0 1 0-.708z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
            <p style="font-size: 1.1rem;">Nenhum cardápio disponível no momento.</p>
        </div>
    @else
        @foreach($dias as $index => $dia)
            <div id="panel-day-{{ $index }}" class="day-panel {{ $index === $indiceAtivo ? 'active' : '' }}">

                @if(!$dia['possui_cardapio'])
                    <div style="padding: 60px 20px; text-align: center; color: var(--muted); margin: 0 auto;">
                        Não há cardápio cadastrado para esta data.
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
                                        <p>{{ $horario['descricao_publico'] ?: 'Geral' }}</p>
                                    </div>
                                    <span class="period-time">{{ substr($horario['hora_inicio'], 0, 5) }}–{{ substr($horario['hora_fim'], 0, 5) }}</span>
                                </div>

                                <div class="row-wrap">
                                    <div class="row" tabindex="0">
                                        @php
                                            $paletaItens = [
                                                ['c' => '#2F76C9', 't' => '#E7F0FB'], // Azul
                                                ['c' => '#D9483B', 't' => '#FBEAE8'], // Vermelho
                                                ['c' => '#C99016', 't' => '#FBF1D5'], // Amarelo
                                                ['c' => '#1F9E8C', 't' => '#E2F3F0'], // Verde Água
                                                ['c' => '#8E44AD', 't' => '#F5EEF8']  // Roxo
                                            ];
                                        @endphp

                                        @foreach($horario['itens'] as $iIndex => $item)
                                            @php $cor = $paletaItens[$iIndex % count($paletaItens)]; @endphp

                                            <div class="item" style="--c:{{ $cor['c'] }};--t:{{ $cor['t'] }}">
                                                @if($item['origem'] !== 'padrao')
                                                    <div class="item-badge-excecao">Extra</div>
                                                @endif

                                                <span class="item-ico">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16"><path d="M8 2a2 2 0 0 0-2 2v2h4V4a2 2 0 0 0-2-2m4 4v1.5a.5.5 0 0 1-1 0V6H5v1.5a.5.5 0 0 1-1 0V6H2.5A1.5 1.5 0 0 0 1 7.5v6A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6A1.5 1.5 0 0 0 13.5 6z"/></svg>
                                                    </span>
                                                <span class="item-name">{{ $item['nome'] }}</span>
                                                @if($item['quantidade_estimada_porcao'])
                                                    <span class="item-cat">Porção: {{ number_format($item['quantidade_estimada_porcao'], 2, ',', '') }}</span>
                                                @endif
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

    <footer class="foot">IFRS · SISGEM — {{ env('IFRS_CAMPUS', 'Campus') }}</footer>
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
