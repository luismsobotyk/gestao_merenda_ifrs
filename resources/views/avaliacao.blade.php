<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISGEM — Protocolo de Teste de Usabilidade</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700&family=Literata:ital,wght@0,300;0,400;0,500;1,300;1,400&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{
            --green-900:#0D2B1A;--green-800:#14422A;--green-700:#1B5C3B;
            --green-600:#22754C;--green-500:#2A9060;--green-400:#3DAE77;
            --green-200:#A8DFC0;--green-100:#D4EFE0;--green-50:#EEF8F3;
            --amber-600:#A05C00;--amber-100:#FEF3DC;--amber-50:#FFFAF0;
            --red-600:#9B2020;--red-100:#FDEAEA;--red-50:#FFF5F5;
            --blue-600:#1A4EA0;--blue-100:#E3ECFD;
            --text:#0F1F16;--muted:#4A6358;--faint:#8AA898;
            --bg:#F3F7F4;--surface:#FFFFFF;--surface2:#EDF4EF;
            --border:#D5E5DB;--border2:#B0CEBC;
            --radius:10px;--radius-lg:16px;--radius-xl:22px;
            --shadow:0 1px 4px rgba(15,31,22,.06),0 6px 20px rgba(15,31,22,.07);
            --shadow-lg:0 2px 8px rgba(15,31,22,.08),0 12px 40px rgba(15,31,22,.1);
        }
        html{font-size:15px;scroll-behavior:smooth}
        body{font-family:'Literata',Georgia,serif;background:var(--bg);color:var(--text);min-height:100vh;line-height:1.65}

        /* ── Layout ── */
        .wrap{display:flex;min-height:100vh}

        /* ── Sidebar ── */
        .sidebar{
            width:256px;flex-shrink:0;
            background:var(--green-900);
            display:flex;flex-direction:column;
            position:sticky;top:0;height:100vh;overflow-y:auto;
        }
        .sidebar-brand{
            padding:1.75rem 1.5rem 1.5rem;
            border-bottom:1px solid rgba(255,255,255,.08);
        }
        .brand-logo{
            display:flex;align-items:center;gap:10px;margin-bottom:4px;
        }
        .brand-icon{
            width:36px;height:36px;border-radius:10px;
            background:var(--green-500);
            display:flex;align-items:center;justify-content:center;
            font-size:1.1rem;flex-shrink:0;
        }
        .brand-name{
            font-family:'Syne',sans-serif;font-size:1.25rem;font-weight:700;
            color:#fff;letter-spacing:-.02em;
        }
        .brand-sub{
            font-size:.7rem;color:rgba(255,255,255,.35);
            letter-spacing:.12em;text-transform:uppercase;
            font-family:'Syne',sans-serif;margin-top:2px;
        }
        .nav-group{
            padding:.5rem 0;border-bottom:1px solid rgba(255,255,255,.06);
        }
        .nav-label{
            padding:.75rem 1.5rem .3rem;
            font-family:'Syne',sans-serif;
            font-size:.64rem;letter-spacing:.14em;text-transform:uppercase;
            color:rgba(255,255,255,.25);font-weight:600;
        }
        .nav-item{
            display:flex;align-items:center;gap:10px;
            padding:.55rem 1.5rem;
            font-family:'Syne',sans-serif;font-size:.82rem;font-weight:500;
            color:rgba(255,255,255,.55);cursor:pointer;
            border-left:3px solid transparent;
            transition:all .15s;user-select:none;
        }
        .nav-item:hover{color:#fff;background:rgba(255,255,255,.05)}
        .nav-item.active{
            color:#fff;border-left-color:var(--green-400);
            background:rgba(61,174,119,.12);
        }
        .nav-icon{font-size:.9rem;width:18px;text-align:center;opacity:.7}
        .nav-item.active .nav-icon{opacity:1}
        .nav-badge{
            margin-left:auto;font-size:.65rem;padding:1px 7px;
            border-radius:100px;background:rgba(255,255,255,.12);
            color:rgba(255,255,255,.6);font-family:'Syne',sans-serif;
        }
        .sidebar-footer{
            margin-top:auto;padding:1rem 1.5rem;
            border-top:1px solid rgba(255,255,255,.08);
        }
        .save-row{
            display:flex;align-items:center;gap:6px;
            font-size:.72rem;color:rgba(255,255,255,.35);
            font-family:'Syne',sans-serif;
        }
        .save-dot{
            width:6px;height:6px;border-radius:50%;
            background:var(--green-400);
            animation:pulse 2.5s ease-in-out infinite;
        }
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.3}}

        /* ── Topbar ── */
        .topbar{
            display:flex;align-items:center;gap:1rem;
            padding:.9rem 2.5rem;
            background:var(--surface);
            border-bottom:1px solid var(--border);
            position:sticky;top:0;z-index:20;
        }
        .topbar-title{
            font-family:'Syne',sans-serif;font-size:.95rem;font-weight:600;
            color:var(--text);flex:1;
        }
        .topbar-actions{display:flex;gap:.5rem}

        /* ── Main ── */
        .main{flex:1;padding:2rem 2.5rem;min-width:0;max-width:880px}
        .page-header{margin-bottom:1.75rem}
        .page-header h1{
            font-family:'Syne',sans-serif;font-size:1.65rem;font-weight:700;
            color:var(--text);letter-spacing:-.03em;line-height:1.2;
        }
        .page-header p{
            color:var(--muted);font-size:.875rem;margin-top:.4rem;
            font-style:italic;
        }

        /* ── Panel ── */
        .panel{display:none;animation:fadeUp .2s ease}
        .panel.active{display:block}
        @keyframes fadeUp{from{opacity:0;transform:translateY(5px)}to{opacity:1;transform:translateY(0)}}

        /* ── Cards ── */
        .card{
            background:var(--surface);border:1px solid var(--border);
            border-radius:var(--radius-lg);padding:1.5rem;
            margin-bottom:.875rem;box-shadow:var(--shadow);
        }
        .card-sm{padding:1rem 1.25rem}
        .card-title{
            font-family:'Syne',sans-serif;font-size:.68rem;font-weight:600;
            text-transform:uppercase;letter-spacing:.12em;
            color:var(--faint);margin-bottom:1rem;
        }

        /* ── Form Controls ── */
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:.75rem}
        .form-group{display:flex;flex-direction:column;gap:.3rem}
        .form-group label{font-family:'Syne',sans-serif;font-size:.72rem;font-weight:600;color:var(--muted)}
        .form-control{
            padding:.55rem .85rem;border:1px solid var(--border);
            border-radius:var(--radius);
            font-family:'Literata',serif;font-size:.875rem;color:var(--text);
            background:var(--surface);transition:border .15s,box-shadow .15s;width:100%;
        }
        .form-control:focus{outline:none;border-color:var(--green-500);box-shadow:0 0 0 3px rgba(42,144,96,.12)}
        select.form-control{cursor:pointer}
        textarea.form-control{resize:vertical;min-height:75px;line-height:1.65}
        .form-full{grid-column:1/-1}

        /* ── Section divider ── */
        .divider{
            font-family:'Syne',sans-serif;font-size:.66rem;font-weight:600;
            text-transform:uppercase;letter-spacing:.12em;color:var(--faint);
            margin:1.5rem 0 .75rem;padding-bottom:.5rem;
            border-bottom:1px solid var(--border);
        }

        /* ── Task Items ── */
        .task-item{
            background:var(--surface);border:1px solid var(--border);
            border-radius:var(--radius-lg);margin-bottom:.75rem;
            overflow:hidden;transition:box-shadow .2s;
        }
        .task-item:hover{box-shadow:var(--shadow)}
        .task-head{padding:1rem 1.25rem;display:flex;align-items:flex-start;gap:.875rem}
        .task-num{
            width:34px;height:34px;border-radius:9px;
            background:var(--green-900);color:#fff;
            display:flex;align-items:center;justify-content:center;
            font-family:'Syne',sans-serif;font-size:.72rem;font-weight:700;
            flex-shrink:0;margin-top:1px;letter-spacing:.02em;
        }
        .task-body{flex:1}
        .task-scenario{
            font-size:.72rem;color:var(--faint);font-family:'Syne',sans-serif;
            font-weight:600;letter-spacing:.06em;text-transform:uppercase;
            margin-bottom:3px;
        }
        .task-label{
            font-size:.95rem;font-weight:500;color:var(--text);
            font-style:italic;line-height:1.4;
        }
        .task-criterion{font-size:.78rem;color:var(--muted);margin-top:.3rem;line-height:1.5}
        .task-tip{font-size:.73rem;color:var(--faint);margin-top:.2rem;font-style:italic}
        .task-module{
            padding:3px 10px;border-radius:100px;
            font-family:'Syne',sans-serif;font-size:.66rem;font-weight:700;
            letter-spacing:.05em;flex-shrink:0;white-space:nowrap;
        }
        .mod-publico{background:var(--green-100);color:var(--green-700)}
        .mod-auth{background:var(--blue-100);color:var(--blue-600)}
        .mod-gestao{background:var(--amber-100);color:var(--amber-600)}
        .mod-contrato{background:#EDE9FE;color:#4C2A99}
        .mod-merenda{background:var(--green-50);color:var(--green-600)}
        .mod-dados{background:#F0F9FF;color:#075985}

        .task-footer{
            padding:.7rem 1.25rem;background:var(--bg);
            border-top:1px solid var(--border);
            display:flex;align-items:center;gap:1rem;flex-wrap:wrap;
        }
        .result-label{font-family:'Syne',sans-serif;font-size:.68rem;font-weight:600;color:var(--muted)}
        .result-options{display:flex;gap:.4rem;flex-wrap:wrap}
        .r-btn{
            padding:3px 11px;border-radius:100px;
            border:1px solid var(--border);background:var(--surface);
            font-family:'Syne',sans-serif;font-size:.68rem;font-weight:600;
            color:var(--muted);cursor:pointer;transition:all .15s;
        }
        .r-btn:hover{border-color:var(--border2);color:var(--text)}
        .r-btn.ok{background:var(--green-100);border-color:var(--green-500);color:var(--green-700)}
        .r-btn.parcial{background:var(--amber-100);border-color:var(--amber-600);color:var(--amber-600)}
        .r-btn.falha{background:var(--red-100);border-color:var(--red-600);color:var(--red-600)}
        .time-field{margin-left:auto;display:flex;align-items:center;gap:.5rem}
        .time-field label{font-family:'Syne',sans-serif;font-size:.68rem;color:var(--muted);font-weight:600}
        .time-input{
            width:68px;padding:3px 8px;border:1px solid var(--border);
            border-radius:6px;font-size:.8rem;font-family:'Syne',sans-serif;
            text-align:center;background:var(--surface);color:var(--text);
        }
        .time-input:focus{outline:none;border-color:var(--green-500)}
        .error-level{display:flex;align-items:center;gap:.4rem;margin-left:.5rem}
        .error-level label{font-family:'Syne',sans-serif;font-size:.68rem;color:var(--muted);font-weight:600}
        .error-select{
            padding:2px 6px;border:1px solid var(--border);border-radius:6px;
            font-size:.72rem;font-family:'Syne',sans-serif;background:var(--surface);color:var(--text);cursor:pointer;
        }
        .error-select:focus{outline:none;border-color:var(--green-500)}

        /* ── SUS ── */
        .sus-intro{
            background:var(--green-50);border:1px solid var(--green-200);
            border-radius:var(--radius);padding:1rem 1.25rem;
            font-size:.875rem;color:var(--green-800);
            margin-bottom:1.25rem;line-height:1.7;
        }
        .sus-question{
            padding:1rem 1.5rem;border-bottom:1px solid var(--border);
            display:flex;align-items:center;gap:1.25rem;
        }
        .sus-question:last-child{border-bottom:none}
        .sus-q-num{
            font-family:'Syne',sans-serif;font-size:.66rem;font-weight:700;
            color:var(--faint);width:22px;flex-shrink:0;
        }
        .sus-q-text{flex:1;font-size:.875rem;line-height:1.55}
        .sus-q-type{
            font-family:'Syne',sans-serif;font-size:.63rem;font-weight:700;
            padding:2px 7px;border-radius:4px;flex-shrink:0;letter-spacing:.04em;
        }
        .type-pos{background:var(--green-100);color:var(--green-700)}
        .type-neg{background:var(--red-100);color:var(--red-600)}
        .sus-scale{display:flex;gap:3px;flex-shrink:0}
        .sus-btn{
            width:32px;height:32px;border-radius:8px;
            border:1px solid var(--border);background:var(--surface);
            font-family:'Syne',sans-serif;font-size:.78rem;font-weight:600;
            color:var(--muted);cursor:pointer;transition:all .15s;
        }
        .sus-btn:hover{border-color:var(--green-500);color:var(--green-600)}
        .sus-btn.sel{background:var(--green-700);border-color:var(--green-700);color:#fff}
        .scale-legend{
            display:flex;justify-content:space-between;
            padding:.35rem 1.5rem .35rem calc(1.5rem + 22px + 1.25rem);
            font-family:'Syne',sans-serif;font-size:.65rem;color:var(--faint);font-weight:600;
        }

        /* ── Score ── */
        .score-wrap{display:grid;grid-template-columns:200px 1fr;gap:1rem;margin-top:1.25rem}
        .score-card{
            background:var(--green-900);border-radius:var(--radius-lg);
            padding:1.5rem;text-align:center;color:#fff;
        }
        .score-num{
            font-family:'Syne',sans-serif;font-size:3.5rem;font-weight:700;
            color:#fff;line-height:1;letter-spacing:-.04em;
        }
        .score-lbl{font-size:.65rem;color:rgba(255,255,255,.4);margin-top:.3rem;letter-spacing:.1em;text-transform:uppercase;font-family:'Syne',sans-serif}
        .score-grade{font-family:'Syne',sans-serif;font-size:.88rem;font-weight:600;margin-top:.6rem}
        .score-bar-bg{background:rgba(255,255,255,.1);border-radius:100px;height:4px;margin-top:.5rem}
        .score-bar{height:4px;border-radius:100px;background:var(--green-400);transition:width .7s ease}
        .sus-ref{border-collapse:collapse;width:100%}
        .sus-ref th{
            text-align:left;padding:.4rem 0;border-bottom:1px solid var(--border);
            font-family:'Syne',sans-serif;font-size:.65rem;font-weight:700;
            text-transform:uppercase;letter-spacing:.09em;color:var(--faint);
        }
        .sus-ref td{
            padding:.45rem 0;border-bottom:1px solid var(--border);
            font-size:.82rem;color:var(--muted);
        }
        .sus-ref td:first-child{font-family:'Syne',sans-serif;font-weight:700;color:var(--text)}
        .sus-ref tr:last-child td{border-bottom:none}
        .sus-hl{background:var(--green-50)}
        .sus-hl td{color:var(--green-700)!important;font-weight:700!important}

        /* ── Qual ── */
        .qual-q{margin-bottom:.875rem}
        .qual-q label{display:block;font-family:'Syne',sans-serif;font-size:.75rem;font-weight:600;color:var(--muted);margin-bottom:.3rem}

        /* ── Moderador ── */
        .checklist-item{
            display:flex;align-items:flex-start;gap:.75rem;
            padding:.6rem 0;border-bottom:1px solid var(--border);cursor:pointer;
        }
        .checklist-item:last-child{border-bottom:none}
        .checklist-item input[type=checkbox]{width:15px;height:15px;margin-top:2px;accent-color:var(--green-600);flex-shrink:0}
        .checklist-item span{font-size:.875rem;line-height:1.55}
        .checklist-item.done span{text-decoration:line-through;color:var(--faint)}
        .script-box{
            background:var(--surface2);border-left:3px solid var(--green-400);
            border-radius:0 var(--radius) var(--radius) 0;
            padding:1rem 1.25rem;font-size:.875rem;font-style:italic;
            color:var(--muted);line-height:1.85;margin:.75rem 0;
        }

        /* ── Sessions ── */
        .session-row{
            display:flex;align-items:center;gap:1rem;
            padding:.75rem 1.25rem;border-bottom:1px solid var(--border);transition:background .15s;
        }
        .session-row:last-child{border-bottom:none}
        .session-row:hover{background:var(--bg)}
        .s-avatar{
            width:36px;height:36px;border-radius:50%;background:var(--green-700);
            color:#fff;display:flex;align-items:center;justify-content:center;
            font-family:'Syne',sans-serif;font-size:.72rem;font-weight:700;flex-shrink:0;
        }
        .s-name{font-family:'Syne',sans-serif;font-size:.9rem;font-weight:600}
        .s-meta{font-size:.75rem;color:var(--muted);margin-top:1px}
        .s-score{
            margin-left:auto;padding:3px 10px;border-radius:100px;
            font-family:'Syne',sans-serif;font-size:.72rem;font-weight:700;
        }
        .s-actions{display:flex;gap:.4rem}
        .empty-state{
            text-align:center;padding:3rem 1rem;
            color:var(--faint);font-size:.875rem;font-style:italic;
        }

        /* ── Análise ── */
        .analysis-section{margin-bottom:1.5rem}
        .analysis-section h3{
            font-family:'Syne',sans-serif;font-size:.9rem;font-weight:700;
            color:var(--green-800);margin-bottom:.75rem;letter-spacing:-.01em;
        }
        .metric-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;margin-bottom:1rem}
        .metric-card{
            background:var(--surface2);border-radius:var(--radius);
            padding:.875rem;text-align:center;
        }
        .metric-val{
            font-family:'Syne',sans-serif;font-size:1.75rem;font-weight:700;
            color:var(--green-700);line-height:1;
        }
        .metric-lbl{font-size:.7rem;color:var(--muted);margin-top:.3rem;font-style:italic}
        .chart-wrap{
            background:var(--surface);border:1px solid var(--border);
            border-radius:var(--radius-lg);padding:1.25rem;margin-bottom:.875rem;
        }
        .chart-title{font-family:'Syne',sans-serif;font-size:.75rem;font-weight:700;color:var(--muted);margin-bottom:1rem;text-transform:uppercase;letter-spacing:.08em}
        .ai-report{
            background:var(--green-50);border:1px solid var(--green-200);
            border-radius:var(--radius-lg);padding:1.5rem;
            font-size:.875rem;line-height:1.8;color:var(--green-900);
            white-space:pre-wrap;font-style:italic;
        }
        .ai-report.loading{color:var(--green-600);animation:blink 1s ease-in-out infinite}
        @keyframes blink{0%,100%{opacity:1}50%{opacity:.5}}
        .task-result-table{width:100%;border-collapse:collapse;font-size:.82rem}
        .task-result-table th{
            text-align:left;padding:.4rem .6rem;border-bottom:1px solid var(--border);
            font-family:'Syne',sans-serif;font-size:.65rem;font-weight:700;
            text-transform:uppercase;letter-spacing:.08em;color:var(--faint);
            background:var(--bg);
        }
        .task-result-table td{padding:.45rem .6rem;border-bottom:1px solid var(--border);vertical-align:middle}
        .task-result-table tr:last-child td{border-bottom:none}

        /* ── Buttons ── */
        .btn{
            display:inline-flex;align-items:center;gap:.4rem;
            padding:.55rem 1.1rem;border-radius:var(--radius);
            border:1px solid var(--border);background:var(--surface);
            font-family:'Syne',sans-serif;font-size:.78rem;font-weight:600;
            color:var(--text);cursor:pointer;transition:all .15s;
        }
        .btn:hover{background:var(--surface2);border-color:var(--border2)}
        .btn-primary{background:var(--green-700);color:#fff;border-color:var(--green-700)}
        .btn-primary:hover{background:var(--green-800)}
        .btn-danger{background:var(--red-100);color:var(--red-600);border-color:var(--red-600)}
        .btn-danger:hover{background:#f8d7d7}
        .btn-accent{background:var(--green-500);color:#fff;border-color:var(--green-500)}
        .btn-accent:hover{background:var(--green-600)}
        .btn-group{display:flex;gap:.5rem;flex-wrap:wrap;margin-top:1.25rem}

        /* ── Toast ── */
        .toast{
            position:fixed;bottom:1.5rem;right:1.5rem;
            background:var(--green-900);color:#fff;
            padding:.75rem 1.25rem;border-radius:var(--radius);
            font-family:'Syne',sans-serif;font-size:.82rem;font-weight:500;
            display:flex;align-items:center;gap:.5rem;
            opacity:0;transform:translateY(8px);transition:all .25s;
            pointer-events:none;z-index:9999;max-width:340px;
        }
        .toast.show{opacity:1;transform:translateY(0)}

        @media(max-width:780px){
            .sidebar{width:100%;height:auto;position:static}
            .main{padding:1rem}
            .topbar{padding:.75rem 1rem}
            .metric-grid{grid-template-columns:1fr 1fr}
            .score-wrap{grid-template-columns:1fr}
            .form-grid{grid-template-columns:1fr}
        }
    </style>
</head>
<body>
<div class="wrap">

    <!-- SIDEBAR -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">
                <div class="brand-icon">🍎</div>
                <span class="brand-name">SISGEM</span>
            </div>
            <div class="brand-sub">Teste de Usabilidade</div>
        </div>

        <div class="nav-group">
            <div class="nav-label">Protocolo</div>
            <div class="nav-item active" onclick="goTo('info')" data-panel="info">
                <span class="nav-icon">◈</span> Identificação
            </div>
            <div class="nav-item" onclick="goTo('tarefas')" data-panel="tarefas">
                <span class="nav-icon">◎</span> Tarefas
                <span class="nav-badge" id="badge-tarefas">19</span>
            </div>
            <div class="nav-item" onclick="goTo('sus')" data-panel="sus">
                <span class="nav-icon">◉</span> Formulário SUS
            </div>
            <div class="nav-item" onclick="goTo('qual')" data-panel="qual">
                <span class="nav-icon">◐</span> Questões abertas
            </div>
        </div>

        <div class="nav-group">
            <div class="nav-label">Moderador</div>
            <div class="nav-item" onclick="goTo('moderador')" data-panel="moderador">
                <span class="nav-icon">⊕</span> Roteiro
            </div>
            <div class="nav-item" onclick="goTo('sessoes')" data-panel="sessoes">
                <span class="nav-icon">⊞</span> Sessões salvas
                <span class="nav-badge" id="badge-sessoes">0</span>
            </div>
            <div class="nav-item" onclick="goTo('analise')" data-panel="analise">
                <span class="nav-icon">◑</span> Análise geral
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="save-row">
                <div class="save-dot"></div>
                <span id="save-status">Autosave ativo</span>
            </div>
            <div style="font-size:.65rem;color:rgba(255,255,255,.2);margin-top:.5rem;font-family:'Syne',sans-serif">
                SUS · Brooke (1996) · v1.0
            </div>
        </div>
    </nav>

    <!-- MAIN -->
    <div style="flex:1;display:flex;flex-direction:column;min-width:0">

        <div class="topbar">
            <span class="topbar-title" id="topbar-title">Identificação do participante</span>
            <div class="topbar-actions">
                <button class="btn" onclick="clearDraft()">↺ Nova sessão</button>
                <button class="btn btn-primary" onclick="saveSession()">✦ Salvar sessão</button>
            </div>
        </div>

        <div class="main">

            <!-- ═══ PAINEL: Identificação ═══ -->
            <div id="panel-info" class="panel active">
                <div class="page-header">
                    <h1>Identificação do participante</h1>
                    <p>Preencha antes de iniciar. Os dados são salvos automaticamente no navegador.</p>
                </div>
                <div class="card">
                    <div class="card-title">Dados da sessão</div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Código do participante</label>
                            <input class="form-control" id="p-codigo" type="text" placeholder="P01, P02…">
                        </div>
                        <div class="form-group">
                            <label>Data</label>
                            <input class="form-control" id="p-data" type="date">
                        </div>
                        <div class="form-group">
                            <label>Hora de início</label>
                            <input class="form-control" id="p-hora" type="time">
                        </div>
                        <div class="form-group">
                            <label>Moderador</label>
                            <input class="form-control" id="p-moderador" type="text" placeholder="Nome do pesquisador">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-title">Perfil do participante</div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Função na escola</label>
                            <select class="form-control" id="p-perfil">
                                <option value="">Selecionar…</option>
                                <option>Diretor(a)</option>
                                <option>Secretário(a) escolar</option>
                                <option>Responsável pela merenda</option>
                                <option>Servidor administrativo</option>
                                <option>Professor(a)</option>
                                <option>Gestor de TI</option>
                                <option>Outro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Faixa etária</label>
                            <select class="form-control" id="p-idade">
                                <option value="">Selecionar…</option>
                                <option>18–29 anos</option>
                                <option>30–44 anos</option>
                                <option>45–59 anos</option>
                                <option>60+ anos</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Experiência com sistemas web</label>
                            <select class="form-control" id="p-experiencia">
                                <option value="">Selecionar…</option>
                                <option>Iniciante (uso básico)</option>
                                <option>Intermediário (usa regularmente)</option>
                                <option>Avançado (usa com frequência sistemas complexos)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Já usou o SISGEM antes?</label>
                            <select class="form-control" id="p-uso-prev">
                                <option value="">Selecionar…</option>
                                <option>Não, nunca</option>
                                <option>Somente demonstração</option>
                                <option>Sim, uso em produção</option>
                            </select>
                        </div>
                        <div class="form-group form-full">
                            <label>Observações iniciais do moderador</label>
                            <textarea class="form-control" id="p-obs" placeholder="Contexto relevante, estado do participante, condições técnicas do ambiente de teste…"></textarea>
                        </div>
                    </div>
                </div>
                <div class="btn-group">
                    <button class="btn btn-primary" onclick="goTo('tarefas');autoSave()">Iniciar teste → Tarefas</button>
                </div>
            </div>

            <!-- ═══ PAINEL: Tarefas ═══ -->
            <div id="panel-tarefas" class="panel">
                <div class="page-header">
                    <h1>Roteiro de tarefas</h1>
                    <p>Apresente uma tarefa por vez. Aplique o protocolo think-aloud — não ajude, apenas observe e registre.</p>
                </div>
                <div id="task-list"></div>
                <div class="card" style="margin-top:1rem">
                    <div class="card-title">Observações gerais do moderador</div>
                    <textarea class="form-control" id="obs-tarefas" rows="5"
                              placeholder="Padrões de comportamento observados, erros recorrentes, hesitações significativas, comentários espontâneos relevantes…"></textarea>
                </div>
                <div class="btn-group">
                    <button class="btn" onclick="goTo('info')">← Identificação</button>
                    <button class="btn btn-primary" onclick="goTo('sus');autoSave()">Formulário SUS →</button>
                </div>
            </div>

            <!-- ═══ PAINEL: SUS ═══ -->
            <div id="panel-sus" class="panel">
                <div class="page-header">
                    <h1>Formulário SUS</h1>
                    <p>System Usability Scale · Brooke (1996) · Aplicar imediatamente após as tarefas.</p>
                </div>
                <div class="sus-intro">
                    <strong>Instrução ao participante:</strong> "Para cada afirmação abaixo, marque um número de 1 a 5 — sendo <strong>1 = discordo totalmente</strong> e <strong>5 = concordo totalmente</strong>. Responda com base na sua experiência geral com o sistema agora. Não há respostas certas ou erradas."
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
                        <div class="score-bar-bg"><div class="score-bar" id="sus-bar" style="width:0%"></div></div>
                    </div>
                    <div class="card card-sm" style="margin:0">
                        <div class="card-title" style="margin-bottom:.75rem">Referência de classificação</div>
                        <table class="sus-ref"><thead><tr>
                                <th>Pontuação</th><th>Adjetivo</th><th>Aceitabilidade</th>
                            </tr></thead><tbody id="sus-ref-body"></tbody></table>
                    </div>
                </div>
                <div class="btn-group">
                    <button class="btn" onclick="goTo('tarefas')">← Tarefas</button>
                    <button class="btn btn-primary" onclick="goTo('qual');autoSave()">Questões abertas →</button>
                </div>
            </div>

            <!-- ═══ PAINEL: Questões abertas ═══ -->
            <div id="panel-qual" class="panel">
                <div class="page-header">
                    <h1>Questões abertas</h1>
                    <p>Debriefing — máximo 10 minutos. Registre as respostas literalmente quando possível.</p>
                </div>
                <div class="card">
                    <div class="card-title">Experiência geral</div>
                    <div class="qual-q"><label>1. O que você mais gostou no sistema?</label>
                        <textarea class="form-control" id="q1" rows="3" placeholder="Resposta do participante…"></textarea></div>
                    <div class="qual-q"><label>2. O que causou mais dificuldade ou frustração?</label>
                        <textarea class="form-control" id="q2" rows="3" placeholder="Resposta do participante…"></textarea></div>
                    <div class="qual-q"><label>3. Se pudesse mudar uma coisa no sistema, o que seria?</label>
                        <textarea class="form-control" id="q3" rows="3" placeholder="Resposta do participante…"></textarea></div>
                </div>
                <div class="card">
                    <div class="card-title">Módulos específicos</div>
                    <div class="qual-q"><label>4. O processo de login (LDAP) foi claro e rápido?</label>
                        <textarea class="form-control" id="q4" rows="2" placeholder="Resposta do participante…"></textarea></div>
                    <div class="qual-q"><label>5. A sincronização de cursos e discentes (importação) foi intuitiva?</label>
                        <textarea class="form-control" id="q5" rows="2" placeholder="Resposta do participante…"></textarea></div>
                    <div class="qual-q"><label>6. O fluxo de cadastro e gestão de contratos/empenhos foi compreensível?</label>
                        <textarea class="form-control" id="q6" rows="2" placeholder="Resposta do participante…"></textarea></div>
                    <div class="qual-q"><label>7. A simulação de retirada de merenda refletiu o processo real da escola?</label>
                        <textarea class="form-control" id="q7" rows="2" placeholder="Resposta do participante…"></textarea></div>
                    <div class="qual-q"><label>8. Os gráficos e dados de retirada foram fáceis de interpretar?</label>
                        <textarea class="form-control" id="q8" rows="2" placeholder="Resposta do participante…"></textarea></div>
                </div>
                <div class="card">
                    <div class="card-title">Intenção de uso e contexto institucional</div>
                    <div class="qual-q"><label>9. Você confiaria neste sistema para gerenciar a merenda escolar da sua instituição?</label>
                        <textarea class="form-control" id="q9" rows="3" placeholder="Resposta do participante…"></textarea></div>
                    <div class="qual-q"><label>10. Há algo que falta no sistema para atender às necessidades reais da escola?</label>
                        <textarea class="form-control" id="q10" rows="3" placeholder="Resposta do participante…"></textarea></div>
                    <div class="qual-q"><label>11. Comentários livres ou sugestões adicionais</label>
                        <textarea class="form-control" id="q11" rows="3" placeholder="Resposta do participante…"></textarea></div>
                </div>
                <div class="btn-group">
                    <button class="btn" onclick="goTo('sus')">← SUS</button>
                    <button class="btn btn-accent" onclick="saveSession()">✦ Salvar sessão completa</button>
                    <button class="btn btn-primary" onclick="exportJSON()">↓ Exportar JSON</button>
                </div>
            </div>

            <!-- ═══ PAINEL: Moderador ═══ -->
            <div id="panel-moderador" class="panel">
                <div class="page-header">
                    <h1>Roteiro do moderador</h1>
                    <p>Protocolo completo para condução. Siga a sequência e use o script como guia de fala.</p>
                </div>
                <div class="card">
                    <div class="card-title">Antes do teste — checklist</div>
                    <div id="pre-check"></div>
                </div>
                <div class="divider">Script de abertura — leia ao participante</div>
                <div class="card" style="padding:0;overflow:hidden">
                    <div style="padding:.75rem 1.5rem;background:var(--green-50);font-family:'Syne',sans-serif;font-size:.68rem;font-weight:700;color:var(--green-600);letter-spacing:.1em;text-transform:uppercase;border-bottom:1px solid var(--border)">Fala do moderador</div>
                    <div class="script-box" style="margin:0;border-left:none;border-radius:0;border-top:none">
                        "Obrigado por participar desta pesquisa sobre o <strong>SISGEM</strong>, sistema de gestão de merenda escolar. <strong>Não estamos testando você</strong> — estamos avaliando o sistema para identificar pontos de melhoria. Não existe resposta certa ou errada, e você pode interromper a qualquer momento.<br><br>
                        Durante o teste, peço que <strong>pense em voz alta</strong>: verbalize o que está vendo, o que pretende fazer e o que espera que aconteça. Se travar em algum ponto, continue falando — isso é o mais valioso para nós.<br><br>
                        A sessão pode ser gravada somente para fins de análise interna, conforme você autorizou no TCLE. Tem alguma dúvida antes de começarmos?"
                    </div>
                </div>
                <div class="divider">Durante o teste</div>
                <div class="card">
                    <div class="card-title">Instruções operacionais</div>
                    <div id="durante-check"></div>
                    <div class="script-box" style="margin-top:.875rem">
                        <strong>Se silêncio > 15s:</strong> "O que você está pensando agora?"<br>
                        <strong>Se travar > 60s:</strong> "O que você tentaria fazer a seguir?" (não revele o caminho)<br>
                        <strong>Se perguntar se está certo:</strong> "Não existe certo ou errado — o que você faria normalmente?"<br>
                        <strong>Se quiser desistir da tarefa:</strong> registre como Falha e avance para a próxima.
                    </div>
                </div>
                <div class="divider">Após o teste</div>
                <div class="card">
                    <div class="card-title">Encerramento</div>
                    <div id="pos-check"></div>
                    <div class="script-box" style="margin-top:.875rem">
                        "Muito obrigado pela participação! Seu feedback é fundamental para melhorar o SISGEM. Gostaria de compartilhar algo que observou e não chegou a mencionar durante o teste?"
                    </div>
                </div>
                <div class="divider">Classificação de erros de usabilidade (Nielsen, 1994)</div>
                <div class="card card-sm">
                    <div style="font-size:.82rem;line-height:2.1;color:var(--muted)">
                        <div><span style="color:var(--red-600);font-weight:700">● Nível 1 — Catastrófico:</span> impede completar a tarefa. Corrigir imediatamente antes do lançamento.</div>
                        <div><span style="color:#B45309;font-weight:700">● Nível 2 — Sério:</span> dificulta muito, mas é contornável. Alta prioridade de redesign.</div>
                        <div><span style="color:var(--green-700);font-weight:700">● Nível 3 — Menor:</span> atraso ou confusão leve. Corrigir se possível no próximo ciclo.</div>
                        <div><span style="color:var(--faint);font-weight:700">● Nível 4 — Cosmético:</span> não afeta uso. Endereçar se sobrar tempo de desenvolvimento.</div>
                    </div>
                </div>
            </div>

            <!-- ═══ PAINEL: Sessões ═══ -->
            <div id="panel-sessoes" class="panel">
                <div class="page-header">
                    <h1>Sessões salvas</h1>
                    <p>Todas as sessões armazenadas localmente neste navegador.</p>
                </div>
                <div class="card" style="padding:0;overflow:hidden">
                    <div id="sessions-list"></div>
                </div>
                <div class="btn-group">
                    <button class="btn btn-primary" onclick="exportAllJSON()">↓ Exportar todas (JSON)</button>
                    <button class="btn btn-primary" onclick="exportCSV()">↓ Exportar CSV resumido</button>
                    <button class="btn btn-danger" onclick="clearAll()">⊘ Apagar todas</button>
                </div>
            </div>

            <!-- ═══ PAINEL: Análise ═══ -->
            <div id="panel-analise" class="panel">
                <div class="page-header">
                    <h1>Análise geral dos testes</h1>
                    <p>Visualizações e relatório gerado a partir das sessões salvas.</p>
                </div>
                <div id="analise-content"></div>
            </div>

        </div><!-- /main -->
    </div>
</div>

<div class="toast" id="toast"></div>

<script>
    // ══════════════════════════════════════════════════════
    // DATA
    // ══════════════════════════════════════════════════════
    const TASKS = [
        { id:'T1',  mod:'publico',   cls:'mod-publico',
            scenario:'Área pública — sem login',
            label:'Identifique o cardápio escolar atual disponível no sistema.',
            criterion:'Cardápio corrente visualizado sem necessidade de autenticação.',
            tip:'Observe se o acesso público é percebido como separado do sistema restrito. O participante tenta fazer login antes de procurar o cardápio?' },
        { id:'T2',  mod:'público',   cls:'mod-publico',
            scenario:'Área pública — sem login',
            label:'Localize o cardápio da próxima semana.',
            criterion:'Cardápio futuro acessado sem login, com datas corretas.',
            tip:'Note se a navegação temporal (semana passada / próxima) é descoberta espontaneamente.' },
        { id:'T3',  mod:'auth',      cls:'mod-auth',
            scenario:'Autenticação',
            label:'Autentique-se no sistema usando suas credenciais LDAP.',
            criterion:'Login concluído; painel principal visível e com dados do usuário corretos.',
            tip:'Registre o tempo até o clique em "Entrar". O fluxo LDAP causa hesitação? Há feedback claro de erro?' },
        { id:'T4',  mod:'gestão',    cls:'mod-gestao',
            scenario:'Gestão escolar',
            label:'Sincronize os cursos da instituição a partir do sistema externo (CTA).',
            criterion:'Importação concluída; lista de cursos atualizada visível.',
            tip:'O botão/CTA de sincronização é encontrado sem ajuda? O feedback de carregamento é claro?' },
        { id:'T5',  mod:'gestão',    cls:'mod-gestao',
            scenario:'Gestão escolar',
            label:'Habilite o acesso à merenda escolar para uma turma ou grupo de estudantes.',
            criterion:'Acesso habilitado; status atualizado na interface.',
            tip:'O fluxo de permissão é intuitivo? O participante sabe onde confirmar a ação?' },
        { id:'T6',  mod:'gestão',    cls:'mod-gestao',
            scenario:'Gestão escolar',
            label:'Sincronize os discentes da instituição a partir do sistema externo (CTA).',
            criterion:'Importação concluída; base de estudantes atualizada.',
            tip:'Diferencia o fluxo do T4? Observe se reutiliza o mesmo caminho mental.' },
        { id:'T7',  mod:'contrato',  cls:'mod-contrato',
            scenario:'Contratos e estoque',
            label:'Identifique os contratos ativos cadastrados no sistema.',
            criterion:'Lista de contratos acessada e visualizada corretamente.',
            tip:'A seção de contratos é encontrada pelo menu ou por busca? Há ambiguidade de nomenclatura?' },
        { id:'T8',  mod:'contrato',  cls:'mod-contrato',
            scenario:'Contratos e estoque',
            label:'Cadastre um novo contrato incluindo ao menos um alimento.',
            criterion:'Contrato criado com alimento vinculado; confirmação exibida.',
            tip:'O formulário de cadastro é descoberto pelo fluxo do T7? O campo de alimento é associado ao contrato sem instrução?' },
        { id:'T9',  mod:'contrato',  cls:'mod-contrato',
            scenario:'Contratos e estoque',
            label:'Edite os dados do contrato recém-cadastrado.',
            criterion:'Alteração salva e refletida na listagem.',
            tip:'O botão de edição é encontrado na listagem ou na tela de detalhe? Duplo clique ou ícone?' },
        { id:'T10', mod:'contrato',  cls:'mod-contrato',
            scenario:'Contratos e estoque',
            label:'Visualize os dados completos de um contrato existente.',
            criterion:'Tela de detalhes do contrato acessada com todas as informações visíveis.',
            tip:'O participante diferencia "visualizar" de "editar"? Há risco de edição acidental?' },
        { id:'T11', mod:'contrato',  cls:'mod-contrato',
            scenario:'Contratos e estoque',
            label:'Cadastre um empenho vinculado ao contrato e visualize seus dados.',
            criterion:'Empenho criado e acessível na tela de detalhes do contrato.',
            tip:'O conceito de empenho é compreendido? O vínculo com o contrato é evidente na interface?' },
        { id:'T12', mod:'merenda',   cls:'mod-merenda',
            scenario:'Pedidos e merenda',
            label:'Faça um pedido de merenda para a semana seguinte.',
            criterion:'Pedido criado com itens e quantidade; confirmação exibida.',
            tip:'O fluxo de pedido é encontrado a partir do cardápio ou de um menu dedicado? Observe ponto de entrada.' },
        { id:'T13', mod:'merenda',   cls:'mod-merenda',
            scenario:'Pedidos e merenda',
            label:'Atualize o status de um pedido para "Recebido".',
            criterion:'Status alterado corretamente; histórico atualizado.',
            tip:'A mudança de status é feita por botão, seletor ou outra interação? É reversível?' },
        { id:'T14', mod:'merenda',   cls:'mod-merenda',
            scenario:'Pedidos e merenda',
            label:'Visualize a lista de pedidos realizados.',
            criterion:'Lista de pedidos acessada com filtros/datas visíveis.',
            tip:'O participante usa filtro de data ou de status espontaneamente?' },
        { id:'T15', mod:'contrato',  cls:'mod-contrato',
            scenario:'Contratos e estoque',
            label:'Identifique outros contratos vinculados à mesma empresa fornecedora.',
            criterion:'Contratos da empresa listados ou filtrados corretamente.',
            tip:'O vínculo por empresa é percebido como funcionalidade? O participante usa busca ou navega pela listagem?' },
        { id:'T16', mod:'merenda',   cls:'mod-merenda',
            scenario:'Cardápio e merenda',
            label:'Cadastre um novo cardápio para uma data futura.',
            criterion:'Cardápio criado, com itens e data vinculados.',
            tip:'O fluxo de criação difere da visualização (T1/T2)? O participante diferencia os contextos.' },
        { id:'T17', mod:'merenda',   cls:'mod-merenda',
            scenario:'Retirada biométrica',
            label:'Simule ou execute a retirada de merenda por um estudante.',
            criterion:'Retirada registrada com identificação do estudante e confirmação.',
            tip:'O fluxo de retirada é compreendido como individual e rastreável?' },
        { id:'T18', mod:'dados',     cls:'mod-dados',
            scenario:'Dados e relatórios',
            label:'Analise os dados de retirada de merenda em formato de gráfico.',
            criterion:'Gráfico de retiradas acessado e interpretado pelo participante.',
            tip:'O participante encontra o módulo de análise sem instrução? Consegue interpretar os eixos corretamente?' },
        { id:'T19', mod:'dados',     cls:'mod-dados',
            scenario:'Dados e relatórios',
            label:'Visualize o gráfico de acessos mais frequentes, sobras e distribuição por turno.',
            criterion:'Gráfico com as três dimensões (frequência, sobras, turno) acessado.',
            tip:'A segmentação por turno é intuitiva? O participante percebe "sobras" como dado de desperdício ou de estoque?' },
    ];

    const SUS_ITEMS = [
        { q:'Eu usaria este sistema com frequência.', type:'pos' },
        { q:'Achei o sistema desnecessariamente complexo.', type:'neg' },
        { q:'Achei o sistema fácil de usar.', type:'pos' },
        { q:'Precisaria de apoio técnico para conseguir usar este sistema.', type:'neg' },
        { q:'As diversas funções do sistema estão bem integradas.', type:'pos' },
        { q:'Achei que havia muita inconsistência no sistema.', type:'neg' },
        { q:'Imagino que a maioria das pessoas aprenderia a usar este sistema rapidamente.', type:'pos' },
        { q:'Achei o sistema muito difícil de usar.', type:'neg' },
        { q:'Me senti confiante usando o sistema.', type:'pos' },
        { q:'Precisei aprender muitas coisas antes de conseguir usar este sistema.', type:'neg' },
    ];

    const SUS_REF = [
        { range:'≥ 90', label:'Excelente',   accept:'Recomendado com entusiasmo', min:90 },
        { range:'80–89',label:'Bom',          accept:'Aceitável',                  min:80 },
        { range:'70–79',label:'OK',           accept:'Com ressalvas',              min:70 },
        { range:'60–69',label:'Pobre',        accept:'Abaixo do esperado',         min:60 },
        { range:'< 60', label:'Inaceitável',  accept:'Reprojetar antes do lançamento', min:0 },
    ];

    const PRE = [
        'TCLE assinado ou confirmado digitalmente pelo participante',
        'Ambiente silencioso preparado; para remoto: câmera + microfone testados',
        'Gravação de tela ativada (com consentimento)',
        'Protótipo SISGEM acessível na URL correta; conta de teste criada e verificada',
        'Conta LDAP de teste pronta para o T3',
        'Tarefas impressas ou em tela separada — participante não vê tarefa seguinte',
        'Cronômetro pronto para registro de tempo por tarefa',
    ];
    const DURANTE = [
        'NÃO ajudar o participante — observar, anotar e encorajar think-aloud',
        'Encerrar tarefa após 3 minutos sem progresso; registrar como Falha',
        'Registrar tempo de conclusão de cada tarefa',
        'Anotar erros, hesitações, comentários espontâneos e pontos de abandono',
        'Classificar erros por nível (1–4) durante ou logo após cada tarefa',
        'Marcar resultado: Concluída / Parcial / Falha / N/A',
    ];
    const POS = [
        'Aplicar formulário SUS imediatamente (memória fresca)',
        'Conduzir debriefing com questões abertas (máx. 10 min)',
        'Salvar sessão antes de fechar o navegador',
        'Agradecer e orientar sobre próximos passos',
        'Registrar impressões pessoais do moderador logo após o término',
    ];

    const PAGE_TITLES = {
        info:'Identificação do participante', tarefas:'Roteiro de tarefas',
        sus:'Formulário SUS', qual:'Questões abertas',
        moderador:'Roteiro do moderador', sessoes:'Sessões salvas',
        analise:'Análise geral dos testes'
    };

    // ══════════════════════════════════════════════════════
    // STATE
    // ══════════════════════════════════════════════════════
    let susAns = new Array(10).fill(0);
    let taskRes = {}; // tid -> { result, time, errorLevel }
    let charts = {};

    // ══════════════════════════════════════════════════════
    // INIT
    // ══════════════════════════════════════════════════════
    window.addEventListener('DOMContentLoaded', () => {
        renderTasks();
        renderSUS();
        renderSUSRef();
        renderChecklist('pre-check', PRE);
        renderChecklist('durante-check', DURANTE);
        renderChecklist('pos-check', POS);
        const now = new Date();
        document.getElementById('p-data').value = now.toISOString().split('T')[0];
        document.getElementById('p-hora').value = now.toTimeString().slice(0,5);
        loadDraft();
        updateBadges();
        document.querySelectorAll('input,select,textarea').forEach(el => {
            el.addEventListener('input', autoSave);
            el.addEventListener('change', autoSave);
        });
    });

    // ══════════════════════════════════════════════════════
    // NAVIGATION
    // ══════════════════════════════════════════════════════
    function goTo(name) {
        document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
        document.getElementById('panel-' + name)?.classList.add('active');
        document.querySelector(`[data-panel="${name}"]`)?.classList.add('active');
        document.getElementById('topbar-title').textContent = PAGE_TITLES[name] || '';
        if (name === 'sessoes') renderSessions();
        if (name === 'analise') renderAnalysis();
    }

    // ══════════════════════════════════════════════════════
    // TASKS
    // ══════════════════════════════════════════════════════
    function renderTasks() {
        document.getElementById('task-list').innerHTML = TASKS.map(t => `
    <div class="task-item">
      <div class="task-head">
        <div class="task-num">${t.id}</div>
        <div class="task-body">
          <div class="task-scenario">${t.scenario}</div>
          <div class="task-label">"${t.label}"</div>
          <div class="task-criterion">✓ Critério: ${t.criterion}</div>
          <div class="task-tip">💡 ${t.tip}</div>
        </div>
        <span class="task-module ${t.cls}">${t.mod}</span>
      </div>
      <div class="task-footer">
        <span class="result-label">Resultado:</span>
        <div class="result-options">
          ${['Concluída','Parcial','Falha','N/A'].map(r =>
            `<button class="r-btn" id="rb-${t.id}-${r.replace('/','')}" onclick="setResult('${t.id}','${r}')">${r}</button>`
        ).join('')}
        </div>
        <div class="error-level">
          <label>Nível de erro:</label>
          <select class="error-select" id="err-${t.id}" onchange="setError('${t.id}',this.value)">
            <option value="">—</option>
            <option value="1">1 – Catastrófico</option>
            <option value="2">2 – Sério</option>
            <option value="3">3 – Menor</option>
            <option value="4">4 – Cosmético</option>
            <option value="0">Sem erro</option>
          </select>
        </div>
        <div class="time-field">
          <label>Tempo (s):</label>
          <input class="time-input" id="time-${t.id}" type="number" min="0" placeholder="—"
            oninput="setTime('${t.id}',this.value)">
        </div>
      </div>
    </div>
  `).join('');
    }

    function setResult(tid, result) {
        if (!taskRes[tid]) taskRes[tid] = {};
        taskRes[tid].result = result;
        ['Concluída','Parcial','Falha','N/A'].forEach(r => {
            const btn = document.getElementById(`rb-${tid}-${r.replace('/','')}`);
            if (!btn) return;
            btn.classList.remove('ok','parcial','falha');
            if (r === result) {
                if (r === 'Concluída') btn.classList.add('ok');
                else if (r === 'Parcial') btn.classList.add('parcial');
                else if (r === 'Falha') btn.classList.add('falha');
            }
        });
        autoSave();
    }
    function setTime(tid, v) {
        if (!taskRes[tid]) taskRes[tid] = {};
        taskRes[tid].time = v;
        autoSave();
    }
    function setError(tid, v) {
        if (!taskRes[tid]) taskRes[tid] = {};
        taskRes[tid].errorLevel = v;
        autoSave();
    }

    // ══════════════════════════════════════════════════════
    // SUS
    // ══════════════════════════════════════════════════════
    function renderSUS() {
        document.getElementById('sus-questions').innerHTML = SUS_ITEMS.map((item, i) => `
    <div class="sus-question">
      <div class="sus-q-num">Q${i+1}</div>
      <div class="sus-q-text">${item.q}</div>
      <span class="sus-q-type ${item.type==='pos'?'type-pos':'type-neg'}">
        ${item.type==='pos'?'+ positiva':'− negativa'}
      </span>
      <div class="sus-scale">
        ${[1,2,3,4,5].map(v =>
            `<button class="sus-btn" id="sus-${i}-${v}" onclick="setSUS(${i},${v})">${v}</button>`
        ).join('')}
      </div>
    </div>
  `).join('');
    }

    function setSUS(q, val) {
        susAns[q] = val;
        for (let v=1;v<=5;v++) document.getElementById(`sus-${q}-${v}`)?.classList.toggle('sel', v===val);
        calcSUS();
        autoSave();
    }

    function calcSUS() {
        if (susAns.some(v => v===0)) {
            document.getElementById('sus-score-val').textContent = '—';
            document.getElementById('sus-grade').textContent = 'aguardando respostas';
            document.getElementById('sus-grade').style.color = 'rgba(255,255,255,.4)';
            document.getElementById('sus-bar').style.width = '0%';
            return;
        }
        let sum = 0;
        for (let i=0;i<10;i++) sum += i%2===0 ? susAns[i]-1 : 5-susAns[i];
        const score = Math.round(sum*2.5);
        document.getElementById('sus-score-val').textContent = score;
        document.getElementById('sus-bar').style.width = score+'%';
        const { label, color } = scoreInfo(score);
        const grade = document.getElementById('sus-grade');
        grade.textContent = label;
        grade.style.color = color;
        highlightRef(score);
        return score;
    }

    function getSUSScore() {
        if (susAns.some(v=>v===0)) return null;
        let sum=0;
        for(let i=0;i<10;i++) sum += i%2===0 ? susAns[i]-1 : 5-susAns[i];
        return Math.round(sum*2.5);
    }

    function scoreInfo(score) {
        if (score>=90) return { label:'Excelente', color:'#3DAE77' };
        if (score>=80) return { label:'Bom',       color:'#6FBF8A' };
        if (score>=70) return { label:'Aceitável', color:'#E5A000' };
        if (score>=60) return { label:'Pobre',     color:'#D97706' };
        return             { label:'Inaceitável', color:'#EF4444' };
    }

    function renderSUSRef() {
        document.getElementById('sus-ref-body').innerHTML = SUS_REF.map(r =>
            `<tr id="ref-${r.min}"><td>${r.range}</td><td>${r.label}</td><td>${r.accept}</td></tr>`
        ).join('');
    }

    function highlightRef(score) {
        document.querySelectorAll('#sus-ref-body tr').forEach(tr => tr.classList.remove('sus-hl'));
        const match = SUS_REF.find(r => score >= r.min);
        if (match) document.getElementById(`ref-${match.min}`)?.classList.add('sus-hl');
    }

    // ══════════════════════════════════════════════════════
    // CHECKLISTS
    // ══════════════════════════════════════════════════════
    function renderChecklist(id, items) {
        document.getElementById(id).innerHTML = items.map((item,i) => `
    <label class="checklist-item" id="cl-${id}-${i}">
      <input type="checkbox" onchange="this.closest('.checklist-item').classList.toggle('done',this.checked)">
      <span>${item}</span>
    </label>
  `).join('');
    }

    // ══════════════════════════════════════════════════════
    // STORAGE
    // ══════════════════════════════════════════════════════
    function getFormData() {
        const g = id => document.getElementById(id)?.value || '';
        return {
            ts: Date.now(),
            participante: {
                codigo:g('p-codigo'), data:g('p-data'), hora:g('p-hora'),
                moderador:g('p-moderador'), perfil:g('p-perfil'), idade:g('p-idade'),
                experiencia:g('p-experiencia'), uso_prev:g('p-uso-prev'), obs:g('p-obs')
            },
            tarefas: { resultados: JSON.parse(JSON.stringify(taskRes)), obs:g('obs-tarefas') },
            sus: { respostas:[...susAns], score:getSUSScore() },
            qualitativo: Object.fromEntries(
                Array.from({length:11},(_,i)=>[`q${i+1}`,g(`q${i+1}`)])
            )
        };
    }

    function autoSave() {
        localStorage.setItem('SISGEM_draft', JSON.stringify(getFormData()));
        document.getElementById('save-status').textContent = 'Salvo ' + new Date().toLocaleTimeString('pt-BR');
    }

    function loadDraft() {
        try {
            const raw = localStorage.getItem('SISGEM_draft');
            if (!raw) return;
            const d = JSON.parse(raw);
            const set = (id,val) => { const el=document.getElementById(id); if(el&&val!==undefined&&val!=='') el.value=val; };
            const p = d.participante||{};
            ['codigo','data','hora','moderador','perfil','idade','experiencia'].forEach(k => set(`p-${k}`, p[k]));
            set('p-uso-prev', p.uso_prev); set('p-obs', p.obs);
            set('obs-tarefas', d.tarefas?.obs);
            if (d.tarefas?.resultados) {
                taskRes = d.tarefas.resultados;
                Object.entries(taskRes).forEach(([tid,r]) => {
                    if (r.result) setResult(tid, r.result);
                    const tel = document.getElementById(`time-${tid}`);
                    if (tel&&r.time) tel.value = r.time;
                    const eel = document.getElementById(`err-${tid}`);
                    if (eel&&r.errorLevel) eel.value = r.errorLevel;
                });
            }
            if (d.sus?.respostas) d.sus.respostas.forEach((v,i)=>{ if(v) setSUS(i,v); });
            for(let i=1;i<=11;i++) set(`q${i}`, d.qualitativo?.[`q${i}`]);
        } catch(e) { console.error(e); }
    }

    // ══════════════════════════════════════════════════════
    // SESSIONS
    // ══════════════════════════════════════════════════════
    function getSessions() {
        try { return JSON.parse(localStorage.getItem('SISGEM_sessions')||'[]'); } catch { return []; }
    }
    function updateBadges() {
        document.getElementById('badge-sessoes').textContent = getSessions().length;
    }

    function saveSession() {
        const data = getFormData();
        const codigo = data.participante.codigo || ('P'+String(getSessions().length+1).padStart(2,'0'));
        data.participante.codigo = codigo;
        const sessions = getSessions();
        const idx = sessions.findIndex(s => s.participante.codigo === codigo);
        if (idx>=0) sessions[idx]=data; else sessions.push(data);
        localStorage.setItem('SISGEM_sessions', JSON.stringify(sessions));
        updateBadges();
        toast('✦ Sessão ' + codigo + ' salva');
    }

    function renderSessions() {
        const sessions = getSessions();
        const list = document.getElementById('sessions-list');
        if (!sessions.length) {
            list.innerHTML = '<div class="empty-state">Nenhuma sessão salva ainda.<br>Complete um teste e clique em "Salvar sessão".</div>';
            return;
        }
        list.innerHTML = sessions.map((s,i) => {
            const p = s.participante;
            const score = s.sus?.score;
            const { label, color } = score!=null ? scoreInfo(score) : { label:'—', color:'#8AA898' };
            const concl = Object.values(s.tarefas?.resultados||{}).filter(r=>r.result==='Concluída').length;
            const initials = (p.codigo||'P?').slice(0,2).toUpperCase();
            return `
      <div class="session-row">
        <div class="s-avatar">${initials}</div>
        <div>
          <div class="s-name">${p.codigo||'—'} · ${p.perfil||'perfil não informado'}</div>
          <div class="s-meta">${p.data||'—'} · ${p.hora||''} · ${concl}/19 concluídas</div>
        </div>
        <span class="s-score" style="background:${color}18;color:${color}">SUS ${score??'—'}</span>
        <div class="s-actions">
          <button class="btn" style="padding:3px 10px;font-size:.72rem" onclick="loadSession(${i})">Carregar</button>
          <button class="btn btn-danger" style="padding:3px 10px;font-size:.72rem" onclick="deleteSession(${i})">✕</button>
        </div>
      </div>`;
        }).join('');
    }

    function loadSession(i) {
        const s = getSessions()[i];
        localStorage.setItem('SISGEM_draft', JSON.stringify(s));
        loadDraft();
        goTo('info');
        toast('Sessão carregada: ' + (s.participante?.codigo||''));
    }
    function deleteSession(i) {
        const sessions = getSessions();
        const cod = sessions[i]?.participante?.codigo||i;
        sessions.splice(i,1);
        localStorage.setItem('SISGEM_sessions', JSON.stringify(sessions));
        updateBadges();
        renderSessions();
        toast('Sessão ' + cod + ' removida');
    }
    function clearAll() {
        if (!confirm('Apagar TODAS as sessões? Esta ação não pode ser desfeita.')) return;
        localStorage.removeItem('SISGEM_sessions');
        updateBadges(); renderSessions();
        toast('Todas as sessões apagadas');
    }
    function clearDraft() {
        if (!confirm('Limpar a sessão atual?')) return;
        localStorage.removeItem('SISGEM_draft');
        location.reload();
    }

    // ══════════════════════════════════════════════════════
    // EXPORT
    // ══════════════════════════════════════════════════════
    function download(content, filename, mime) {
        const a = document.createElement('a');
        a.href = URL.createObjectURL(new Blob([content],{type:mime}));
        a.download = filename; a.click();
    }
    function exportJSON() {
        download(JSON.stringify(getFormData(),null,2),
            'SISGEM_'+(document.getElementById('p-codigo')?.value||'draft')+'.json','application/json');
        toast('↓ JSON exportado');
    }
    function exportAllJSON() {
        download(JSON.stringify(getSessions(),null,2),'SISGEM_todas_sessoes.json','application/json');
        toast('↓ Todas as sessões exportadas');
    }
    function exportCSV() {
        const sessions = getSessions();
        if (!sessions.length) { toast('Nenhuma sessão para exportar'); return; }
        const taskIds = TASKS.map(t=>t.id);
        const header = ['codigo','data','perfil','sus_score','sus_grade',
            ...taskIds.map(t=>t+'_resultado'),...taskIds.map(t=>t+'_tempo'),...taskIds.map(t=>t+'_erro')];
        const rows = sessions.map(s => {
            const p=s.participante, sc=s.sus?.score;
            const grade = sc==null?'':sc>=90?'Excelente':sc>=80?'Bom':sc>=70?'OK':sc>=60?'Pobre':'Inaceitável';
            return [p.codigo,p.data,p.perfil,sc??'',grade,
                ...taskIds.map(t=>s.tarefas?.resultados?.[t]?.result||''),
                ...taskIds.map(t=>s.tarefas?.resultados?.[t]?.time||''),
                ...taskIds.map(t=>s.tarefas?.resultados?.[t]?.errorLevel||''),
            ].map(c=>'"'+String(c).replace(/"/g,'""')+'"').join(',');
        });
        download([header.join(','),...rows].join('\n'),'SISGEM_resultados.csv','text/csv');
        toast('↓ CSV exportado');
    }

    // ══════════════════════════════════════════════════════
    // ANALYSIS
    // ══════════════════════════════════════════════════════
    function renderAnalysis() {
        const sessions = getSessions();
        const el = document.getElementById('analise-content');

        if (!sessions.length) {
            el.innerHTML = '<div class="card"><p style="color:var(--faint);font-style:italic;text-align:center;padding:2rem">Salve ao menos uma sessão para gerar a análise.</p></div>';
            return;
        }

        // Métricas gerais
        const n = sessions.length;
        const scores = sessions.map(s=>s.sus?.score).filter(s=>s!=null);
        const avgSUS = scores.length ? Math.round(scores.reduce((a,b)=>a+b,0)/scores.length) : null;

        // Taxa de conclusão por tarefa
        const taskCompletion = TASKS.map(t => {
            const results = sessions.map(s => s.tarefas?.resultados?.[t.id]?.result).filter(Boolean);
            const ok = results.filter(r=>r==='Concluída').length;
            const total = results.filter(r=>r!=='N/A').length;
            return { id:t.id, label:t.label.slice(0,40)+'…', ok, total, pct: total>0?Math.round((ok/total)*100):null };
        });

        // Tempo médio por tarefa
        const taskTimes = TASKS.map(t => {
            const times = sessions.map(s=>parseFloat(s.tarefas?.resultados?.[t.id]?.time)).filter(v=>!isNaN(v)&&v>0);
            return { id:t.id, avg: times.length ? Math.round(times.reduce((a,b)=>a+b,0)/times.length) : null };
        });

        // Distribuição de erros
        const errorDist = {1:0,2:0,3:0,4:0,0:0};
        sessions.forEach(s => {
            Object.values(s.tarefas?.resultados||{}).forEach(r => {
                if (r.errorLevel) errorDist[r.errorLevel] = (errorDist[r.errorLevel]||0)+1;
            });
        });

        // SUS por sessão
        const susPerSession = sessions.map(s => ({
            cod: s.participante?.codigo||'?',
            score: s.sus?.score
        })).filter(s=>s.score!=null);

        el.innerHTML = `
    <div class="analysis-section">
      <div class="metric-grid">
        <div class="metric-card">
          <div class="metric-val">${n}</div>
          <div class="metric-lbl">Sessões realizadas</div>
        </div>
        <div class="metric-card">
          <div class="metric-val" style="color:${avgSUS!=null?scoreInfo(avgSUS).color:'var(--faint)'}">${avgSUS??'—'}</div>
          <div class="metric-lbl">SUS médio</div>
        </div>
        <div class="metric-card">
          <div class="metric-val">${taskCompletion.filter(t=>t.pct!=null&&t.pct<70).length}</div>
          <div class="metric-lbl">Tarefas críticas (&lt;70%)</div>
        </div>
        <div class="metric-card">
          <div class="metric-val">${errorDist[1]||0}</div>
          <div class="metric-lbl">Erros catastróficos</div>
        </div>
      </div>
    </div>

    <div class="analysis-section">
      <h3>Pontuação SUS por participante</h3>
      <div class="chart-wrap">
        <div class="chart-title">SUS Score individual</div>
        <canvas id="chart-sus" height="80"></canvas>
      </div>
    </div>

    <div class="analysis-section">
      <h3>Taxa de conclusão por tarefa (%)</h3>
      <div class="chart-wrap">
        <div class="chart-title">% tarefas concluídas com sucesso</div>
        <canvas id="chart-completion" height="140"></canvas>
      </div>
    </div>

    <div class="analysis-section">
      <h3>Tempo médio por tarefa (segundos)</h3>
      <div class="chart-wrap">
        <div class="chart-title">Tempo médio de conclusão</div>
        <canvas id="chart-time" height="140"></canvas>
      </div>
    </div>

    <div class="analysis-section">
      <h3>Distribuição de erros por nível</h3>
      <div class="chart-wrap" style="max-width:340px">
        <div class="chart-title">Incidência por nível de severidade</div>
        <canvas id="chart-errors" height="160"></canvas>
      </div>
    </div>

    <div class="analysis-section">
      <h3>Detalhamento por tarefa</h3>
      <div class="card" style="padding:0;overflow:hidden">
        <table class="task-result-table">
          <thead><tr>
            <th>Tarefa</th><th>Módulo</th><th>Conclusão</th><th>Tempo médio (s)</th><th>Erros</th>
          </tr></thead>
          <tbody>
            ${taskCompletion.map((t,i)=>{
            const pct = t.pct;
            const color = pct==null?'var(--faint)':pct>=80?'var(--green-600)':pct>=60?'var(--amber-600)':'var(--red-600)';
            const taskMod = TASKS[i].mod;
            const errCount = sessions.reduce((acc,s)=>{
                const er = s.tarefas?.resultados?.[t.id]?.errorLevel;
                return acc + (er&&er!=='0'?1:0);
            },0);
            return `<tr>
                <td><strong>${t.id}</strong></td>
                <td><span class="task-module ${TASKS[i].cls}" style="font-size:.62rem">${taskMod}</span></td>
                <td style="color:${color};font-weight:700">${pct!=null?pct+'%':'—'}</td>
                <td>${taskTimes[i].avg!=null?taskTimes[i].avg+'s':'—'}</td>
                <td>${errCount>0?errCount:'—'}</td>
              </tr>`;
        }).join('')}
          </tbody>
        </table>
      </div>
    </div>

    <div class="analysis-section">
      <h3>Relatório automático com IA</h3>
      <div style="margin-bottom:.75rem">
        <button class="btn btn-accent" onclick="generateAIReport()">✦ Gerar relatório com IA →</button>
        <button class="btn" onclick="exportReportTXT()" id="btn-export-report" style="display:none">↓ Exportar relatório (.txt)</button>
      </div>
      <div id="ai-report" class="ai-report" style="display:none"></div>
    </div>
  `;

        // Destruir charts antigos
        Object.values(charts).forEach(c => c.destroy?.());
        charts = {};

        const GREEN = 'rgba(42,144,96,0.85)';
        const GREEN_LIGHT = 'rgba(42,144,96,0.2)';
        const AMBER = 'rgba(217,119,6,0.85)';
        const RED = 'rgba(155,32,32,0.85)';

        // Chart SUS individual
        if (susPerSession.length) {
            charts.sus = new Chart(document.getElementById('chart-sus'), {
                type:'bar',
                data:{
                    labels: susPerSession.map(s=>s.cod),
                    datasets:[{
                        label:'SUS Score',
                        data: susPerSession.map(s=>s.score),
                        backgroundColor: susPerSession.map(s=>s.score>=80?GREEN:s.score>=70?AMBER:RED),
                        borderRadius:6, borderSkipped:false
                    }]
                },
                options:{ plugins:{legend:{display:false}}, scales:{y:{min:0,max:100,grid:{color:'rgba(0,0,0,.05)'}}}, responsive:true }
            });
        }

        // Chart conclusão
        charts.completion = new Chart(document.getElementById('chart-completion'), {
            type:'bar',
            data:{
                labels: taskCompletion.map(t=>t.id),
                datasets:[{
                    label:'% conclusão',
                    data: taskCompletion.map(t=>t.pct),
                    backgroundColor: taskCompletion.map(t=>
                        t.pct==null?'rgba(0,0,0,.1)':t.pct>=80?GREEN:t.pct>=60?AMBER:RED),
                    borderRadius:4, borderSkipped:false
                }]
            },
            options:{
                plugins:{legend:{display:false}},
                scales:{y:{min:0,max:100,grid:{color:'rgba(0,0,0,.05)'},ticks:{callback:v=>v+'%'}}},
                responsive:true
            }
        });

        // Chart tempo
        const hasTime = taskTimes.some(t=>t.avg!=null);
        if (hasTime) {
            charts.time = new Chart(document.getElementById('chart-time'), {
                type:'bar',
                data:{
                    labels: taskTimes.map(t=>t.id),
                    datasets:[{
                        label:'Tempo médio (s)',
                        data: taskTimes.map(t=>t.avg),
                        backgroundColor: GREEN_LIGHT,
                        borderColor: GREEN,
                        borderWidth:2,
                        borderRadius:4, borderSkipped:false
                    }]
                },
                options:{
                    plugins:{legend:{display:false}},
                    scales:{y:{grid:{color:'rgba(0,0,0,.05)'}}},
                    responsive:true
                }
            });
        }

        // Chart erros
        charts.errors = new Chart(document.getElementById('chart-errors'), {
            type:'doughnut',
            data:{
                labels:['Catastrófico (1)','Sério (2)','Menor (3)','Cosmético (4)','Sem erro'],
                datasets:[{
                    data:[errorDist[1]||0,errorDist[2]||0,errorDist[3]||0,errorDist[4]||0,errorDist[0]||0],
                    backgroundColor:[RED,AMBER,'rgba(42,144,96,.6)',GREEN_LIGHT,'rgba(0,0,0,.08)'],
                    borderWidth:0
                }]
            },
            options:{plugins:{legend:{position:'right'}},responsive:true}
        });
    }

    // ══════════════════════════════════════════════════════
    // AI REPORT
    // ══════════════════════════════════════════════════════
    async function generateAIReport() {
        const sessions = getSessions();
        if (!sessions.length) { toast('Salve ao menos uma sessão primeiro'); return; }

        const reportEl = document.getElementById('ai-report');
        const exportBtn = document.getElementById('btn-export-report');
        reportEl.style.display = 'block';
        reportEl.className = 'ai-report loading';
        reportEl.textContent = 'Analisando dados das sessões…';

        const n = sessions.length;
        const scores = sessions.map(s=>s.sus?.score).filter(s=>s!=null);
        const avgSUS = scores.length ? Math.round(scores.reduce((a,b)=>a+b,0)/scores.length) : null;

        const taskSummary = TASKS.map(t => {
            const results = sessions.map(s=>s.tarefas?.resultados?.[t.id]?.result).filter(Boolean);
            const ok = results.filter(r=>r==='Concluída').length;
            const total = results.filter(r=>r!=='N/A').length;
            const pct = total>0?Math.round((ok/total)*100):null;
            const times = sessions.map(s=>parseFloat(s.tarefas?.resultados?.[t.id]?.time)).filter(v=>!isNaN(v)&&v>0);
            const avgTime = times.length?Math.round(times.reduce((a,b)=>a+b,0)/times.length):null;
            const errs = sessions.map(s=>s.tarefas?.resultados?.[t.id]?.errorLevel).filter(e=>e&&e!=='0');
            return `${t.id} (${t.mod}): ${pct!=null?pct+'% conclusão':'sem dados'}, tempo médio ${avgTime!=null?avgTime+'s':'—'}, erros: [${errs.join(',')||'nenhum'}]`;
        }).join('\n');

        const qualSummary = sessions.slice(0,5).map((s,i) => {
            const q = s.qualitativo||{};
            const resp = [q.q1,q.q2,q.q3].filter(Boolean).join(' | ');
            return `P${i+1}: ${resp||'sem respostas'}`;
        }).join('\n');

        const prompt = `Você é um especialista em UX e avaliação de sistemas. Analise os seguintes dados de teste de usabilidade do sistema SISGEM (gestão de merenda escolar com autenticação LDAP) e produza um relatório estruturado em português.

DADOS DA AVALIAÇÃO:
- Número de participantes: ${n}
- Pontuação SUS média: ${avgSUS!=null?avgSUS+'/100':'não disponível'}
- Scores individuais: ${scores.join(', ')||'não disponível'}

RESULTADOS POR TAREFA (19 tarefas):
${taskSummary}

RESPOSTAS QUALITATIVAS (amostra):
${qualSummary}

Produza um relatório com as seguintes seções claramente delimitadas:

1. SUMÁRIO EXECUTIVO (3-4 linhas)
2. AVALIAÇÃO GERAL DE USABILIDADE (SUS e contexto)
3. PONTOS FORTES IDENTIFICADOS (máx. 4 itens)
4. PROBLEMAS CRÍTICOS E RECOMENDAÇÕES (ordenados por prioridade, máx. 6 itens)
5. ANÁLISE POR MÓDULO (área pública, autenticação, gestão escolar, contratos, merenda, dados)
6. RECOMENDAÇÕES DE REDESIGN (curto, médio e longo prazo)
7. PRÓXIMOS PASSOS SUGERIDOS

Seja objetivo, preciso e forneça recomendações acionáveis. Base suas análises nos dados fornecidos.`;

        try {
            const res = await fetch('https://api.anthropic.com/v1/messages', {
                method:'POST',
                headers:{'Content-Type':'application/json'},
                body:JSON.stringify({
                    model:'claude-sonnet-4-20250514',
                    max_tokens:1000,
                    messages:[{role:'user',content:prompt}]
                })
            });
            const data = await res.json();
            const text = data.content?.map(b=>b.text||'').join('')||'Erro ao gerar relatório.';
            reportEl.className = 'ai-report';
            reportEl.textContent = text;
            exportBtn.style.display = 'inline-flex';
            window._lastReport = text;
        } catch(e) {
            reportEl.className = 'ai-report';
            reportEl.textContent = 'Erro ao conectar com a API. Verifique sua conexão e tente novamente.\n\n'+e.message;
        }
    }

    function exportReportTXT() {
        if (!window._lastReport) return;
        download(window._lastReport, 'SISGEM_relatorio_usabilidade.txt', 'text/plain');
        toast('↓ Relatório exportado');
    }

    // ══════════════════════════════════════════════════════
    // TOAST
    // ══════════════════════════════════════════════════════
    function toast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(()=>t.classList.remove('show'), 2800);
    }
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISGEM — Protocolo de Teste de Usabilidade</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700&family=Literata:ital,wght@0,300;0,400;0,500;1,300;1,400&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{
            --green-900:#0D2B1A;--green-800:#14422A;--green-700:#1B5C3B;
            --green-600:#22754C;--green-500:#2A9060;--green-400:#3DAE77;
            --green-200:#A8DFC0;--green-100:#D4EFE0;--green-50:#EEF8F3;
            --amber-600:#A05C00;--amber-100:#FEF3DC;--amber-50:#FFFAF0;
            --red-600:#9B2020;--red-100:#FDEAEA;--red-50:#FFF5F5;
            --blue-600:#1A4EA0;--blue-100:#E3ECFD;
            --text:#0F1F16;--muted:#4A6358;--faint:#8AA898;
            --bg:#F3F7F4;--surface:#FFFFFF;--surface2:#EDF4EF;
            --border:#D5E5DB;--border2:#B0CEBC;
            --radius:10px;--radius-lg:16px;--radius-xl:22px;
            --shadow:0 1px 4px rgba(15,31,22,.06),0 6px 20px rgba(15,31,22,.07);
            --shadow-lg:0 2px 8px rgba(15,31,22,.08),0 12px 40px rgba(15,31,22,.1);
        }
        html{font-size:15px;scroll-behavior:smooth}
        body{font-family:'Literata',Georgia,serif;background:var(--bg);color:var(--text);min-height:100vh;line-height:1.65}

        /* ── Layout ── */
        .wrap{display:flex;min-height:100vh}

        /* ── Sidebar ── */
        .sidebar{
            width:256px;flex-shrink:0;
            background:var(--green-900);
            display:flex;flex-direction:column;
            position:sticky;top:0;height:100vh;overflow-y:auto;
        }
        .sidebar-brand{
            padding:1.75rem 1.5rem 1.5rem;
            border-bottom:1px solid rgba(255,255,255,.08);
        }
        .brand-logo{
            display:flex;align-items:center;gap:10px;margin-bottom:4px;
        }
        .brand-icon{
            width:36px;height:36px;border-radius:10px;
            background:var(--green-500);
            display:flex;align-items:center;justify-content:center;
            font-size:1.1rem;flex-shrink:0;
        }
        .brand-name{
            font-family:'Syne',sans-serif;font-size:1.25rem;font-weight:700;
            color:#fff;letter-spacing:-.02em;
        }
        .brand-sub{
            font-size:.7rem;color:rgba(255,255,255,.35);
            letter-spacing:.12em;text-transform:uppercase;
            font-family:'Syne',sans-serif;margin-top:2px;
        }
        .nav-group{
            padding:.5rem 0;border-bottom:1px solid rgba(255,255,255,.06);
        }
        .nav-label{
            padding:.75rem 1.5rem .3rem;
            font-family:'Syne',sans-serif;
            font-size:.64rem;letter-spacing:.14em;text-transform:uppercase;
            color:rgba(255,255,255,.25);font-weight:600;
        }
        .nav-item{
            display:flex;align-items:center;gap:10px;
            padding:.55rem 1.5rem;
            font-family:'Syne',sans-serif;font-size:.82rem;font-weight:500;
            color:rgba(255,255,255,.55);cursor:pointer;
            border-left:3px solid transparent;
            transition:all .15s;user-select:none;
        }
        .nav-item:hover{color:#fff;background:rgba(255,255,255,.05)}
        .nav-item.active{
            color:#fff;border-left-color:var(--green-400);
            background:rgba(61,174,119,.12);
        }
        .nav-icon{font-size:.9rem;width:18px;text-align:center;opacity:.7}
        .nav-item.active .nav-icon{opacity:1}
        .nav-badge{
            margin-left:auto;font-size:.65rem;padding:1px 7px;
            border-radius:100px;background:rgba(255,255,255,.12);
            color:rgba(255,255,255,.6);font-family:'Syne',sans-serif;
        }
        .sidebar-footer{
            margin-top:auto;padding:1rem 1.5rem;
            border-top:1px solid rgba(255,255,255,.08);
        }
        .save-row{
            display:flex;align-items:center;gap:6px;
            font-size:.72rem;color:rgba(255,255,255,.35);
            font-family:'Syne',sans-serif;
        }
        .save-dot{
            width:6px;height:6px;border-radius:50%;
            background:var(--green-400);
            animation:pulse 2.5s ease-in-out infinite;
        }
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.3}}

        /* ── Topbar ── */
        .topbar{
            display:flex;align-items:center;gap:1rem;
            padding:.9rem 2.5rem;
            background:var(--surface);
            border-bottom:1px solid var(--border);
            position:sticky;top:0;z-index:20;
        }
        .topbar-title{
            font-family:'Syne',sans-serif;font-size:.95rem;font-weight:600;
            color:var(--text);flex:1;
        }
        .topbar-actions{display:flex;gap:.5rem}

        /* ── Main ── */
        .main{flex:1;padding:2rem 2.5rem;min-width:0;max-width:880px}
        .page-header{margin-bottom:1.75rem}
        .page-header h1{
            font-family:'Syne',sans-serif;font-size:1.65rem;font-weight:700;
            color:var(--text);letter-spacing:-.03em;line-height:1.2;
        }
        .page-header p{
            color:var(--muted);font-size:.875rem;margin-top:.4rem;
            font-style:italic;
        }

        /* ── Panel ── */
        .panel{display:none;animation:fadeUp .2s ease}
        .panel.active{display:block}
        @keyframes fadeUp{from{opacity:0;transform:translateY(5px)}to{opacity:1;transform:translateY(0)}}

        /* ── Cards ── */
        .card{
            background:var(--surface);border:1px solid var(--border);
            border-radius:var(--radius-lg);padding:1.5rem;
            margin-bottom:.875rem;box-shadow:var(--shadow);
        }
        .card-sm{padding:1rem 1.25rem}
        .card-title{
            font-family:'Syne',sans-serif;font-size:.68rem;font-weight:600;
            text-transform:uppercase;letter-spacing:.12em;
            color:var(--faint);margin-bottom:1rem;
        }

        /* ── Form Controls ── */
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:.75rem}
        .form-group{display:flex;flex-direction:column;gap:.3rem}
        .form-group label{font-family:'Syne',sans-serif;font-size:.72rem;font-weight:600;color:var(--muted)}
        .form-control{
            padding:.55rem .85rem;border:1px solid var(--border);
            border-radius:var(--radius);
            font-family:'Literata',serif;font-size:.875rem;color:var(--text);
            background:var(--surface);transition:border .15s,box-shadow .15s;width:100%;
        }
        .form-control:focus{outline:none;border-color:var(--green-500);box-shadow:0 0 0 3px rgba(42,144,96,.12)}
        select.form-control{cursor:pointer}
        textarea.form-control{resize:vertical;min-height:75px;line-height:1.65}
        .form-full{grid-column:1/-1}

        /* ── Section divider ── */
        .divider{
            font-family:'Syne',sans-serif;font-size:.66rem;font-weight:600;
            text-transform:uppercase;letter-spacing:.12em;color:var(--faint);
            margin:1.5rem 0 .75rem;padding-bottom:.5rem;
            border-bottom:1px solid var(--border);
        }

        /* ── Task Items ── */
        .task-item{
            background:var(--surface);border:1px solid var(--border);
            border-radius:var(--radius-lg);margin-bottom:.75rem;
            overflow:hidden;transition:box-shadow .2s;
        }
        .task-item:hover{box-shadow:var(--shadow)}
        .task-head{padding:1rem 1.25rem;display:flex;align-items:flex-start;gap:.875rem}
        .task-num{
            width:34px;height:34px;border-radius:9px;
            background:var(--green-900);color:#fff;
            display:flex;align-items:center;justify-content:center;
            font-family:'Syne',sans-serif;font-size:.72rem;font-weight:700;
            flex-shrink:0;margin-top:1px;letter-spacing:.02em;
        }
        .task-body{flex:1}
        .task-scenario{
            font-size:.72rem;color:var(--faint);font-family:'Syne',sans-serif;
            font-weight:600;letter-spacing:.06em;text-transform:uppercase;
            margin-bottom:3px;
        }
        .task-label{
            font-size:.95rem;font-weight:500;color:var(--text);
            font-style:italic;line-height:1.4;
        }
        .task-criterion{font-size:.78rem;color:var(--muted);margin-top:.3rem;line-height:1.5}
        .task-tip{font-size:.73rem;color:var(--faint);margin-top:.2rem;font-style:italic}
        .task-module{
            padding:3px 10px;border-radius:100px;
            font-family:'Syne',sans-serif;font-size:.66rem;font-weight:700;
            letter-spacing:.05em;flex-shrink:0;white-space:nowrap;
        }
        .mod-publico{background:var(--green-100);color:var(--green-700)}
        .mod-auth{background:var(--blue-100);color:var(--blue-600)}
        .mod-gestao{background:var(--amber-100);color:var(--amber-600)}
        .mod-contrato{background:#EDE9FE;color:#4C2A99}
        .mod-merenda{background:var(--green-50);color:var(--green-600)}
        .mod-dados{background:#F0F9FF;color:#075985}

        .task-footer{
            padding:.7rem 1.25rem;background:var(--bg);
            border-top:1px solid var(--border);
            display:flex;align-items:center;gap:1rem;flex-wrap:wrap;
        }
        .result-label{font-family:'Syne',sans-serif;font-size:.68rem;font-weight:600;color:var(--muted)}
        .result-options{display:flex;gap:.4rem;flex-wrap:wrap}
        .r-btn{
            padding:3px 11px;border-radius:100px;
            border:1px solid var(--border);background:var(--surface);
            font-family:'Syne',sans-serif;font-size:.68rem;font-weight:600;
            color:var(--muted);cursor:pointer;transition:all .15s;
        }
        .r-btn:hover{border-color:var(--border2);color:var(--text)}
        .r-btn.ok{background:var(--green-100);border-color:var(--green-500);color:var(--green-700)}
        .r-btn.parcial{background:var(--amber-100);border-color:var(--amber-600);color:var(--amber-600)}
        .r-btn.falha{background:var(--red-100);border-color:var(--red-600);color:var(--red-600)}
        .time-field{margin-left:auto;display:flex;align-items:center;gap:.5rem}
        .time-field label{font-family:'Syne',sans-serif;font-size:.68rem;color:var(--muted);font-weight:600}
        .time-input{
            width:68px;padding:3px 8px;border:1px solid var(--border);
            border-radius:6px;font-size:.8rem;font-family:'Syne',sans-serif;
            text-align:center;background:var(--surface);color:var(--text);
        }
        .time-input:focus{outline:none;border-color:var(--green-500)}
        .error-level{display:flex;align-items:center;gap:.4rem;margin-left:.5rem}
        .error-level label{font-family:'Syne',sans-serif;font-size:.68rem;color:var(--muted);font-weight:600}
        .error-select{
            padding:2px 6px;border:1px solid var(--border);border-radius:6px;
            font-size:.72rem;font-family:'Syne',sans-serif;background:var(--surface);color:var(--text);cursor:pointer;
        }
        .error-select:focus{outline:none;border-color:var(--green-500)}

        /* ── SUS ── */
        .sus-intro{
            background:var(--green-50);border:1px solid var(--green-200);
            border-radius:var(--radius);padding:1rem 1.25rem;
            font-size:.875rem;color:var(--green-800);
            margin-bottom:1.25rem;line-height:1.7;
        }
        .sus-question{
            padding:1rem 1.5rem;border-bottom:1px solid var(--border);
            display:flex;align-items:center;gap:1.25rem;
        }
        .sus-question:last-child{border-bottom:none}
        .sus-q-num{
            font-family:'Syne',sans-serif;font-size:.66rem;font-weight:700;
            color:var(--faint);width:22px;flex-shrink:0;
        }
        .sus-q-text{flex:1;font-size:.875rem;line-height:1.55}
        .sus-q-type{
            font-family:'Syne',sans-serif;font-size:.63rem;font-weight:700;
            padding:2px 7px;border-radius:4px;flex-shrink:0;letter-spacing:.04em;
        }
        .type-pos{background:var(--green-100);color:var(--green-700)}
        .type-neg{background:var(--red-100);color:var(--red-600)}
        .sus-scale{display:flex;gap:3px;flex-shrink:0}
        .sus-btn{
            width:32px;height:32px;border-radius:8px;
            border:1px solid var(--border);background:var(--surface);
            font-family:'Syne',sans-serif;font-size:.78rem;font-weight:600;
            color:var(--muted);cursor:pointer;transition:all .15s;
        }
        .sus-btn:hover{border-color:var(--green-500);color:var(--green-600)}
        .sus-btn.sel{background:var(--green-700);border-color:var(--green-700);color:#fff}
        .scale-legend{
            display:flex;justify-content:space-between;
            padding:.35rem 1.5rem .35rem calc(1.5rem + 22px + 1.25rem);
            font-family:'Syne',sans-serif;font-size:.65rem;color:var(--faint);font-weight:600;
        }

        /* ── Score ── */
        .score-wrap{display:grid;grid-template-columns:200px 1fr;gap:1rem;margin-top:1.25rem}
        .score-card{
            background:var(--green-900);border-radius:var(--radius-lg);
            padding:1.5rem;text-align:center;color:#fff;
        }
        .score-num{
            font-family:'Syne',sans-serif;font-size:3.5rem;font-weight:700;
            color:#fff;line-height:1;letter-spacing:-.04em;
        }
        .score-lbl{font-size:.65rem;color:rgba(255,255,255,.4);margin-top:.3rem;letter-spacing:.1em;text-transform:uppercase;font-family:'Syne',sans-serif}
        .score-grade{font-family:'Syne',sans-serif;font-size:.88rem;font-weight:600;margin-top:.6rem}
        .score-bar-bg{background:rgba(255,255,255,.1);border-radius:100px;height:4px;margin-top:.5rem}
        .score-bar{height:4px;border-radius:100px;background:var(--green-400);transition:width .7s ease}
        .sus-ref{border-collapse:collapse;width:100%}
        .sus-ref th{
            text-align:left;padding:.4rem 0;border-bottom:1px solid var(--border);
            font-family:'Syne',sans-serif;font-size:.65rem;font-weight:700;
            text-transform:uppercase;letter-spacing:.09em;color:var(--faint);
        }
        .sus-ref td{
            padding:.45rem 0;border-bottom:1px solid var(--border);
            font-size:.82rem;color:var(--muted);
        }
        .sus-ref td:first-child{font-family:'Syne',sans-serif;font-weight:700;color:var(--text)}
        .sus-ref tr:last-child td{border-bottom:none}
        .sus-hl{background:var(--green-50)}
        .sus-hl td{color:var(--green-700)!important;font-weight:700!important}

        /* ── Qual ── */
        .qual-q{margin-bottom:.875rem}
        .qual-q label{display:block;font-family:'Syne',sans-serif;font-size:.75rem;font-weight:600;color:var(--muted);margin-bottom:.3rem}

        /* ── Moderador ── */
        .checklist-item{
            display:flex;align-items:flex-start;gap:.75rem;
            padding:.6rem 0;border-bottom:1px solid var(--border);cursor:pointer;
        }
        .checklist-item:last-child{border-bottom:none}
        .checklist-item input[type=checkbox]{width:15px;height:15px;margin-top:2px;accent-color:var(--green-600);flex-shrink:0}
        .checklist-item span{font-size:.875rem;line-height:1.55}
        .checklist-item.done span{text-decoration:line-through;color:var(--faint)}
        .script-box{
            background:var(--surface2);border-left:3px solid var(--green-400);
            border-radius:0 var(--radius) var(--radius) 0;
            padding:1rem 1.25rem;font-size:.875rem;font-style:italic;
            color:var(--muted);line-height:1.85;margin:.75rem 0;
        }

        /* ── Sessions ── */
        .session-row{
            display:flex;align-items:center;gap:1rem;
            padding:.75rem 1.25rem;border-bottom:1px solid var(--border);transition:background .15s;
        }
        .session-row:last-child{border-bottom:none}
        .session-row:hover{background:var(--bg)}
        .s-avatar{
            width:36px;height:36px;border-radius:50%;background:var(--green-700);
            color:#fff;display:flex;align-items:center;justify-content:center;
            font-family:'Syne',sans-serif;font-size:.72rem;font-weight:700;flex-shrink:0;
        }
        .s-name{font-family:'Syne',sans-serif;font-size:.9rem;font-weight:600}
        .s-meta{font-size:.75rem;color:var(--muted);margin-top:1px}
        .s-score{
            margin-left:auto;padding:3px 10px;border-radius:100px;
            font-family:'Syne',sans-serif;font-size:.72rem;font-weight:700;
        }
        .s-actions{display:flex;gap:.4rem}
        .empty-state{
            text-align:center;padding:3rem 1rem;
            color:var(--faint);font-size:.875rem;font-style:italic;
        }

        /* ── Análise ── */
        .analysis-section{margin-bottom:1.5rem}
        .analysis-section h3{
            font-family:'Syne',sans-serif;font-size:.9rem;font-weight:700;
            color:var(--green-800);margin-bottom:.75rem;letter-spacing:-.01em;
        }
        .metric-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;margin-bottom:1rem}
        .metric-card{
            background:var(--surface2);border-radius:var(--radius);
            padding:.875rem;text-align:center;
        }
        .metric-val{
            font-family:'Syne',sans-serif;font-size:1.75rem;font-weight:700;
            color:var(--green-700);line-height:1;
        }
        .metric-lbl{font-size:.7rem;color:var(--muted);margin-top:.3rem;font-style:italic}
        .chart-wrap{
            background:var(--surface);border:1px solid var(--border);
            border-radius:var(--radius-lg);padding:1.25rem;margin-bottom:.875rem;
        }
        .chart-title{font-family:'Syne',sans-serif;font-size:.75rem;font-weight:700;color:var(--muted);margin-bottom:1rem;text-transform:uppercase;letter-spacing:.08em}
        .ai-report{
            background:var(--green-50);border:1px solid var(--green-200);
            border-radius:var(--radius-lg);padding:1.5rem;
            font-size:.875rem;line-height:1.8;color:var(--green-900);
            white-space:pre-wrap;font-style:italic;
        }
        .ai-report.loading{color:var(--green-600);animation:blink 1s ease-in-out infinite}
        @keyframes blink{0%,100%{opacity:1}50%{opacity:.5}}
        .task-result-table{width:100%;border-collapse:collapse;font-size:.82rem}
        .task-result-table th{
            text-align:left;padding:.4rem .6rem;border-bottom:1px solid var(--border);
            font-family:'Syne',sans-serif;font-size:.65rem;font-weight:700;
            text-transform:uppercase;letter-spacing:.08em;color:var(--faint);
            background:var(--bg);
        }
        .task-result-table td{padding:.45rem .6rem;border-bottom:1px solid var(--border);vertical-align:middle}
        .task-result-table tr:last-child td{border-bottom:none}

        /* ── Buttons ── */
        .btn{
            display:inline-flex;align-items:center;gap:.4rem;
            padding:.55rem 1.1rem;border-radius:var(--radius);
            border:1px solid var(--border);background:var(--surface);
            font-family:'Syne',sans-serif;font-size:.78rem;font-weight:600;
            color:var(--text);cursor:pointer;transition:all .15s;
        }
        .btn:hover{background:var(--surface2);border-color:var(--border2)}
        .btn-primary{background:var(--green-700);color:#fff;border-color:var(--green-700)}
        .btn-primary:hover{background:var(--green-800)}
        .btn-danger{background:var(--red-100);color:var(--red-600);border-color:var(--red-600)}
        .btn-danger:hover{background:#f8d7d7}
        .btn-accent{background:var(--green-500);color:#fff;border-color:var(--green-500)}
        .btn-accent:hover{background:var(--green-600)}
        .btn-group{display:flex;gap:.5rem;flex-wrap:wrap;margin-top:1.25rem}

        /* ── Toast ── */
        .toast{
            position:fixed;bottom:1.5rem;right:1.5rem;
            background:var(--green-900);color:#fff;
            padding:.75rem 1.25rem;border-radius:var(--radius);
            font-family:'Syne',sans-serif;font-size:.82rem;font-weight:500;
            display:flex;align-items:center;gap:.5rem;
            opacity:0;transform:translateY(8px);transition:all .25s;
            pointer-events:none;z-index:9999;max-width:340px;
        }
        .toast.show{opacity:1;transform:translateY(0)}

        @media(max-width:780px){
            .sidebar{width:100%;height:auto;position:static}
            .main{padding:1rem}
            .topbar{padding:.75rem 1rem}
            .metric-grid{grid-template-columns:1fr 1fr}
            .score-wrap{grid-template-columns:1fr}
            .form-grid{grid-template-columns:1fr}
        }
    </style>
</head>
<body>
<div class="wrap">

    <!-- SIDEBAR -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">
                <div class="brand-icon">🍎</div>
                <span class="brand-name">SISGEM</span>
            </div>
            <div class="brand-sub">Teste de Usabilidade</div>
        </div>

        <div class="nav-group">
            <div class="nav-label">Protocolo</div>
            <div class="nav-item active" onclick="goTo('info')" data-panel="info">
                <span class="nav-icon">◈</span> Identificação
            </div>
            <div class="nav-item" onclick="goTo('tarefas')" data-panel="tarefas">
                <span class="nav-icon">◎</span> Tarefas
                <span class="nav-badge" id="badge-tarefas">19</span>
            </div>
            <div class="nav-item" onclick="goTo('sus')" data-panel="sus">
                <span class="nav-icon">◉</span> Formulário SUS
            </div>
            <div class="nav-item" onclick="goTo('qual')" data-panel="qual">
                <span class="nav-icon">◐</span> Questões abertas
            </div>
        </div>

        <div class="nav-group">
            <div class="nav-label">Moderador</div>
            <div class="nav-item" onclick="goTo('moderador')" data-panel="moderador">
                <span class="nav-icon">⊕</span> Roteiro
            </div>
            <div class="nav-item" onclick="goTo('sessoes')" data-panel="sessoes">
                <span class="nav-icon">⊞</span> Sessões salvas
                <span class="nav-badge" id="badge-sessoes">0</span>
            </div>
            <div class="nav-item" onclick="goTo('analise')" data-panel="analise">
                <span class="nav-icon">◑</span> Análise geral
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="save-row">
                <div class="save-dot"></div>
                <span id="save-status">Autosave ativo</span>
            </div>
            <div style="font-size:.65rem;color:rgba(255,255,255,.2);margin-top:.5rem;font-family:'Syne',sans-serif">
                SUS · Brooke (1996) · v1.0
            </div>
        </div>
    </nav>

    <!-- MAIN -->
    <div style="flex:1;display:flex;flex-direction:column;min-width:0">

        <div class="topbar">
            <span class="topbar-title" id="topbar-title">Identificação do participante</span>
            <div class="topbar-actions">
                <button class="btn" onclick="clearDraft()">↺ Nova sessão</button>
                <button class="btn btn-primary" onclick="saveSession()">✦ Salvar sessão</button>
            </div>
        </div>

        <div class="main">

            <!-- ═══ PAINEL: Identificação ═══ -->
            <div id="panel-info" class="panel active">
                <div class="page-header">
                    <h1>Identificação do participante</h1>
                    <p>Preencha antes de iniciar. Os dados são salvos automaticamente no navegador.</p>
                </div>
                <div class="card">
                    <div class="card-title">Dados da sessão</div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Código do participante</label>
                            <input class="form-control" id="p-codigo" type="text" placeholder="P01, P02…">
                        </div>
                        <div class="form-group">
                            <label>Data</label>
                            <input class="form-control" id="p-data" type="date">
                        </div>
                        <div class="form-group">
                            <label>Hora de início</label>
                            <input class="form-control" id="p-hora" type="time">
                        </div>
                        <div class="form-group">
                            <label>Moderador</label>
                            <input class="form-control" id="p-moderador" type="text" placeholder="Nome do pesquisador">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-title">Perfil do participante</div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Função na escola</label>
                            <select class="form-control" id="p-perfil">
                                <option value="">Selecionar…</option>
                                <option>Diretor(a)</option>
                                <option>Secretário(a) escolar</option>
                                <option>Responsável pela merenda</option>
                                <option>Servidor administrativo</option>
                                <option>Professor(a)</option>
                                <option>Gestor de TI</option>
                                <option>Outro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Faixa etária</label>
                            <select class="form-control" id="p-idade">
                                <option value="">Selecionar…</option>
                                <option>18–29 anos</option>
                                <option>30–44 anos</option>
                                <option>45–59 anos</option>
                                <option>60+ anos</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Experiência com sistemas web</label>
                            <select class="form-control" id="p-experiencia">
                                <option value="">Selecionar…</option>
                                <option>Iniciante (uso básico)</option>
                                <option>Intermediário (usa regularmente)</option>
                                <option>Avançado (usa com frequência sistemas complexos)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Já usou o SISGEM antes?</label>
                            <select class="form-control" id="p-uso-prev">
                                <option value="">Selecionar…</option>
                                <option>Não, nunca</option>
                                <option>Somente demonstração</option>
                                <option>Sim, uso em produção</option>
                            </select>
                        </div>
                        <div class="form-group form-full">
                            <label>Observações iniciais do moderador</label>
                            <textarea class="form-control" id="p-obs" placeholder="Contexto relevante, estado do participante, condições técnicas do ambiente de teste…"></textarea>
                        </div>
                    </div>
                </div>
                <div class="btn-group">
                    <button class="btn btn-primary" onclick="goTo('tarefas');autoSave()">Iniciar teste → Tarefas</button>
                </div>
            </div>

            <!-- ═══ PAINEL: Tarefas ═══ -->
            <div id="panel-tarefas" class="panel">
                <div class="page-header">
                    <h1>Roteiro de tarefas</h1>
                    <p>Apresente uma tarefa por vez. Aplique o protocolo think-aloud — não ajude, apenas observe e registre.</p>
                </div>
                <div id="task-list"></div>
                <div class="card" style="margin-top:1rem">
                    <div class="card-title">Observações gerais do moderador</div>
                    <textarea class="form-control" id="obs-tarefas" rows="5"
                              placeholder="Padrões de comportamento observados, erros recorrentes, hesitações significativas, comentários espontâneos relevantes…"></textarea>
                </div>
                <div class="btn-group">
                    <button class="btn" onclick="goTo('info')">← Identificação</button>
                    <button class="btn btn-primary" onclick="goTo('sus');autoSave()">Formulário SUS →</button>
                </div>
            </div>

            <!-- ═══ PAINEL: SUS ═══ -->
            <div id="panel-sus" class="panel">
                <div class="page-header">
                    <h1>Formulário SUS</h1>
                    <p>System Usability Scale · Brooke (1996) · Aplicar imediatamente após as tarefas.</p>
                </div>
                <div class="sus-intro">
                    <strong>Instrução ao participante:</strong> "Para cada afirmação abaixo, marque um número de 1 a 5 — sendo <strong>1 = discordo totalmente</strong> e <strong>5 = concordo totalmente</strong>. Responda com base na sua experiência geral com o sistema agora. Não há respostas certas ou erradas."
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
                        <div class="score-bar-bg"><div class="score-bar" id="sus-bar" style="width:0%"></div></div>
                    </div>
                    <div class="card card-sm" style="margin:0">
                        <div class="card-title" style="margin-bottom:.75rem">Referência de classificação</div>
                        <table class="sus-ref"><thead><tr>
                                <th>Pontuação</th><th>Adjetivo</th><th>Aceitabilidade</th>
                            </tr></thead><tbody id="sus-ref-body"></tbody></table>
                    </div>
                </div>
                <div class="btn-group">
                    <button class="btn" onclick="goTo('tarefas')">← Tarefas</button>
                    <button class="btn btn-primary" onclick="goTo('qual');autoSave()">Questões abertas →</button>
                </div>
            </div>

            <!-- ═══ PAINEL: Questões abertas ═══ -->
            <div id="panel-qual" class="panel">
                <div class="page-header">
                    <h1>Questões abertas</h1>
                    <p>Debriefing — máximo 10 minutos. Registre as respostas literalmente quando possível.</p>
                </div>
                <div class="card">
                    <div class="card-title">Experiência geral</div>
                    <div class="qual-q"><label>1. O que você mais gostou no sistema?</label>
                        <textarea class="form-control" id="q1" rows="3" placeholder="Resposta do participante…"></textarea></div>
                    <div class="qual-q"><label>2. O que causou mais dificuldade ou frustração?</label>
                        <textarea class="form-control" id="q2" rows="3" placeholder="Resposta do participante…"></textarea></div>
                    <div class="qual-q"><label>3. Se pudesse mudar uma coisa no sistema, o que seria?</label>
                        <textarea class="form-control" id="q3" rows="3" placeholder="Resposta do participante…"></textarea></div>
                </div>
                <div class="card">
                    <div class="card-title">Módulos específicos</div>
                    <div class="qual-q"><label>4. O processo de login (LDAP) foi claro e rápido?</label>
                        <textarea class="form-control" id="q4" rows="2" placeholder="Resposta do participante…"></textarea></div>
                    <div class="qual-q"><label>5. A sincronização de cursos e discentes (importação) foi intuitiva?</label>
                        <textarea class="form-control" id="q5" rows="2" placeholder="Resposta do participante…"></textarea></div>
                    <div class="qual-q"><label>6. O fluxo de cadastro e gestão de contratos/empenhos foi compreensível?</label>
                        <textarea class="form-control" id="q6" rows="2" placeholder="Resposta do participante…"></textarea></div>
                    <div class="qual-q"><label>7. A simulação de retirada de merenda refletiu o processo real da escola?</label>
                        <textarea class="form-control" id="q7" rows="2" placeholder="Resposta do participante…"></textarea></div>
                    <div class="qual-q"><label>8. Os gráficos e dados de retirada foram fáceis de interpretar?</label>
                        <textarea class="form-control" id="q8" rows="2" placeholder="Resposta do participante…"></textarea></div>
                </div>
                <div class="card">
                    <div class="card-title">Intenção de uso e contexto institucional</div>
                    <div class="qual-q"><label>9. Você confiaria neste sistema para gerenciar a merenda escolar da sua instituição?</label>
                        <textarea class="form-control" id="q9" rows="3" placeholder="Resposta do participante…"></textarea></div>
                    <div class="qual-q"><label>10. Há algo que falta no sistema para atender às necessidades reais da escola?</label>
                        <textarea class="form-control" id="q10" rows="3" placeholder="Resposta do participante…"></textarea></div>
                    <div class="qual-q"><label>11. Comentários livres ou sugestões adicionais</label>
                        <textarea class="form-control" id="q11" rows="3" placeholder="Resposta do participante…"></textarea></div>
                </div>
                <div class="btn-group">
                    <button class="btn" onclick="goTo('sus')">← SUS</button>
                    <button class="btn btn-accent" onclick="saveSession()">✦ Salvar sessão completa</button>
                    <button class="btn btn-primary" onclick="exportJSON()">↓ Exportar JSON</button>
                </div>
            </div>

            <!-- ═══ PAINEL: Moderador ═══ -->
            <div id="panel-moderador" class="panel">
                <div class="page-header">
                    <h1>Roteiro do moderador</h1>
                    <p>Protocolo completo para condução. Siga a sequência e use o script como guia de fala.</p>
                </div>
                <div class="card">
                    <div class="card-title">Antes do teste — checklist</div>
                    <div id="pre-check"></div>
                </div>
                <div class="divider">Script de abertura — leia ao participante</div>
                <div class="card" style="padding:0;overflow:hidden">
                    <div style="padding:.75rem 1.5rem;background:var(--green-50);font-family:'Syne',sans-serif;font-size:.68rem;font-weight:700;color:var(--green-600);letter-spacing:.1em;text-transform:uppercase;border-bottom:1px solid var(--border)">Fala do moderador</div>
                    <div class="script-box" style="margin:0;border-left:none;border-radius:0;border-top:none">
                        "Obrigado por participar desta pesquisa sobre o <strong>SISGEM</strong>, sistema de gestão de merenda escolar. <strong>Não estamos testando você</strong> — estamos avaliando o sistema para identificar pontos de melhoria. Não existe resposta certa ou errada, e você pode interromper a qualquer momento.<br><br>
                        Durante o teste, peço que <strong>pense em voz alta</strong>: verbalize o que está vendo, o que pretende fazer e o que espera que aconteça. Se travar em algum ponto, continue falando — isso é o mais valioso para nós.<br><br>
                        A sessão pode ser gravada somente para fins de análise interna, conforme você autorizou no TCLE. Tem alguma dúvida antes de começarmos?"
                    </div>
                </div>
                <div class="divider">Durante o teste</div>
                <div class="card">
                    <div class="card-title">Instruções operacionais</div>
                    <div id="durante-check"></div>
                    <div class="script-box" style="margin-top:.875rem">
                        <strong>Se silêncio > 15s:</strong> "O que você está pensando agora?"<br>
                        <strong>Se travar > 60s:</strong> "O que você tentaria fazer a seguir?" (não revele o caminho)<br>
                        <strong>Se perguntar se está certo:</strong> "Não existe certo ou errado — o que você faria normalmente?"<br>
                        <strong>Se quiser desistir da tarefa:</strong> registre como Falha e avance para a próxima.
                    </div>
                </div>
                <div class="divider">Após o teste</div>
                <div class="card">
                    <div class="card-title">Encerramento</div>
                    <div id="pos-check"></div>
                    <div class="script-box" style="margin-top:.875rem">
                        "Muito obrigado pela participação! Seu feedback é fundamental para melhorar o SISGEM. Gostaria de compartilhar algo que observou e não chegou a mencionar durante o teste?"
                    </div>
                </div>
                <div class="divider">Classificação de erros de usabilidade (Nielsen, 1994)</div>
                <div class="card card-sm">
                    <div style="font-size:.82rem;line-height:2.1;color:var(--muted)">
                        <div><span style="color:var(--red-600);font-weight:700">● Nível 1 — Catastrófico:</span> impede completar a tarefa. Corrigir imediatamente antes do lançamento.</div>
                        <div><span style="color:#B45309;font-weight:700">● Nível 2 — Sério:</span> dificulta muito, mas é contornável. Alta prioridade de redesign.</div>
                        <div><span style="color:var(--green-700);font-weight:700">● Nível 3 — Menor:</span> atraso ou confusão leve. Corrigir se possível no próximo ciclo.</div>
                        <div><span style="color:var(--faint);font-weight:700">● Nível 4 — Cosmético:</span> não afeta uso. Endereçar se sobrar tempo de desenvolvimento.</div>
                    </div>
                </div>
            </div>

            <!-- ═══ PAINEL: Sessões ═══ -->
            <div id="panel-sessoes" class="panel">
                <div class="page-header">
                    <h1>Sessões salvas</h1>
                    <p>Todas as sessões armazenadas localmente neste navegador.</p>
                </div>
                <div class="card" style="padding:0;overflow:hidden">
                    <div id="sessions-list"></div>
                </div>
                <div class="btn-group">
                    <button class="btn btn-primary" onclick="exportAllJSON()">↓ Exportar todas (JSON)</button>
                    <button class="btn btn-primary" onclick="exportCSV()">↓ Exportar CSV resumido</button>
                    <button class="btn btn-danger" onclick="clearAll()">⊘ Apagar todas</button>
                </div>
            </div>

            <!-- ═══ PAINEL: Análise ═══ -->
            <div id="panel-analise" class="panel">
                <div class="page-header">
                    <h1>Análise geral dos testes</h1>
                    <p>Visualizações e relatório gerado a partir das sessões salvas.</p>
                </div>
                <div id="analise-content"></div>
            </div>

        </div><!-- /main -->
    </div>
</div>

<div class="toast" id="toast"></div>

<script>
    // ══════════════════════════════════════════════════════
    // DATA
    // ══════════════════════════════════════════════════════
    const TASKS = [
        { id:'T1',  mod:'publico',   cls:'mod-publico',
            scenario:'Área pública — sem login',
            label:'Identifique o cardápio escolar atual disponível no sistema.',
            criterion:'Cardápio corrente visualizado sem necessidade de autenticação.',
            tip:'Observe se o acesso público é percebido como separado do sistema restrito. O participante tenta fazer login antes de procurar o cardápio?' },
        { id:'T2',  mod:'público',   cls:'mod-publico',
            scenario:'Área pública — sem login',
            label:'Localize o cardápio da próxima semana.',
            criterion:'Cardápio futuro acessado sem login, com datas corretas.',
            tip:'Note se a navegação temporal (semana passada / próxima) é descoberta espontaneamente.' },
        { id:'T3',  mod:'auth',      cls:'mod-auth',
            scenario:'Autenticação',
            label:'Autentique-se no sistema usando suas credenciais LDAP.',
            criterion:'Login concluído; painel principal visível e com dados do usuário corretos.',
            tip:'Registre o tempo até o clique em "Entrar". O fluxo LDAP causa hesitação? Há feedback claro de erro?' },
        { id:'T4',  mod:'gestão',    cls:'mod-gestao',
            scenario:'Gestão escolar',
            label:'Sincronize os cursos da instituição a partir do sistema externo (CTA).',
            criterion:'Importação concluída; lista de cursos atualizada visível.',
            tip:'O botão/CTA de sincronização é encontrado sem ajuda? O feedback de carregamento é claro?' },
        { id:'T5',  mod:'gestão',    cls:'mod-gestao',
            scenario:'Gestão escolar',
            label:'Habilite o acesso à merenda escolar para uma turma ou grupo de estudantes.',
            criterion:'Acesso habilitado; status atualizado na interface.',
            tip:'O fluxo de permissão é intuitivo? O participante sabe onde confirmar a ação?' },
        { id:'T6',  mod:'gestão',    cls:'mod-gestao',
            scenario:'Gestão escolar',
            label:'Sincronize os discentes da instituição a partir do sistema externo (CTA).',
            criterion:'Importação concluída; base de estudantes atualizada.',
            tip:'Diferencia o fluxo do T4? Observe se reutiliza o mesmo caminho mental.' },
        { id:'T7',  mod:'contrato',  cls:'mod-contrato',
            scenario:'Contratos e estoque',
            label:'Identifique os contratos ativos cadastrados no sistema.',
            criterion:'Lista de contratos acessada e visualizada corretamente.',
            tip:'A seção de contratos é encontrada pelo menu ou por busca? Há ambiguidade de nomenclatura?' },
        { id:'T8',  mod:'contrato',  cls:'mod-contrato',
            scenario:'Contratos e estoque',
            label:'Cadastre um novo contrato incluindo ao menos um alimento.',
            criterion:'Contrato criado com alimento vinculado; confirmação exibida.',
            tip:'O formulário de cadastro é descoberto pelo fluxo do T7? O campo de alimento é associado ao contrato sem instrução?' },
        { id:'T9',  mod:'contrato',  cls:'mod-contrato',
            scenario:'Contratos e estoque',
            label:'Edite os dados do contrato recém-cadastrado.',
            criterion:'Alteração salva e refletida na listagem.',
            tip:'O botão de edição é encontrado na listagem ou na tela de detalhe? Duplo clique ou ícone?' },
        { id:'T10', mod:'contrato',  cls:'mod-contrato',
            scenario:'Contratos e estoque',
            label:'Visualize os dados completos de um contrato existente.',
            criterion:'Tela de detalhes do contrato acessada com todas as informações visíveis.',
            tip:'O participante diferencia "visualizar" de "editar"? Há risco de edição acidental?' },
        { id:'T11', mod:'contrato',  cls:'mod-contrato',
            scenario:'Contratos e estoque',
            label:'Cadastre um empenho vinculado ao contrato e visualize seus dados.',
            criterion:'Empenho criado e acessível na tela de detalhes do contrato.',
            tip:'O conceito de empenho é compreendido? O vínculo com o contrato é evidente na interface?' },
        { id:'T12', mod:'merenda',   cls:'mod-merenda',
            scenario:'Pedidos e merenda',
            label:'Faça um pedido de merenda para a semana seguinte.',
            criterion:'Pedido criado com itens e quantidade; confirmação exibida.',
            tip:'O fluxo de pedido é encontrado a partir do cardápio ou de um menu dedicado? Observe ponto de entrada.' },
        { id:'T13', mod:'merenda',   cls:'mod-merenda',
            scenario:'Pedidos e merenda',
            label:'Atualize o status de um pedido para "Recebido".',
            criterion:'Status alterado corretamente; histórico atualizado.',
            tip:'A mudança de status é feita por botão, seletor ou outra interação? É reversível?' },
        { id:'T14', mod:'merenda',   cls:'mod-merenda',
            scenario:'Pedidos e merenda',
            label:'Visualize a lista de pedidos realizados.',
            criterion:'Lista de pedidos acessada com filtros/datas visíveis.',
            tip:'O participante usa filtro de data ou de status espontaneamente?' },
        { id:'T15', mod:'contrato',  cls:'mod-contrato',
            scenario:'Contratos e estoque',
            label:'Identifique outros contratos vinculados à mesma empresa fornecedora.',
            criterion:'Contratos da empresa listados ou filtrados corretamente.',
            tip:'O vínculo por empresa é percebido como funcionalidade? O participante usa busca ou navega pela listagem?' },
        { id:'T16', mod:'merenda',   cls:'mod-merenda',
            scenario:'Cardápio e merenda',
            label:'Cadastre um novo cardápio para uma data futura.',
            criterion:'Cardápio criado, com itens e data vinculados.',
            tip:'O fluxo de criação difere da visualização (T1/T2)? O participante diferencia os contextos.' },
        { id:'T17', mod:'merenda',   cls:'mod-merenda',
            scenario:'Retirada biométrica',
            label:'Simule ou execute a retirada de merenda por um estudante.',
            criterion:'Retirada registrada com identificação do estudante e confirmação.',
            tip:'O fluxo de retirada é compreendido como individual e rastreável?' },
        { id:'T18', mod:'dados',     cls:'mod-dados',
            scenario:'Dados e relatórios',
            label:'Analise os dados de retirada de merenda em formato de gráfico.',
            criterion:'Gráfico de retiradas acessado e interpretado pelo participante.',
            tip:'O participante encontra o módulo de análise sem instrução? Consegue interpretar os eixos corretamente?' },
        { id:'T19', mod:'dados',     cls:'mod-dados',
            scenario:'Dados e relatórios',
            label:'Visualize o gráfico de acessos mais frequentes, sobras e distribuição por turno.',
            criterion:'Gráfico com as três dimensões (frequência, sobras, turno) acessado.',
            tip:'A segmentação por turno é intuitiva? O participante percebe "sobras" como dado de desperdício ou de estoque?' },
    ];

    const SUS_ITEMS = [
        { q:'Eu usaria este sistema com frequência.', type:'pos' },
        { q:'Achei o sistema desnecessariamente complexo.', type:'neg' },
        { q:'Achei o sistema fácil de usar.', type:'pos' },
        { q:'Precisaria de apoio técnico para conseguir usar este sistema.', type:'neg' },
        { q:'As diversas funções do sistema estão bem integradas.', type:'pos' },
        { q:'Achei que havia muita inconsistência no sistema.', type:'neg' },
        { q:'Imagino que a maioria das pessoas aprenderia a usar este sistema rapidamente.', type:'pos' },
        { q:'Achei o sistema muito difícil de usar.', type:'neg' },
        { q:'Me senti confiante usando o sistema.', type:'pos' },
        { q:'Precisei aprender muitas coisas antes de conseguir usar este sistema.', type:'neg' },
    ];

    const SUS_REF = [
        { range:'≥ 90', label:'Excelente',   accept:'Recomendado com entusiasmo', min:90 },
        { range:'80–89',label:'Bom',          accept:'Aceitável',                  min:80 },
        { range:'70–79',label:'OK',           accept:'Com ressalvas',              min:70 },
        { range:'60–69',label:'Pobre',        accept:'Abaixo do esperado',         min:60 },
        { range:'< 60', label:'Inaceitável',  accept:'Reprojetar antes do lançamento', min:0 },
    ];

    const PRE = [
        'TCLE assinado ou confirmado digitalmente pelo participante',
        'Ambiente silencioso preparado; para remoto: câmera + microfone testados',
        'Gravação de tela ativada (com consentimento)',
        'Protótipo SISGEM acessível na URL correta; conta de teste criada e verificada',
        'Conta LDAP de teste pronta para o T3',
        'Tarefas impressas ou em tela separada — participante não vê tarefa seguinte',
        'Cronômetro pronto para registro de tempo por tarefa',
    ];
    const DURANTE = [
        'NÃO ajudar o participante — observar, anotar e encorajar think-aloud',
        'Encerrar tarefa após 3 minutos sem progresso; registrar como Falha',
        'Registrar tempo de conclusão de cada tarefa',
        'Anotar erros, hesitações, comentários espontâneos e pontos de abandono',
        'Classificar erros por nível (1–4) durante ou logo após cada tarefa',
        'Marcar resultado: Concluída / Parcial / Falha / N/A',
    ];
    const POS = [
        'Aplicar formulário SUS imediatamente (memória fresca)',
        'Conduzir debriefing com questões abertas (máx. 10 min)',
        'Salvar sessão antes de fechar o navegador',
        'Agradecer e orientar sobre próximos passos',
        'Registrar impressões pessoais do moderador logo após o término',
    ];

    const PAGE_TITLES = {
        info:'Identificação do participante', tarefas:'Roteiro de tarefas',
        sus:'Formulário SUS', qual:'Questões abertas',
        moderador:'Roteiro do moderador', sessoes:'Sessões salvas',
        analise:'Análise geral dos testes'
    };

    // ══════════════════════════════════════════════════════
    // STATE
    // ══════════════════════════════════════════════════════
    let susAns = new Array(10).fill(0);
    let taskRes = {}; // tid -> { result, time, errorLevel }
    let charts = {};

    // ══════════════════════════════════════════════════════
    // INIT
    // ══════════════════════════════════════════════════════
    window.addEventListener('DOMContentLoaded', () => {
        renderTasks();
        renderSUS();
        renderSUSRef();
        renderChecklist('pre-check', PRE);
        renderChecklist('durante-check', DURANTE);
        renderChecklist('pos-check', POS);
        const now = new Date();
        document.getElementById('p-data').value = now.toISOString().split('T')[0];
        document.getElementById('p-hora').value = now.toTimeString().slice(0,5);
        loadDraft();
        updateBadges();
        document.querySelectorAll('input,select,textarea').forEach(el => {
            el.addEventListener('input', autoSave);
            el.addEventListener('change', autoSave);
        });
    });

    // ══════════════════════════════════════════════════════
    // NAVIGATION
    // ══════════════════════════════════════════════════════
    function goTo(name) {
        document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
        document.getElementById('panel-' + name)?.classList.add('active');
        document.querySelector(`[data-panel="${name}"]`)?.classList.add('active');
        document.getElementById('topbar-title').textContent = PAGE_TITLES[name] || '';
        if (name === 'sessoes') renderSessions();
        if (name === 'analise') renderAnalysis();
    }

    // ══════════════════════════════════════════════════════
    // TASKS
    // ══════════════════════════════════════════════════════
    function renderTasks() {
        document.getElementById('task-list').innerHTML = TASKS.map(t => `
    <div class="task-item">
      <div class="task-head">
        <div class="task-num">${t.id}</div>
        <div class="task-body">
          <div class="task-scenario">${t.scenario}</div>
          <div class="task-label">"${t.label}"</div>
          <div class="task-criterion">✓ Critério: ${t.criterion}</div>
          <div class="task-tip">💡 ${t.tip}</div>
        </div>
        <span class="task-module ${t.cls}">${t.mod}</span>
      </div>
      <div class="task-footer">
        <span class="result-label">Resultado:</span>
        <div class="result-options">
          ${['Concluída','Parcial','Falha','N/A'].map(r =>
            `<button class="r-btn" id="rb-${t.id}-${r.replace('/','')}" onclick="setResult('${t.id}','${r}')">${r}</button>`
        ).join('')}
        </div>
        <div class="error-level">
          <label>Nível de erro:</label>
          <select class="error-select" id="err-${t.id}" onchange="setError('${t.id}',this.value)">
            <option value="">—</option>
            <option value="1">1 – Catastrófico</option>
            <option value="2">2 – Sério</option>
            <option value="3">3 – Menor</option>
            <option value="4">4 – Cosmético</option>
            <option value="0">Sem erro</option>
          </select>
        </div>
        <div class="time-field">
          <label>Tempo (s):</label>
          <input class="time-input" id="time-${t.id}" type="number" min="0" placeholder="—"
            oninput="setTime('${t.id}',this.value)">
        </div>
      </div>
    </div>
  `).join('');
    }

    function setResult(tid, result) {
        if (!taskRes[tid]) taskRes[tid] = {};
        taskRes[tid].result = result;
        ['Concluída','Parcial','Falha','N/A'].forEach(r => {
            const btn = document.getElementById(`rb-${tid}-${r.replace('/','')}`);
            if (!btn) return;
            btn.classList.remove('ok','parcial','falha');
            if (r === result) {
                if (r === 'Concluída') btn.classList.add('ok');
                else if (r === 'Parcial') btn.classList.add('parcial');
                else if (r === 'Falha') btn.classList.add('falha');
            }
        });
        autoSave();
    }
    function setTime(tid, v) {
        if (!taskRes[tid]) taskRes[tid] = {};
        taskRes[tid].time = v;
        autoSave();
    }
    function setError(tid, v) {
        if (!taskRes[tid]) taskRes[tid] = {};
        taskRes[tid].errorLevel = v;
        autoSave();
    }

    // ══════════════════════════════════════════════════════
    // SUS
    // ══════════════════════════════════════════════════════
    function renderSUS() {
        document.getElementById('sus-questions').innerHTML = SUS_ITEMS.map((item, i) => `
    <div class="sus-question">
      <div class="sus-q-num">Q${i+1}</div>
      <div class="sus-q-text">${item.q}</div>
      <span class="sus-q-type ${item.type==='pos'?'type-pos':'type-neg'}">
        ${item.type==='pos'?'+ positiva':'− negativa'}
      </span>
      <div class="sus-scale">
        ${[1,2,3,4,5].map(v =>
            `<button class="sus-btn" id="sus-${i}-${v}" onclick="setSUS(${i},${v})">${v}</button>`
        ).join('')}
      </div>
    </div>
  `).join('');
    }

    function setSUS(q, val) {
        susAns[q] = val;
        for (let v=1;v<=5;v++) document.getElementById(`sus-${q}-${v}`)?.classList.toggle('sel', v===val);
        calcSUS();
        autoSave();
    }

    function calcSUS() {
        if (susAns.some(v => v===0)) {
            document.getElementById('sus-score-val').textContent = '—';
            document.getElementById('sus-grade').textContent = 'aguardando respostas';
            document.getElementById('sus-grade').style.color = 'rgba(255,255,255,.4)';
            document.getElementById('sus-bar').style.width = '0%';
            return;
        }
        let sum = 0;
        for (let i=0;i<10;i++) sum += i%2===0 ? susAns[i]-1 : 5-susAns[i];
        const score = Math.round(sum*2.5);
        document.getElementById('sus-score-val').textContent = score;
        document.getElementById('sus-bar').style.width = score+'%';
        const { label, color } = scoreInfo(score);
        const grade = document.getElementById('sus-grade');
        grade.textContent = label;
        grade.style.color = color;
        highlightRef(score);
        return score;
    }

    function getSUSScore() {
        if (susAns.some(v=>v===0)) return null;
        let sum=0;
        for(let i=0;i<10;i++) sum += i%2===0 ? susAns[i]-1 : 5-susAns[i];
        return Math.round(sum*2.5);
    }

    function scoreInfo(score) {
        if (score>=90) return { label:'Excelente', color:'#3DAE77' };
        if (score>=80) return { label:'Bom',       color:'#6FBF8A' };
        if (score>=70) return { label:'Aceitável', color:'#E5A000' };
        if (score>=60) return { label:'Pobre',     color:'#D97706' };
        return             { label:'Inaceitável', color:'#EF4444' };
    }

    function renderSUSRef() {
        document.getElementById('sus-ref-body').innerHTML = SUS_REF.map(r =>
            `<tr id="ref-${r.min}"><td>${r.range}</td><td>${r.label}</td><td>${r.accept}</td></tr>`
        ).join('');
    }

    function highlightRef(score) {
        document.querySelectorAll('#sus-ref-body tr').forEach(tr => tr.classList.remove('sus-hl'));
        const match = SUS_REF.find(r => score >= r.min);
        if (match) document.getElementById(`ref-${match.min}`)?.classList.add('sus-hl');
    }

    // ══════════════════════════════════════════════════════
    // CHECKLISTS
    // ══════════════════════════════════════════════════════
    function renderChecklist(id, items) {
        document.getElementById(id).innerHTML = items.map((item,i) => `
    <label class="checklist-item" id="cl-${id}-${i}">
      <input type="checkbox" onchange="this.closest('.checklist-item').classList.toggle('done',this.checked)">
      <span>${item}</span>
    </label>
  `).join('');
    }

    // ══════════════════════════════════════════════════════
    // STORAGE
    // ══════════════════════════════════════════════════════
    function getFormData() {
        const g = id => document.getElementById(id)?.value || '';
        return {
            ts: Date.now(),
            participante: {
                codigo:g('p-codigo'), data:g('p-data'), hora:g('p-hora'),
                moderador:g('p-moderador'), perfil:g('p-perfil'), idade:g('p-idade'),
                experiencia:g('p-experiencia'), uso_prev:g('p-uso-prev'), obs:g('p-obs')
            },
            tarefas: { resultados: JSON.parse(JSON.stringify(taskRes)), obs:g('obs-tarefas') },
            sus: { respostas:[...susAns], score:getSUSScore() },
            qualitativo: Object.fromEntries(
                Array.from({length:11},(_,i)=>[`q${i+1}`,g(`q${i+1}`)])
            )
        };
    }

    function autoSave() {
        localStorage.setItem('SISGEM_draft', JSON.stringify(getFormData()));
        document.getElementById('save-status').textContent = 'Salvo ' + new Date().toLocaleTimeString('pt-BR');
    }

    function loadDraft() {
        try {
            const raw = localStorage.getItem('SISGEM_draft');
            if (!raw) return;
            const d = JSON.parse(raw);
            const set = (id,val) => { const el=document.getElementById(id); if(el&&val!==undefined&&val!=='') el.value=val; };
            const p = d.participante||{};
            ['codigo','data','hora','moderador','perfil','idade','experiencia'].forEach(k => set(`p-${k}`, p[k]));
            set('p-uso-prev', p.uso_prev); set('p-obs', p.obs);
            set('obs-tarefas', d.tarefas?.obs);
            if (d.tarefas?.resultados) {
                taskRes = d.tarefas.resultados;
                Object.entries(taskRes).forEach(([tid,r]) => {
                    if (r.result) setResult(tid, r.result);
                    const tel = document.getElementById(`time-${tid}`);
                    if (tel&&r.time) tel.value = r.time;
                    const eel = document.getElementById(`err-${tid}`);
                    if (eel&&r.errorLevel) eel.value = r.errorLevel;
                });
            }
            if (d.sus?.respostas) d.sus.respostas.forEach((v,i)=>{ if(v) setSUS(i,v); });
            for(let i=1;i<=11;i++) set(`q${i}`, d.qualitativo?.[`q${i}`]);
        } catch(e) { console.error(e); }
    }

    // ══════════════════════════════════════════════════════
    // SESSIONS
    // ══════════════════════════════════════════════════════
    function getSessions() {
        try { return JSON.parse(localStorage.getItem('SISGEM_sessions')||'[]'); } catch { return []; }
    }
    function updateBadges() {
        document.getElementById('badge-sessoes').textContent = getSessions().length;
    }

    function saveSession() {
        const data = getFormData();
        const codigo = data.participante.codigo || ('P'+String(getSessions().length+1).padStart(2,'0'));
        data.participante.codigo = codigo;
        const sessions = getSessions();
        const idx = sessions.findIndex(s => s.participante.codigo === codigo);
        if (idx>=0) sessions[idx]=data; else sessions.push(data);
        localStorage.setItem('SISGEM_sessions', JSON.stringify(sessions));
        updateBadges();
        toast('✦ Sessão ' + codigo + ' salva');
    }

    function renderSessions() {
        const sessions = getSessions();
        const list = document.getElementById('sessions-list');
        if (!sessions.length) {
            list.innerHTML = '<div class="empty-state">Nenhuma sessão salva ainda.<br>Complete um teste e clique em "Salvar sessão".</div>';
            return;
        }
        list.innerHTML = sessions.map((s,i) => {
            const p = s.participante;
            const score = s.sus?.score;
            const { label, color } = score!=null ? scoreInfo(score) : { label:'—', color:'#8AA898' };
            const concl = Object.values(s.tarefas?.resultados||{}).filter(r=>r.result==='Concluída').length;
            const initials = (p.codigo||'P?').slice(0,2).toUpperCase();
            return `
      <div class="session-row">
        <div class="s-avatar">${initials}</div>
        <div>
          <div class="s-name">${p.codigo||'—'} · ${p.perfil||'perfil não informado'}</div>
          <div class="s-meta">${p.data||'—'} · ${p.hora||''} · ${concl}/19 concluídas</div>
        </div>
        <span class="s-score" style="background:${color}18;color:${color}">SUS ${score??'—'}</span>
        <div class="s-actions">
          <button class="btn" style="padding:3px 10px;font-size:.72rem" onclick="loadSession(${i})">Carregar</button>
          <button class="btn btn-danger" style="padding:3px 10px;font-size:.72rem" onclick="deleteSession(${i})">✕</button>
        </div>
      </div>`;
        }).join('');
    }

    function loadSession(i) {
        const s = getSessions()[i];
        localStorage.setItem('SISGEM_draft', JSON.stringify(s));
        loadDraft();
        goTo('info');
        toast('Sessão carregada: ' + (s.participante?.codigo||''));
    }
    function deleteSession(i) {
        const sessions = getSessions();
        const cod = sessions[i]?.participante?.codigo||i;
        sessions.splice(i,1);
        localStorage.setItem('SISGEM_sessions', JSON.stringify(sessions));
        updateBadges();
        renderSessions();
        toast('Sessão ' + cod + ' removida');
    }
    function clearAll() {
        if (!confirm('Apagar TODAS as sessões? Esta ação não pode ser desfeita.')) return;
        localStorage.removeItem('SISGEM_sessions');
        updateBadges(); renderSessions();
        toast('Todas as sessões apagadas');
    }
    function clearDraft() {
        if (!confirm('Limpar a sessão atual?')) return;
        localStorage.removeItem('SISGEM_draft');
        location.reload();
    }

    // ══════════════════════════════════════════════════════
    // EXPORT
    // ══════════════════════════════════════════════════════
    function download(content, filename, mime) {
        const a = document.createElement('a');
        a.href = URL.createObjectURL(new Blob([content],{type:mime}));
        a.download = filename; a.click();
    }
    function exportJSON() {
        download(JSON.stringify(getFormData(),null,2),
            'SISGEM_'+(document.getElementById('p-codigo')?.value||'draft')+'.json','application/json');
        toast('↓ JSON exportado');
    }
    function exportAllJSON() {
        download(JSON.stringify(getSessions(),null,2),'SISGEM_todas_sessoes.json','application/json');
        toast('↓ Todas as sessões exportadas');
    }
    function exportCSV() {
        const sessions = getSessions();
        if (!sessions.length) { toast('Nenhuma sessão para exportar'); return; }
        const taskIds = TASKS.map(t=>t.id);
        const header = ['codigo','data','perfil','sus_score','sus_grade',
            ...taskIds.map(t=>t+'_resultado'),...taskIds.map(t=>t+'_tempo'),...taskIds.map(t=>t+'_erro')];
        const rows = sessions.map(s => {
            const p=s.participante, sc=s.sus?.score;
            const grade = sc==null?'':sc>=90?'Excelente':sc>=80?'Bom':sc>=70?'OK':sc>=60?'Pobre':'Inaceitável';
            return [p.codigo,p.data,p.perfil,sc??'',grade,
                ...taskIds.map(t=>s.tarefas?.resultados?.[t]?.result||''),
                ...taskIds.map(t=>s.tarefas?.resultados?.[t]?.time||''),
                ...taskIds.map(t=>s.tarefas?.resultados?.[t]?.errorLevel||''),
            ].map(c=>'"'+String(c).replace(/"/g,'""')+'"').join(',');
        });
        download([header.join(','),...rows].join('\n'),'SISGEM_resultados.csv','text/csv');
        toast('↓ CSV exportado');
    }

    // ══════════════════════════════════════════════════════
    // ANALYSIS
    // ══════════════════════════════════════════════════════
    function renderAnalysis() {
        const sessions = getSessions();
        const el = document.getElementById('analise-content');

        if (!sessions.length) {
            el.innerHTML = '<div class="card"><p style="color:var(--faint);font-style:italic;text-align:center;padding:2rem">Salve ao menos uma sessão para gerar a análise.</p></div>';
            return;
        }

        // Métricas gerais
        const n = sessions.length;
        const scores = sessions.map(s=>s.sus?.score).filter(s=>s!=null);
        const avgSUS = scores.length ? Math.round(scores.reduce((a,b)=>a+b,0)/scores.length) : null;

        // Taxa de conclusão por tarefa
        const taskCompletion = TASKS.map(t => {
            const results = sessions.map(s => s.tarefas?.resultados?.[t.id]?.result).filter(Boolean);
            const ok = results.filter(r=>r==='Concluída').length;
            const total = results.filter(r=>r!=='N/A').length;
            return { id:t.id, label:t.label.slice(0,40)+'…', ok, total, pct: total>0?Math.round((ok/total)*100):null };
        });

        // Tempo médio por tarefa
        const taskTimes = TASKS.map(t => {
            const times = sessions.map(s=>parseFloat(s.tarefas?.resultados?.[t.id]?.time)).filter(v=>!isNaN(v)&&v>0);
            return { id:t.id, avg: times.length ? Math.round(times.reduce((a,b)=>a+b,0)/times.length) : null };
        });

        // Distribuição de erros
        const errorDist = {1:0,2:0,3:0,4:0,0:0};
        sessions.forEach(s => {
            Object.values(s.tarefas?.resultados||{}).forEach(r => {
                if (r.errorLevel) errorDist[r.errorLevel] = (errorDist[r.errorLevel]||0)+1;
            });
        });

        // SUS por sessão
        const susPerSession = sessions.map(s => ({
            cod: s.participante?.codigo||'?',
            score: s.sus?.score
        })).filter(s=>s.score!=null);

        el.innerHTML = `
    <div class="analysis-section">
      <div class="metric-grid">
        <div class="metric-card">
          <div class="metric-val">${n}</div>
          <div class="metric-lbl">Sessões realizadas</div>
        </div>
        <div class="metric-card">
          <div class="metric-val" style="color:${avgSUS!=null?scoreInfo(avgSUS).color:'var(--faint)'}">${avgSUS??'—'}</div>
          <div class="metric-lbl">SUS médio</div>
        </div>
        <div class="metric-card">
          <div class="metric-val">${taskCompletion.filter(t=>t.pct!=null&&t.pct<70).length}</div>
          <div class="metric-lbl">Tarefas críticas (&lt;70%)</div>
        </div>
        <div class="metric-card">
          <div class="metric-val">${errorDist[1]||0}</div>
          <div class="metric-lbl">Erros catastróficos</div>
        </div>
      </div>
    </div>

    <div class="analysis-section">
      <h3>Pontuação SUS por participante</h3>
      <div class="chart-wrap">
        <div class="chart-title">SUS Score individual</div>
        <canvas id="chart-sus" height="80"></canvas>
      </div>
    </div>

    <div class="analysis-section">
      <h3>Taxa de conclusão por tarefa (%)</h3>
      <div class="chart-wrap">
        <div class="chart-title">% tarefas concluídas com sucesso</div>
        <canvas id="chart-completion" height="140"></canvas>
      </div>
    </div>

    <div class="analysis-section">
      <h3>Tempo médio por tarefa (segundos)</h3>
      <div class="chart-wrap">
        <div class="chart-title">Tempo médio de conclusão</div>
        <canvas id="chart-time" height="140"></canvas>
      </div>
    </div>

    <div class="analysis-section">
      <h3>Distribuição de erros por nível</h3>
      <div class="chart-wrap" style="max-width:340px">
        <div class="chart-title">Incidência por nível de severidade</div>
        <canvas id="chart-errors" height="160"></canvas>
      </div>
    </div>

    <div class="analysis-section">
      <h3>Detalhamento por tarefa</h3>
      <div class="card" style="padding:0;overflow:hidden">
        <table class="task-result-table">
          <thead><tr>
            <th>Tarefa</th><th>Módulo</th><th>Conclusão</th><th>Tempo médio (s)</th><th>Erros</th>
          </tr></thead>
          <tbody>
            ${taskCompletion.map((t,i)=>{
            const pct = t.pct;
            const color = pct==null?'var(--faint)':pct>=80?'var(--green-600)':pct>=60?'var(--amber-600)':'var(--red-600)';
            const taskMod = TASKS[i].mod;
            const errCount = sessions.reduce((acc,s)=>{
                const er = s.tarefas?.resultados?.[t.id]?.errorLevel;
                return acc + (er&&er!=='0'?1:0);
            },0);
            return `<tr>
                <td><strong>${t.id}</strong></td>
                <td><span class="task-module ${TASKS[i].cls}" style="font-size:.62rem">${taskMod}</span></td>
                <td style="color:${color};font-weight:700">${pct!=null?pct+'%':'—'}</td>
                <td>${taskTimes[i].avg!=null?taskTimes[i].avg+'s':'—'}</td>
                <td>${errCount>0?errCount:'—'}</td>
              </tr>`;
        }).join('')}
          </tbody>
        </table>
      </div>
    </div>

    <div class="analysis-section">
      <h3>Relatório automático com IA</h3>
      <div style="margin-bottom:.75rem">
        <button class="btn btn-accent" onclick="generateAIReport()">✦ Gerar relatório com IA →</button>
        <button class="btn" onclick="exportReportTXT()" id="btn-export-report" style="display:none">↓ Exportar relatório (.txt)</button>
      </div>
      <div id="ai-report" class="ai-report" style="display:none"></div>
    </div>
  `;

        // Destruir charts antigos
        Object.values(charts).forEach(c => c.destroy?.());
        charts = {};

        const GREEN = 'rgba(42,144,96,0.85)';
        const GREEN_LIGHT = 'rgba(42,144,96,0.2)';
        const AMBER = 'rgba(217,119,6,0.85)';
        const RED = 'rgba(155,32,32,0.85)';

        // Chart SUS individual
        if (susPerSession.length) {
            charts.sus = new Chart(document.getElementById('chart-sus'), {
                type:'bar',
                data:{
                    labels: susPerSession.map(s=>s.cod),
                    datasets:[{
                        label:'SUS Score',
                        data: susPerSession.map(s=>s.score),
                        backgroundColor: susPerSession.map(s=>s.score>=80?GREEN:s.score>=70?AMBER:RED),
                        borderRadius:6, borderSkipped:false
                    }]
                },
                options:{ plugins:{legend:{display:false}}, scales:{y:{min:0,max:100,grid:{color:'rgba(0,0,0,.05)'}}}, responsive:true }
            });
        }

        // Chart conclusão
        charts.completion = new Chart(document.getElementById('chart-completion'), {
            type:'bar',
            data:{
                labels: taskCompletion.map(t=>t.id),
                datasets:[{
                    label:'% conclusão',
                    data: taskCompletion.map(t=>t.pct),
                    backgroundColor: taskCompletion.map(t=>
                        t.pct==null?'rgba(0,0,0,.1)':t.pct>=80?GREEN:t.pct>=60?AMBER:RED),
                    borderRadius:4, borderSkipped:false
                }]
            },
            options:{
                plugins:{legend:{display:false}},
                scales:{y:{min:0,max:100,grid:{color:'rgba(0,0,0,.05)'},ticks:{callback:v=>v+'%'}}},
                responsive:true
            }
        });

        // Chart tempo
        const hasTime = taskTimes.some(t=>t.avg!=null);
        if (hasTime) {
            charts.time = new Chart(document.getElementById('chart-time'), {
                type:'bar',
                data:{
                    labels: taskTimes.map(t=>t.id),
                    datasets:[{
                        label:'Tempo médio (s)',
                        data: taskTimes.map(t=>t.avg),
                        backgroundColor: GREEN_LIGHT,
                        borderColor: GREEN,
                        borderWidth:2,
                        borderRadius:4, borderSkipped:false
                    }]
                },
                options:{
                    plugins:{legend:{display:false}},
                    scales:{y:{grid:{color:'rgba(0,0,0,.05)'}}},
                    responsive:true
                }
            });
        }

        // Chart erros
        charts.errors = new Chart(document.getElementById('chart-errors'), {
            type:'doughnut',
            data:{
                labels:['Catastrófico (1)','Sério (2)','Menor (3)','Cosmético (4)','Sem erro'],
                datasets:[{
                    data:[errorDist[1]||0,errorDist[2]||0,errorDist[3]||0,errorDist[4]||0,errorDist[0]||0],
                    backgroundColor:[RED,AMBER,'rgba(42,144,96,.6)',GREEN_LIGHT,'rgba(0,0,0,.08)'],
                    borderWidth:0
                }]
            },
            options:{plugins:{legend:{position:'right'}},responsive:true}
        });
    }

    // ══════════════════════════════════════════════════════
    // AI REPORT
    // ══════════════════════════════════════════════════════
    async function generateAIReport() {
        const sessions = getSessions();
        if (!sessions.length) { toast('Salve ao menos uma sessão primeiro'); return; }

        const reportEl = document.getElementById('ai-report');
        const exportBtn = document.getElementById('btn-export-report');
        reportEl.style.display = 'block';
        reportEl.className = 'ai-report loading';
        reportEl.textContent = 'Analisando dados das sessões…';

        const n = sessions.length;
        const scores = sessions.map(s=>s.sus?.score).filter(s=>s!=null);
        const avgSUS = scores.length ? Math.round(scores.reduce((a,b)=>a+b,0)/scores.length) : null;

        const taskSummary = TASKS.map(t => {
            const results = sessions.map(s=>s.tarefas?.resultados?.[t.id]?.result).filter(Boolean);
            const ok = results.filter(r=>r==='Concluída').length;
            const total = results.filter(r=>r!=='N/A').length;
            const pct = total>0?Math.round((ok/total)*100):null;
            const times = sessions.map(s=>parseFloat(s.tarefas?.resultados?.[t.id]?.time)).filter(v=>!isNaN(v)&&v>0);
            const avgTime = times.length?Math.round(times.reduce((a,b)=>a+b,0)/times.length):null;
            const errs = sessions.map(s=>s.tarefas?.resultados?.[t.id]?.errorLevel).filter(e=>e&&e!=='0');
            return `${t.id} (${t.mod}): ${pct!=null?pct+'% conclusão':'sem dados'}, tempo médio ${avgTime!=null?avgTime+'s':'—'}, erros: [${errs.join(',')||'nenhum'}]`;
        }).join('\n');

        const qualSummary = sessions.slice(0,5).map((s,i) => {
            const q = s.qualitativo||{};
            const resp = [q.q1,q.q2,q.q3].filter(Boolean).join(' | ');
            return `P${i+1}: ${resp||'sem respostas'}`;
        }).join('\n');

        const prompt = `Você é um especialista em UX e avaliação de sistemas. Analise os seguintes dados de teste de usabilidade do sistema SISGEM (gestão de merenda escolar com autenticação LDAP) e produza um relatório estruturado em português.

DADOS DA AVALIAÇÃO:
- Número de participantes: ${n}
- Pontuação SUS média: ${avgSUS!=null?avgSUS+'/100':'não disponível'}
- Scores individuais: ${scores.join(', ')||'não disponível'}

RESULTADOS POR TAREFA (19 tarefas):
${taskSummary}

RESPOSTAS QUALITATIVAS (amostra):
${qualSummary}

Produza um relatório com as seguintes seções claramente delimitadas:

1. SUMÁRIO EXECUTIVO (3-4 linhas)
2. AVALIAÇÃO GERAL DE USABILIDADE (SUS e contexto)
3. PONTOS FORTES IDENTIFICADOS (máx. 4 itens)
4. PROBLEMAS CRÍTICOS E RECOMENDAÇÕES (ordenados por prioridade, máx. 6 itens)
5. ANÁLISE POR MÓDULO (área pública, autenticação, gestão escolar, contratos, merenda, dados)
6. RECOMENDAÇÕES DE REDESIGN (curto, médio e longo prazo)
7. PRÓXIMOS PASSOS SUGERIDOS

Seja objetivo, preciso e forneça recomendações acionáveis. Base suas análises nos dados fornecidos.`;

        try {
            const res = await fetch('https://api.anthropic.com/v1/messages', {
                method:'POST',
                headers:{'Content-Type':'application/json'},
                body:JSON.stringify({
                    model:'claude-sonnet-4-20250514',
                    max_tokens:1000,
                    messages:[{role:'user',content:prompt}]
                })
            });
            const data = await res.json();
            const text = data.content?.map(b=>b.text||'').join('')||'Erro ao gerar relatório.';
            reportEl.className = 'ai-report';
            reportEl.textContent = text;
            exportBtn.style.display = 'inline-flex';
            window._lastReport = text;
        } catch(e) {
            reportEl.className = 'ai-report';
            reportEl.textContent = 'Erro ao conectar com a API. Verifique sua conexão e tente novamente.\n\n'+e.message;
        }
    }

    function exportReportTXT() {
        if (!window._lastReport) return;
        download(window._lastReport, 'SISGEM_relatorio_usabilidade.txt', 'text/plain');
        toast('↓ Relatório exportado');
    }

    // ══════════════════════════════════════════════════════
    // TOAST
    // ══════════════════════════════════════════════════════
    function toast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(()=>t.classList.remove('show'), 2800);
    }
</script>
</body>
</html>
