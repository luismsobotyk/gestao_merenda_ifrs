@extends('dashboard.layout')

@section('content')
    <div class="pt-4 pb-3 mb-4 d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Painel de Controle</h1>
            <p class="text-muted mb-0">Bem-vindo(a) ao SISGEM - Sistema de Gestão da Merenda Escolar.</p>
        </div>
    </div>

    <style>
        .card-dash {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card-dash:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .card-dash .card-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding-top: 1.75rem;
        }

        .dash-icon-wrapper {
            width: 100%;
            height: 4.75rem;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1rem;
            flex-shrink: 0;
        }

        .dash-icon {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 4.75rem;
            height: 4.75rem;
            font-size: 2.5rem;
            line-height: 1;
        }

        .dash-icon::before {
            display: block;
            line-height: 1;
            vertical-align: 0;
        }

        .card-dash .card-title {
            margin-top: 0;
            margin-bottom: .75rem;
        }

        .card-dash .card-text {
            margin-bottom: 0;
        }

        .card,.card-footer{
            background-color: #f5f5f5;
        }

        /* ==========================================
           Classes Personalizadas: Lilás
           ========================================== */
        .text-lilas {
            color: #9b59b6 !important; /* Tom de lilás/roxo com bom contraste */
        }

        .bg-lilas {
            background-color: rgba(155, 89, 182, 0.12) !important; /* Fundo com opacidade imitando o Bootstrap */
        }

        /* ==========================================
           Botões do Dashboard (Borda da cor, texto escurecido)
           ========================================== */
        .btn-dash {
            border: 1px solid transparent;
            background-color: transparent;
            transition: all 0.15s ease-in-out;
        }

        /* Success (Verde) */
        .btn-dash-success {
            border-color: #198754;
            color: color-mix(in srgb, #198754, black 35%);
        }
        .btn-dash-success:hover { background-color: #198754; color: #fff; }

        /* Primary (Azul) */
        .btn-dash-primary {
            border-color: #0d6efd;
            color: color-mix(in srgb, #0d6efd, black 35%);
        }
        .btn-dash-primary:hover { background-color: #0d6efd; color: #fff; }

        /* Warning (Amarelo) - Misturado com mais preto para dar leitura */
        .btn-dash-warning {
            border-color: #ffc107;
            color: color-mix(in srgb, #ffc107, black 55%);
        }
        .btn-dash-warning:hover { background-color: #ffc107; color: #000; }

        /* Info (Ciano) - Misturado com mais preto para dar leitura */
        .btn-dash-info {
            border-color: #0dcaf0;
            color: color-mix(in srgb, #0dcaf0, black 50%);
        }
        .btn-dash-info:hover { background-color: #0dcaf0; color: #000; }

        /* Lilás (Roxo) */
        .btn-dash-lilas {
            border-color: #9b59b6;
            color: color-mix(in srgb, #9b59b6, black 35%);
        }
        .btn-dash-lilas:hover { background-color: #9b59b6; color: #fff; }

        /* Secondary (Cinza) */
        .btn-dash-secondary {
            border-color: #6c757d;
            color: color-mix(in srgb, #6c757d, black 35%);
        }
        .btn-dash-secondary:hover { background-color: #6c757d; color: #fff; }
    </style>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">

        <div class="col">
            <div class="card h-100 border-0 shadow-sm text-center p-3 card-dash">
                <div class="card-body">
                    <div class="dash-icon-wrapper">
                        <i class="bi bi-upc-scan dash-icon text-success bg-success bg-opacity-10 rounded-4"></i>
                    </div>

                    <h5 class="card-title fw-bold">Totem de Retirada</h5>

                    <p class="card-text text-muted small">
                        Inicie o modo Totem de autoatendimento para leitura das matrículas dos alunos.
                    </p>
                </div>

                <div class="card-footer border-0 pb-3 pt-0">
                    <a href="{{ route('retirada.totem') }}" class="btn btn-dash btn-dash-success w-100 fw-medium">
                        Abrir Totem
                    </a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 border-0 shadow-sm text-center p-3 card-dash">
                <div class="card-body">
                    <div class="dash-icon-wrapper">
                        <i class="bi bi-cup-straw dash-icon text-primary bg-primary bg-opacity-10 rounded-4"></i>
                    </div>

                    <h5 class="card-title fw-bold">Cardápios</h5>

                    <p class="card-text text-muted small">
                        Crie cardápios semestrais, configure a grade padrão semanal e adicione dias excepcionais.
                    </p>
                </div>

                <div class="card-footer border-0 pb-3 pt-0">
                    <a href="{{ route('cardapio') }}" class="btn btn-dash btn-dash-primary w-100 fw-medium">
                        Acessar Cardápios
                    </a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 border-0 shadow-sm text-center p-3 card-dash">
                <div class="card-body">
                    <div class="dash-icon-wrapper">
                        <i class="bi bi-file-earmark-plus dash-icon text-warning bg-warning bg-opacity-10 rounded-4"></i>
                    </div>

                    <h5 class="card-title fw-bold">Novo Contrato</h5>

                    <p class="card-text text-muted small">
                        Cadastre um novo contrato, vinculando fornecedores, itens licitados e valores globais.
                    </p>
                </div>

                <div class="card-footer border-0 pb-3 pt-0">
                    <a href="{{ route('contrato.criar') }}" class="btn btn-dash btn-dash-warning w-100 fw-medium">
                        Criar Contrato
                    </a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 border-0 shadow-sm text-center p-3 card-dash">
                <div class="card-body">
                    <div class="dash-icon-wrapper">
                        <i class="bi bi-mortarboard dash-icon text-info bg-info bg-opacity-10 rounded-4"></i>
                    </div>

                    <h5 class="card-title fw-bold">Cursos</h5>

                    <p class="card-text text-muted small">
                        Sincronize a base de dados com a API do SIGAA e gerencie as permissões de merenda por curso.
                    </p>
                </div>

                <div class="card-footer border-0 pb-3 pt-0">
                    <a href="{{ route('cursos.index') }}" class="btn btn-dash btn-dash-info w-100 fw-medium">
                        Acessar Cursos
                    </a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 border-0 shadow-sm text-center p-3 card-dash">
                <div class="card-body">
                    <div class="dash-icon-wrapper">
                        <i class="bi bi-people dash-icon text-lilas bg-lilas rounded-4"></i>
                    </div>

                    <h5 class="card-title fw-bold">Discentes Autorizados</h5>

                    <p class="card-text text-muted small">
                        Consulte a listagem de alunos matriculados, turmas e acompanhe o status de direito à merenda.
                    </p>
                </div>

                <div class="card-footer border-0 pb-3 pt-0">
                    <a href="{{ route('alunos.index') }}" class="btn btn-dash btn-dash-lilas w-100 fw-medium">
                        Acessar Alunos
                    </a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 border-0 shadow-sm text-center p-3 card-dash">
                <div class="card-body">
                    <div class="dash-icon-wrapper">
                        <i class="bi bi-gear dash-icon text-secondary bg-secondary bg-opacity-10 rounded-4"></i>
                    </div>

                    <h5 class="card-title fw-bold">Gerenciar Usuários</h5>

                    <p class="card-text text-muted small">
                        Autorize novos operadores do sistema consultando diretamente as contas do diretório LDAP.
                    </p>
                </div>

                <div class="card-footer border-0 pb-3 pt-0">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-dash btn-dash-secondary w-100 fw-medium">
                        Acessar Usuários
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection
