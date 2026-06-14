<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SISGEM — Avaliação de Usabilidade</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700&family=Literata:ital,wght@0,300;0,400;0,500;1,300;1,400&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --green-900: #0D2B1A;
            --green-800: #14422A;
            --green-700: #1B5C3B;
            --green-600: #22754C;
            --green-500: #2A9060;
            --green-400: #3DAE77;
            --green-200: #A8DFC0;
            --green-100: #D4EFE0;
            --green-50: #EEF8F3;

            --amber-600: #A05C00;
            --amber-100: #FEF3DC;

            --red-600: #9B2020;
            --red-100: #FDEAEA;
            --red-50: #FFF5F5;

            --text: #0F1F16;
            --muted: #4A6358;
            --faint: #8AA898;
            --bg: #F3F7F4;
            --surface: #FFFFFF;
            --surface2: #EDF4EF;
            --border: #D5E5DB;
            --border2: #B0CEBC;

            --radius: 10px;
            --radius-lg: 16px;
            --shadow: 0 1px 4px rgba(15,31,22,.06), 0 6px 20px rgba(15,31,22,.07);
            --shadow-lg: 0 2px 8px rgba(15,31,22,.08), 0 12px 40px rgba(15,31,22,.1);
        }

        html {
            font-size: 15px;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Literata', Georgia, serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            line-height: 1.65;
        }

        .layout {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
        }

        .sidebar {
            background: var(--green-900);
            color: #fff;
            min-height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 1.75rem 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 4px;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--green-500);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.02em;
        }

        .brand-sub {
            font-size: .7rem;
            color: rgba(255,255,255,.35);
            letter-spacing: .12em;
            text-transform: uppercase;
            font-family: 'Syne', sans-serif;
            margin-top: 2px;
        }

        .nav-group {
            padding: .75rem 0;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }

        .nav-label {
            padding: .75rem 1.5rem .3rem;
            font-family: 'Syne', sans-serif;
            font-size: .64rem;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: rgba(255,255,255,.25);
            font-weight: 600;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: .65rem 1.5rem;
            font-family: 'Syne', sans-serif;
            font-size: .86rem;
            font-weight: 500;
            color: rgba(255,255,255,.58);
            border-left: 3px solid transparent;
            text-decoration: none;
            cursor: pointer;
            transition: all .15s;
        }

        .nav-item:hover {
            color: #fff;
            background: rgba(255,255,255,.05);
        }

        .nav-item.active {
            color: #fff;
            border-left-color: var(--green-400);
            background: rgba(61,174,119,.12);
        }

        .nav-icon {
            font-size: .95rem;
            width: 18px;
            text-align: center;
            opacity: .8;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,.08);
        }

        .save-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .72rem;
            color: rgba(255,255,255,.45);
            font-family: 'Syne', sans-serif;
        }

        .save-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--green-400);
            animation: pulse 2.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .3; }
        }

        .content-wrap {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: .9rem 2.5rem;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .topbar-title {
            font-family: 'Syne', sans-serif;
            font-size: .95rem;
            font-weight: 600;
            color: var(--text);
            flex: 1;
        }

        .topbar-actions {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .main {
            width: min(100%, 960px);
            padding: 2rem 2.5rem;
        }

        .page-header {
            margin-bottom: 1.75rem;
        }

        .page-header h1 {
            font-family: 'Syne', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -.03em;
            line-height: 1.2;
        }

        .page-header p {
            color: var(--muted);
            font-size: .9rem;
            margin-top: .45rem;
            font-style: italic;
        }

        .panel {
            display: none;
            animation: fadeUp .2s ease;
        }

        .panel.active {
            display: block;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: .875rem;
            box-shadow: var(--shadow);
        }

        .card-sm {
            padding: 1rem 1.25rem;
        }

        .card-title {
            font-family: 'Syne', sans-serif;
            font-size: .68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: var(--faint);
            margin-bottom: 1rem;
        }

        .notice {
            background: var(--green-50);
            border: 1px solid var(--green-200);
            border-radius: var(--radius);
            padding: 1rem 1.25rem;
            font-size: .9rem;
            color: var(--green-800);
            margin-bottom: 1.25rem;
            line-height: 1.7;
        }

        .notice-warning {
            background: var(--amber-100);
            border-color: #F2C36B;
            color: #6E3F00;
        }

        .notice-danger {
            background: var(--red-50);
            border-color: var(--red-100);
            color: var(--red-600);
        }

        .sus-question {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: grid;
            grid-template-columns: 42px minmax(0, 1fr) auto auto;
            gap: 1rem;
            align-items: center;
        }

        .sus-question:last-child {
            border-bottom: none;
        }

        .sus-q-num {
            font-family: 'Syne', sans-serif;
            font-size: .72rem;
            font-weight: 700;
            color: var(--faint);
            flex-shrink: 0;
        }

        .sus-q-text {
            font-size: .9rem;
            line-height: 1.55;
        }

        .sus-q-type {
            font-family: 'Syne', sans-serif;
            font-size: .63rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 4px;
            flex-shrink: 0;
            letter-spacing: .04em;
            white-space: nowrap;
        }

        .type-pos {
            background: var(--green-100);
            color: var(--green-700);
        }

        .type-neg {
            background: var(--red-100);
            color: var(--red-600);
        }

        .sus-scale {
            display: flex;
            gap: 3px;
            flex-shrink: 0;
        }

        .sus-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--surface);
            font-family: 'Syne', sans-serif;
            font-size: .78rem;
            font-weight: 600;
            color: var(--muted);
            cursor: pointer;
            transition: all .15s;
        }

        .sus-btn:hover {
            border-color: var(--green-500);
            color: var(--green-600);
        }

        .sus-btn.sel {
            background: var(--green-700);
            border-color: var(--green-700);
            color: #fff;
        }

        .sus-btn:disabled {
            cursor: not-allowed;
            opacity: .75;
        }

        .scale-legend {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: .65rem 1.5rem;
            font-family: 'Syne', sans-serif;
            font-size: .68rem;
            color: var(--faint);
            font-weight: 600;
            border-bottom: 1px solid var(--border);
            background: var(--bg);
        }

        .score-wrap {
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: 1rem;
            margin-top: 1.25rem;
        }

        .score-card {
            background: var(--green-900);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            text-align: center;
            color: #fff;
        }

        .score-num {
            font-family: 'Syne', sans-serif;
            font-size: 3.5rem;
            font-weight: 700;
            color: #fff;
            line-height: 1;
            letter-spacing: -.04em;
        }

        .score-lbl {
            font-size: .65rem;
            color: rgba(255,255,255,.4);
            margin-top: .3rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            font-family: 'Syne', sans-serif;
        }

        .score-grade {
            font-family: 'Syne', sans-serif;
            font-size: .88rem;
            font-weight: 600;
            margin-top: .6rem;
        }

        .score-bar-bg {
            background: rgba(255,255,255,.1);
            border-radius: 100px;
            height: 4px;
            margin-top: .5rem;
        }

        .score-bar {
            height: 4px;
            border-radius: 100px;
            background: var(--green-400);
            transition: width .7s ease;
        }

        .sus-ref {
            border-collapse: collapse;
            width: 100%;
        }

        .sus-ref th {
            text-align: left;
            padding: .4rem 0;
            border-bottom: 1px solid var(--border);
            font-family: 'Syne', sans-serif;
            font-size: .65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .09em;
            color: var(--faint);
        }

        .sus-ref td {
            padding: .45rem 0;
            border-bottom: 1px solid var(--border);
            font-size: .82rem;
            color: var(--muted);
        }

        .sus-ref td:first-child {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            color: var(--text);
        }

        .sus-ref tr:last-child td {
            border-bottom: none;
        }

        .sus-hl {
            background: var(--green-50);
        }

        .sus-hl td {
            color: var(--green-700) !important;
            font-weight: 700 !important;
        }

        .qual-q {
            margin-bottom: 1rem;
        }

        .qual-q:last-child {
            margin-bottom: 0;
        }

        .qual-q label {
            display: block;
            font-family: 'Syne', sans-serif;
            font-size: .78rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: .35rem;
        }

        .form-control {
            padding: .7rem .9rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-family: 'Literata', serif;
            font-size: .9rem;
            color: var(--text);
            background: var(--surface);
            transition: border .15s, box-shadow .15s;
            width: 100%;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--green-500);
            box-shadow: 0 0 0 3px rgba(42,144,96,.12);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 75px;
            line-height: 1.65;
        }

        .form-control:disabled {
            cursor: not-allowed;
            background: var(--surface2);
            color: var(--muted);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            padding: .58rem 1.1rem;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            background: var(--surface);
            font-family: 'Syne', sans-serif;
            font-size: .8rem;
            font-weight: 600;
            color: var(--text);
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
            min-height: 38px;
        }

        .btn:hover {
            background: var(--surface2);
            border-color: var(--border2);
        }

        .btn-primary {
            background: var(--green-700);
            color: #fff;
            border-color: var(--green-700);
        }

        .btn-primary:hover {
            background: var(--green-800);
        }

        .btn-accent {
            background: var(--green-500);
            color: #fff;
            border-color: var(--green-500);
        }

        .btn-accent:hover {
            background: var(--green-600);
        }

        .btn:disabled {
            cursor: not-allowed;
            opacity: .7;
        }

        .btn-group {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            margin-top: 1.25rem;
        }

        .progress-line {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-top: 1rem;
        }

        .progress-bg {
            flex: 1;
            height: 8px;
            border-radius: 999px;
            background: var(--surface2);
            overflow: hidden;
        }

        .progress-bar {
            height: 8px;
            width: 0;
            background: var(--green-500);
            border-radius: 999px;
            transition: width .25s ease;
        }

        .progress-text {
            font-family: 'Syne', sans-serif;
            font-size: .72rem;
            font-weight: 600;
            color: var(--muted);
            min-width: 120px;
            text-align: right;
        }

        .toast {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            background: var(--green-900);
            color: #fff;
            padding: .75rem 1.25rem;
            border-radius: var(--radius);
            font-family: 'Syne', sans-serif;
            font-size: .82rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .5rem;
            opacity: 0;
            transform: translateY(8px);
            transition: all .25s;
            pointer-events: none;
            z-index: 9999;
            max-width: 360px;
            box-shadow: var(--shadow-lg);
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 900px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                min-height: auto;
                position: static;
            }

            .sidebar-footer {
                display: none;
            }

            .nav-group {
                display: flex;
                padding: .5rem;
                gap: .5rem;
                border-bottom: none;
            }

            .nav-label {
                display: none;
            }

            .nav-item {
                flex: 1;
                border-left: none;
                border-radius: var(--radius);
                padding: .7rem .9rem;
                justify-content: center;
            }

            .topbar {
                padding: .8rem 1rem;
                position: static;
            }

            .main {
                padding: 1rem;
                width: 100%;
            }

            .score-wrap {
                grid-template-columns: 1fr;
            }

            .sus-question {
                grid-template-columns: 1fr;
                gap: .6rem;
            }

            .sus-q-type {
                width: fit-content;
            }

            .sus-scale {
                justify-content: space-between;
            }

            .sus-btn {
                flex: 1;
            }
        }
    </style>
</head>
<body>
@php
    $submitted = (bool) ($avaliacao->submitted_at ?? null);
@endphp

<div class="layout">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">
                <div class="brand-icon">🍎</div>
                <span class="brand-name">SISGEM</span>
            </div>
            <div class="brand-sub">Avaliação de Usabilidade</div>
        </div>

        <div class="nav-group">
            <div class="nav-label">Formulário</div>
            <a class="nav-item active" href="#" onclick="goTo('sus'); return false;" data-panel="sus">
                <span class="nav-icon">◉</span>
                Formulário SUS
            </a>
            <a class="nav-item" href="#" onclick="goTo('qual'); return false;" data-panel="qual">
                <span class="nav-icon">◐</span>
                Questões abertas
            </a>
        </div>

        @if($isAdmin ?? false)
            <div class="nav-group">
                <div class="nav-label">Administração</div>
                <a class="nav-item" href="{{ route('avaliacao.respostas') }}">
                    <span class="nav-icon">⊞</span>
                    Ver respostas
                </a>
            </div>
        @endif

        <div class="sidebar-footer">
            <div class="save-row">
                <div class="save-dot"></div>
                <span id="save-status">
                    @if($submitted)
                        Avaliação submetida
                    @else
                        Autosave ativo
                    @endif
                </span>
            </div>
            <div style="font-size:.65rem;color:rgba(255,255,255,.2);margin-top:.5rem;font-family:'Syne',sans-serif">
                SUS · Brooke (1996)
            </div>
        </div>
    </aside>

    <div class="content-wrap">
        <header class="topbar">
            <span class="topbar-title" id="topbar-title">Formulário SUS</span>
            <div class="topbar-actions">
                @if($isAdmin ?? false)
                    <a class="btn" href="{{ route('avaliacao.respostas') }}">Ver respostas</a>
                @endif
                <button class="btn btn-primary" id="btn-submit" onclick="submitEvaluation()">
                    @if($submitted)
                        ✓ Avaliação submetida
                    @else
                        ✓ Submeter avaliação
                    @endif
                </button>
            </div>
        </header>

        <main class="main">
            @if($submitted)
                <div class="notice notice-warning" id="submitted-notice">
                    <strong>Avaliação já submetida.</strong> Suas respostas foram registradas e não podem mais ser alteradas.
                </div>
            @else
                <div class="notice" id="draft-notice">
                    <strong>Identificação automática:</strong> esta avaliação será vinculada ao seu usuário autenticado no sistema. Suas respostas são salvas automaticamente a cada alteração.
                </div>
            @endif

            <section id="panel-sus" class="panel active">
                <div class="page-header">
                    <h1>Formulário SUS</h1>
                    <p>System Usability Scale · Responda com base na sua experiência geral com o SISGEM.</p>
                </div>

                <div class="notice">
                    <strong>Instrução:</strong> para cada afirmação abaixo, marque um número de 1 a 5, sendo
                    <strong>1 = discordo totalmente</strong> e <strong>5 = concordo totalmente</strong>.
                    Não há respostas certas ou erradas.
                </div>

                <div class="card" style="padding:0;overflow:hidden">
                    <div class="scale-legend">
                        <span>1 — discordo totalmente</span>
                        <span>5 — concordo totalmente</span>
                    </div>
                    <div id="sus-questions"></div>
                </div>

                <div class="score-wrap">
                    <div class="score-card">
                        <div class="score-num" id="sus-score-val">—</div>
                        <div class="score-lbl">Pontuação SUS (0–100)</div>
                        <div class="score-grade" id="sus-grade" style="color:rgba(255,255,255,.4)">aguardando respostas</div>
                        <div class="score-bar-bg">
                            <div class="score-bar" id="sus-bar" style="width:0%"></div>
                        </div>
                    </div>

                    <div class="card card-sm" style="margin:0">
                        <div class="card-title" style="margin-bottom:.75rem">Referência de classificação</div>
                        <table class="sus-ref">
                            <thead>
                            <tr>
                                <th>Pontuação</th>
                                <th>Adjetivo</th>
                                <th>Aceitabilidade</th>
                            </tr>
                            </thead>
                            <tbody id="sus-ref-body"></tbody>
                        </table>
                    </div>
                </div>

                <div class="progress-line">
                    <div class="progress-bg">
                        <div class="progress-bar" id="progress-bar"></div>
                    </div>
                    <div class="progress-text" id="progress-text">0% preenchido</div>
                </div>

                <div class="btn-group">
                    <button class="btn btn-primary" onclick="goTo('qual')">Questões abertas →</button>
                </div>
            </section>

            <section id="panel-qual" class="panel">
                <div class="page-header">
                    <h1>Questões abertas</h1>
                    <p>Registre livremente suas percepções sobre o uso do sistema.</p>
                </div>

                <div class="card">
                    <div class="card-title">Experiência geral</div>

                    <div class="qual-q">
                        <label for="q1">1. O que você mais gostou no sistema?</label>
                        <textarea class="form-control" id="q1" rows="3" placeholder="Digite sua resposta..."></textarea>
                    </div>

                    <div class="qual-q">
                        <label for="q2">2. O que causou mais dificuldade ou frustração?</label>
                        <textarea class="form-control" id="q2" rows="3" placeholder="Digite sua resposta..."></textarea>
                    </div>

                    <div class="qual-q">
                        <label for="q3">3. Se pudesse mudar uma coisa no sistema, o que seria?</label>
                        <textarea class="form-control" id="q3" rows="3" placeholder="Digite sua resposta..."></textarea>
                    </div>
                </div>

                <div class="card">
                    <div class="card-title">Módulos específicos</div>

                    <div class="qual-q">
                        <label for="q4">4. O processo de login foi claro e rápido?</label>
                        <textarea class="form-control" id="q4" rows="2" placeholder="Digite sua resposta..."></textarea>
                    </div>

                    <div class="qual-q">
                        <label for="q5">5. A sincronização de cursos e discentes foi intuitiva?</label>
                        <textarea class="form-control" id="q5" rows="2" placeholder="Digite sua resposta..."></textarea>
                    </div>

                    <div class="qual-q">
                        <label for="q6">6. O fluxo de cadastro e gestão de contratos/empenhos foi compreensível?</label>
                        <textarea class="form-control" id="q6" rows="2" placeholder="Digite sua resposta..."></textarea>
                    </div>

                    <div class="qual-q">
                        <label for="q7">7. A simulação de retirada de merenda refletiu o processo real da escola?</label>
                        <textarea class="form-control" id="q7" rows="2" placeholder="Digite sua resposta..."></textarea>
                    </div>

                    <div class="qual-q">
                        <label for="q8">8. Os gráficos e dados de retirada foram fáceis de interpretar?</label>
                        <textarea class="form-control" id="q8" rows="2" placeholder="Digite sua resposta..."></textarea>
                    </div>
                </div>

                <div class="card">
                    <div class="card-title">Intenção de uso e contexto institucional</div>

                    <div class="qual-q">
                        <label for="q9">9. Você confiaria neste sistema para gerenciar a merenda escolar da sua instituição?</label>
                        <textarea class="form-control" id="q9" rows="3" placeholder="Digite sua resposta..."></textarea>
                    </div>

                    <div class="qual-q">
                        <label for="q10">10. Há algo que falta no sistema para atender às necessidades reais da escola?</label>
                        <textarea class="form-control" id="q10" rows="3" placeholder="Digite sua resposta..."></textarea>
                    </div>

                    <div class="qual-q">
                        <label for="q11">11. Comentários livres ou sugestões adicionais</label>
                        <textarea class="form-control" id="q11" rows="3" placeholder="Digite sua resposta..."></textarea>
                    </div>
                </div>

                <div class="progress-line">
                    <div class="progress-bg">
                        <div class="progress-bar" id="progress-bar-2"></div>
                    </div>
                    <div class="progress-text" id="progress-text-2">0% preenchido</div>
                </div>

                <div class="btn-group">
                    <button class="btn" onclick="goTo('sus')">← Formulário SUS</button>
                    <button class="btn btn-accent" id="btn-submit-bottom" onclick="submitEvaluation()">
                        @if($submitted)
                            ✓ Avaliação submetida
                        @else
                            ✓ Submeter avaliação
                        @endif
                    </button>
                </div>
            </section>
        </main>
    </div>
</div>

<div class="toast" id="toast"></div>

<script>
    window.AVALIACAO_INICIAL = @json($avaliacao->payload ?? []);
    window.AVALIACAO_SUBMITTED = @json($submitted);
    window.AVALIACAO_SAVE_URL = @json(route('avaliacao.salvar'));
    window.AVALIACAO_SUBMIT_URL = @json(route('avaliacao.submeter'));

    const SUS_ITEMS = [
        { q: 'Eu usaria este sistema com frequência.', type: 'pos' },
        { q: 'Achei o sistema desnecessariamente complexo.', type: 'neg' },
        { q: 'Achei o sistema fácil de usar.', type: 'pos' },
        { q: 'Precisaria de apoio técnico para conseguir usar este sistema.', type: 'neg' },
        { q: 'As diversas funções do sistema estão bem integradas.', type: 'pos' },
        { q: 'Achei que havia muita inconsistência no sistema.', type: 'neg' },
        { q: 'Imagino que a maioria das pessoas aprenderia a usar este sistema rapidamente.', type: 'pos' },
        { q: 'Achei o sistema muito difícil de usar.', type: 'neg' },
        { q: 'Me senti confiante usando o sistema.', type: 'pos' },
        { q: 'Precisei aprender muitas coisas antes de conseguir usar este sistema.', type: 'neg' },
    ];

    const SUS_REF = [
        { range: '≥ 90', label: 'Excelente', accept: 'Recomendado com entusiasmo', min: 90 },
        { range: '80–89', label: 'Bom', accept: 'Aceitável', min: 80 },
        { range: '70–79', label: 'OK', accept: 'Com ressalvas', min: 70 },
        { range: '60–69', label: 'Pobre', accept: 'Abaixo do esperado', min: 60 },
        { range: '< 60', label: 'Inaceitável', accept: 'Reprojetar antes do lançamento', min: 0 },
    ];

    const PAGE_TITLES = {
        sus: 'Formulário SUS',
        qual: 'Questões abertas'
    };

    let susAns = new Array(10).fill(0);
    let saveTimer = null;
    let isSaving = false;
    let isSubmitted = Boolean(window.AVALIACAO_SUBMITTED);

    window.addEventListener('DOMContentLoaded', () => {
        renderSUS();
        renderSUSRef();
        loadDraft();
        bindAutosave();
        updateProgress();

        if (isSubmitted) {
            bloquearFormulario();
        }
    });

    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    function goTo(name) {
        document.querySelectorAll('.panel').forEach(panel => panel.classList.remove('active'));
        document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));

        document.getElementById('panel-' + name)?.classList.add('active');
        document.querySelector(`[data-panel="${name}"]`)?.classList.add('active');

        document.getElementById('topbar-title').textContent = PAGE_TITLES[name] || '';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function renderSUS() {
        document.getElementById('sus-questions').innerHTML = SUS_ITEMS.map((item, i) => `
            <div class="sus-question">
                <div class="sus-q-num">Q${i + 1}</div>
                <div class="sus-q-text">${item.q}</div>
                <span class="sus-q-type ${item.type === 'pos' ? 'type-pos' : 'type-neg'}">
                    ${item.type === 'pos' ? '+ positiva' : '− negativa'}
                </span>
                <div class="sus-scale">
                    ${[1, 2, 3, 4, 5].map(v => `
                        <button type="button" class="sus-btn" id="sus-${i}-${v}" onclick="setSUS(${i}, ${v})">${v}</button>
                    `).join('')}
                </div>
            </div>
        `).join('');
    }

    function renderSUSRef() {
        document.getElementById('sus-ref-body').innerHTML = SUS_REF.map(r => `
            <tr id="ref-${r.min}">
                <td>${r.range}</td>
                <td>${r.label}</td>
                <td>${r.accept}</td>
            </tr>
        `).join('');
    }

    function setSUS(index, value, shouldSave = true) {
        if (isSubmitted) return;

        susAns[index] = value;

        for (let v = 1; v <= 5; v++) {
            document.getElementById(`sus-${index}-${v}`)?.classList.toggle('sel', v === value);
        }

        calcSUS();
        updateProgress();

        if (shouldSave) {
            autoSave();
        }
    }

    function calcSUS() {
        if (susAns.some(v => Number(v) === 0)) {
            document.getElementById('sus-score-val').textContent = '—';
            document.getElementById('sus-grade').textContent = 'aguardando respostas';
            document.getElementById('sus-grade').style.color = 'rgba(255,255,255,.4)';
            document.getElementById('sus-bar').style.width = '0%';
            document.querySelectorAll('#sus-ref-body tr').forEach(tr => tr.classList.remove('sus-hl'));
            return null;
        }

        let sum = 0;

        for (let i = 0; i < 10; i++) {
            const value = Number(susAns[i]);
            sum += i % 2 === 0 ? value - 1 : 5 - value;
        }

        const score = Math.round(sum * 2.5);

        document.getElementById('sus-score-val').textContent = score;
        document.getElementById('sus-bar').style.width = score + '%';

        const info = scoreInfo(score);
        const grade = document.getElementById('sus-grade');
        grade.textContent = info.label;
        grade.style.color = info.color;

        highlightRef(score);

        return score;
    }

    function getSUSScore() {
        if (susAns.some(v => Number(v) === 0)) {
            return null;
        }

        let sum = 0;

        for (let i = 0; i < 10; i++) {
            const value = Number(susAns[i]);
            sum += i % 2 === 0 ? value - 1 : 5 - value;
        }

        return Math.round(sum * 2.5);
    }

    function scoreInfo(score) {
        if (score >= 90) return { label: 'Excelente', color: '#3DAE77' };
        if (score >= 80) return { label: 'Bom', color: '#6FBF8A' };
        if (score >= 70) return { label: 'Aceitável', color: '#E5A000' };
        if (score >= 60) return { label: 'Pobre', color: '#D97706' };
        return { label: 'Inaceitável', color: '#EF4444' };
    }

    function highlightRef(score) {
        document.querySelectorAll('#sus-ref-body tr').forEach(tr => tr.classList.remove('sus-hl'));
        const match = SUS_REF.find(r => score >= r.min);
        if (match) {
            document.getElementById(`ref-${match.min}`)?.classList.add('sus-hl');
        }
    }

    function getFormData() {
        const getValue = id => document.getElementById(id)?.value || '';

        return {
            ts: Date.now(),
            sus: {
                respostas: [...susAns],
                score: getSUSScore()
            },
            qualitativo: Object.fromEntries(
                Array.from({ length: 11 }, (_, i) => [`q${i + 1}`, getValue(`q${i + 1}`)])
            )
        };
    }

    function loadDraft() {
        const data = window.AVALIACAO_INICIAL || {};

        if (data.sus?.respostas) {
            data.sus.respostas.forEach((value, index) => {
                if (value) {
                    setSUS(index, Number(value), false);
                }
            });
        }

        if (data.qualitativo) {
            for (let i = 1; i <= 11; i++) {
                const el = document.getElementById(`q${i}`);
                if (el && data.qualitativo[`q${i}`] !== undefined && data.qualitativo[`q${i}`] !== null) {
                    el.value = data.qualitativo[`q${i}`];
                }
            }
        }

        calcSUS();
        updateProgress();
    }

    function bindAutosave() {
        document.querySelectorAll('textarea').forEach(el => {
            el.addEventListener('input', () => {
                updateProgress();
                autoSave();
            });
            el.addEventListener('change', () => {
                updateProgress();
                autoSave();
            });
        });
    }

    function autoSave() {
        if (isSubmitted) return;

        clearTimeout(saveTimer);

        saveTimer = setTimeout(() => {
            salvarRascunho();
        }, 600);
    }

    async function salvarRascunho() {
        if (isSubmitted || isSaving) return;

        isSaving = true;
        setSaveStatus('Salvando...');

        try {
            const response = await fetch(window.AVALIACAO_SAVE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                },
                body: JSON.stringify({
                    payload: getFormData()
                })
            });

            if (response.status === 423) {
                isSubmitted = true;
                bloquearFormulario();
                toast('Esta avaliação já foi submetida.');
                return;
            }

            if (!response.ok) {
                throw new Error('Erro ao salvar o rascunho.');
            }

            const data = await response.json();
            setSaveStatus('Salvo ' + (data.last_saved_at || new Date().toLocaleTimeString('pt-BR')));
        } catch (error) {
            console.error(error);
            setSaveStatus('Erro ao salvar');
            toast('Não foi possível salvar automaticamente.');
        } finally {
            isSaving = false;
        }
    }

    function validarFormularioCompletoCliente() {
        if (susAns.some(v => !v || Number(v) < 1 || Number(v) > 5)) {
            return 'Responda todas as 10 questões SUS.';
        }

        for (let i = 1; i <= 11; i++) {
            const value = document.getElementById(`q${i}`)?.value || '';
            if (!value.trim()) {
                return `Preencha a questão aberta ${i}.`;
            }
        }

        return null;
    }

    async function submitEvaluation() {
        if (isSubmitted) return;

        const erro = validarFormularioCompletoCliente();

        if (erro) {
            toast(erro);
            if (erro.includes('SUS')) {
                goTo('sus');
            } else {
                goTo('qual');
            }
            return;
        }

        const confirmacao = confirm('Deseja submeter a avaliação? Após a submissão, não será mais possível alterar as respostas.');

        if (!confirmacao) {
            return;
        }

        try {
            const response = await fetch(window.AVALIACAO_SUBMIT_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                },
                body: JSON.stringify({
                    payload: getFormData()
                })
            });

            if (response.status === 422) {
                const data = await response.json();
                const firstError = Object.values(data.errors || {})?.[0]?.[0] || 'Preencha todos os campos obrigatórios.';
                toast(firstError);
                return;
            }

            if (response.status === 423) {
                isSubmitted = true;
                bloquearFormulario();
                toast('Esta avaliação já foi submetida.');
                return;
            }

            if (!response.ok) {
                throw new Error('Erro ao submeter a avaliação.');
            }

            const data = await response.json();

            isSubmitted = true;
            bloquearFormulario();
            setSaveStatus('Submetido em ' + (data.submitted_at || ''));
            toast('Avaliação submetida com sucesso.');
        } catch (error) {
            console.error(error);
            toast('Não foi possível submeter a avaliação.');
        }
    }

    function bloquearFormulario() {
        document.querySelectorAll('textarea').forEach(el => {
            el.disabled = true;
        });

        document.querySelectorAll('.sus-btn').forEach(btn => {
            btn.disabled = true;
            btn.style.cursor = 'not-allowed';
            btn.style.opacity = '.75';
        });

        const topSubmit = document.getElementById('btn-submit');
        const bottomSubmit = document.getElementById('btn-submit-bottom');

        [topSubmit, bottomSubmit].forEach(btn => {
            if (!btn) return;
            btn.disabled = true;
            btn.textContent = '✓ Avaliação submetida';
        });

        setSaveStatus('Avaliação submetida');

        const draftNotice = document.getElementById('draft-notice');
        if (draftNotice) {
            draftNotice.className = 'notice notice-warning';
            draftNotice.innerHTML = '<strong>Avaliação já submetida.</strong> Suas respostas foram registradas e não podem mais ser alteradas.';
        }
    }

    function updateProgress() {
        const susFilled = susAns.filter(v => Number(v) >= 1 && Number(v) <= 5).length;
        let openFilled = 0;

        for (let i = 1; i <= 11; i++) {
            const value = document.getElementById(`q${i}`)?.value || '';
            if (value.trim()) {
                openFilled++;
            }
        }

        const total = 21;
        const filled = susFilled + openFilled;
        const percent = Math.round((filled / total) * 100);

        ['progress-bar', 'progress-bar-2'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.width = percent + '%';
        });

        ['progress-text', 'progress-text-2'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = `${percent}% preenchido`;
        });
    }

    function setSaveStatus(text) {
        const el = document.getElementById('save-status');
        if (el) {
            el.textContent = text;
        }
    }

    function toast(message) {
        const t = document.getElementById('toast');
        t.textContent = message;
        t.classList.add('show');

        clearTimeout(t._timer);
        t._timer = setTimeout(() => {
            t.classList.remove('show');
        }, 3200);
    }
</script>
</body>
</html>
