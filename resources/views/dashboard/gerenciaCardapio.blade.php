@extends('dashboard.layout')

@section('custom_css')
    <style>
        /* Estilos para a Grid do Cardápio */
        .menu-grid-table {
            table-layout: fixed; /* Força colunas de largura igual */
        }
        .menu-grid-table td {
            vertical-align: top;
            height: 120px; /* Altura mínima */
            transition: background-color 0.2s;
            cursor: pointer;
        }
        .menu-grid-table td:hover {
            background-color: rgba(25, 135, 84, 0.05); /* Verde IFRS bem claro */
        }
        .meal-time-column {
            width: 10%; /* Coluna de horário mais estreita */
            background-color: var(--bs-body-tertiary);
            font-weight: bold;
        }
        .add-food-btn {
            opacity: 0.3;
            transition: opacity 0.2s;
        }
        .menu-grid-table td:hover .add-food-btn {
            opacity: 1;
        }

        /* Estilos para a seção de Exceções */
        .exception-card {
            border-left: 4px solid var(--bs-warning); /* Destaque visual em amarelo */
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Criar Novo Cardápio Flexível</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="#" class="btn btn-sm btn-outline-secondary">Cancelar</a>
                <button type="submit" form="formCardapio" class="btn btn-sm btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                        <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.293l2.646-2.646a.5.5 0 0 1 .708 0"/>
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                    </svg>
                    Salvar Cardápio e Exceções
                </button>
            </div>
        </div>
    </div>

    <form id="formCardapio" method="POST" action="#">
        @csrf
        <div class="row">
            {{-- COLUNA ESQUERDA: Estrutura Base --}}
            <div class="col-md-4 mb-4">
                {{-- Card 1: Informações Gerais (Mantido) --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light text-uppercase fw-bold small text-body-secondary">
                        1. Informações Gerais e Vigência
                    </div>
                    <div class="card-body row g-3">
                        <div class="col-12">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Nome do Cardápio</label>
                            <input type="text" class="form-control" name="nome_cardapio" placeholder="Ex: Regular - 1º Semestre 2026" required autofocus>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Início da Vigência</label>
                            <input type="date" class="form-control" name="data_inicio" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Fim da Vigência</label>
                            <input type="date" class="form-control" name="data_fim" required>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Horários Padrão (Mantido) --}}
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0 pt-3">
                        <h5 class="card-title mb-0 text-uppercase fw-bold text-body-secondary small">2. Definir Dias e Horários Padrão (Repetição)</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAdicionarHorario">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.585-.189l.136-.992c.221.03.436.069.645.115zm1.905 1.14c-.161-.137-.33-.268-.507-.393l.583-.812c.22.157.43.323.63.496zm1.59 1.815a7 7 0 0 0-.394-.507l.812-.583a8 8 0 0 1 .496.63zM16 8q0 .148-.022.294l-.992-.136A7 7 0 0 0 16 8M1.019 7.485A7 7 0 0 0 1 8V9h1V8a6 6 0 0 1 .012-.387zm-.45 2.004c.03.221.069.436.115.645l.992-.136a7 7 0 0 1-.189-.585zM1.14 11.394c.157.22.323.43.496.63l.812-.583a7 7 0 0 1-.393-.507zm1.815 1.59a8 8 0 0 1 .496-.63l-.583-.812a7 7 0 0 0-.507.394zm1.815 1.59a7 7 0 0 1-.63-.393l.583-.812c.137.161.268.33.393.507z"/>
                            </svg>
                            Adicionar
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush" id="listaHorarios">
                            {{-- Exemplo estático --}}
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3 h-row">
                                <div>
                                    <strong class="text-uppercase text-primary">MANHÃ (M) - 10:00 - 10:15</strong><br>
                                    <span class="text-muted small">Superior Tecnólogo, Ensino Médio</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- COLUNA DIREITA: Grid e Exceções --}}
            <div class="col-md-8">
                {{-- Card 3: Grid Semanal Padrão (Mantido) --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-3">
                        <h5 class="card-title mb-0 text-uppercase fw-bold text-body-secondary small">3. Preencher Alimentos na Grid Semanal Padrão</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered menu-grid-table mb-0">
                                <thead class="table-light text-uppercase small text-center">
                                <tr>
                                    <th class="meal-time-column">Horário</th>
                                    <th>Segunda</th>
                                    <th>Terça</th>
                                    <th>Quarta</th>
                                    <th>Quinta</th>
                                    <th>Sexta</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{-- Exemplo de linha --}}
                                <tr>
                                    <td class="meal-time-column text-center align-middle">
                                        MANHÃ<br>10:00-10:15
                                    </td>
                                    {{-- Célula Vazia com data-attributes para o JS capturar --}}
                                    <td data-bs-toggle="modal" data-bs-target="#modalAdicionarAlimento" data-contexto="padrao" data-dia="segunda" data-horario="10:00">
                                        <div class="text-center mt-4">
                                            <button type="button" class="btn btn-sm btn-outline-success add-food-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                                                </svg>
                                                Alimentos
                                            </button>
                                        </div>
                                    </td>
                                    {{-- Outras células vazias --}}
                                    <td data-bs-toggle="modal" data-bs-target="#modalAdicionarAlimento" data-contexto="padrao" data-dia="terca" data-horario="10:00"></td>
                                    <td data-bs-toggle="modal" data-bs-target="#modalAdicionarAlimento" data-contexto="padrao" data-dia="quarta" data-horario="10:00"></td>
                                    <td data-bs-toggle="modal" data-bs-target="#modalAdicionarAlimento" data-contexto="padrao" data-dia="quinta" data-horario="10:00"></td>
                                    <td data-bs-toggle="modal" data-bs-target="#modalAdicionarAlimento" data-contexto="padrao" data-dia="sexta" data-horario="10:00"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- NOVA SEÇÃO: Exceções e Dias Especiais (A Liberdade Solicitada) --}}
                <div class="card shadow-sm exception-card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0 pt-3">
                        <h5 class="card-title mb-0 text-uppercase fw-bold text-body-secondary small text-warning">4. Exceções e Dias Especiais (Sábados, Eventos)</h5>
                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalAdicionarExcecao">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-plus" viewBox="0 0 16 16">
                                <path d="M8 7a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11.5a.5.5 0 0 1-1 0V10H6a.5.5 0 0 1 0-1h1.5V7.5A.5.5 0 0 1 8 7z"/>
                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                            </svg>
                            Adicionar Data Específica
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0 align-middle">
                                <thead class="table-light text-uppercase small">
                                <tr>
                                    <th>Data Exata</th>
                                    <th>Refeição (Horário)</th>
                                    <th>Tipo</th>
                                    <th>Alimentos</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{-- Exemplo de Sábado Letivo --}}
                                <tr>
                                    <td>
                                        <strong>14/03/2026</strong><br>
                                        <span class="text-muted small">Sábado</span>
                                    </td>
                                    <td>
                                        <strong class="text-primary text-uppercase">Manhã (M)</strong><br>
                                        <span class="text-muted small">10:00 - 10:15</span>
                                    </td>
                                    <td><span class="badge bg-info text-uppercase">Inclusão (Sábado)</span></td>
                                    <td>
                                        <ul class="list-unstyled small mb-0">
                                            <li>• Sanduíche Natural Frango (1 un)</li>
                                            <li>• Maçã Fuji (1 un)</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-xs btn-outline-danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                {{-- Exemplo de Substituição PONTUAL (ex: evento numa Quarta) --}}
                                <tr>
                                    <td>
                                        <strong>18/03/2026</strong><br>
                                        <span class="text-muted small">Quarta-feira</span>
                                    </td>
                                    <td>
                                        <strong class="text-primary text-uppercase">Manhã (M)</strong><br>
                                        <span class="text-muted small">10:00 - 10:15</span>
                                    </td>
                                    <td><span class="badge bg-danger text-uppercase">Substituição (Evento)</span></td>
                                    <td>
                                        <ul class="list-unstyled small mb-0">
                                            <li>• Cachorro-Quente Especial (1 un)</li>
                                            <li>• Refrigerante Caçulinha (1 un)</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-xs btn-outline-danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- MODAL ADICIONAR ALIMENTO (Etapa 3, MODIFICADO) --}}
    {{-- Adicionei tratamento de CONTEXTO (padrao ou excecao) --}}
    <div class="modal fade" id="modalAdicionarAlimento" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="formAdicionarAlimento">
                <div class="modal-header bg-success text-white">
                    <h1 class="modal-title h5">Preencher Alimentos</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-secondary shadow-none border-0 p-2 text-center small mb-3">
                        {{-- Campo oculto para saber se é grid padrão ou exceção --}}
                        <input type="hidden" id="contextoModal" name="contexto">
                        <span id="labelDiaModal">...</span> / <span id="labelHorarioModal">...</span>
                    </div>

                    {{-- Conteúdo do Modal igual ao anterior, focando na seleção de alimentos --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold text-uppercase text-body-secondary small">Selecione Alimento/Preparação (Do Estoque)</label>
                        <select class="form-select border-primary" id="selectAlimento" name="alimento_id" required>
                            <option value="" selected disabled>Escolha...</option>
                            <option value="1">Arroz Integral (kg)</option>
                            <option value="2">Frango Desfiado (kg)</option>
                        </select>
                    </div>
                    {{-- ... restante dos campos do modal alimento ... --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Confirmar Alimentos</button>
                </div>
            </form>
        </div>
    </div>

    {{-- NOVO MODAL: ADICIONAR EXCEÇÃO (A Liberdade Solicitada) --}}
    <div class="modal fade" id="modalAdicionarExcecao" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" id="formAdicionarExcecao">
                <div class="modal-header bg-warning text-dark">
                    <h1 class="modal-title h5">Adicionar Dia Especial ou Evento Pontual</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        {{-- REQUISITO: Liberdade Total de Data --}}
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Data Exata do Evento</label>
                            <input type="date" class="form-control border-warning" name="data_exata" required value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Tipo de Exceção</label>
                            <select class="form-select border-warning" name="tipo_excecao" required>
                                <option value="" selected disabled>Escolha...</option>
                                <option value="inclusao">Inclusão (ex: Sábado letivo)</option>
                                <option value="substituicao">Substituição (ex: Mudar cardápio da Quarta)</option>
                            </select>
                        </div>

                        {{-- Seleção de Horário (Reutiliza a lógica dos horários já cadastrados) --}}
                        <div class="col-12 mt-4">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Selecione o Horário (já cadastrado na Coluna Esq.)</label>
                            <select class="form-select border-primary form-select-lg" name="horario_id" required>
                                <option value="" selected disabled>Escolha o horário...</option>
                                {{-- @foreach($cardapio->horarios as $horario) --}}
                                <option value="1">MANHÃ (10:00 - 10:15)</option>
                                <option value="2">VESPERTINO (15:30 - 15:45)</option>
                                {{-- @endforeach --}}
                            </select>
                        </div>

                        {{-- Link visual para o modal de alimentos --}}
                        <div class="col-12 mt-4 d-grid">
                            <div class="alert alert-info small">
                                Após preencher os dados acima, clique no botão abaixo para escolher os alimentos deste dia especial.
                            </div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdicionarAlimento" data-contexto="excecao">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-egg-fried" viewBox="0 0 16 16">
                                    <path d="M8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                    <path d="M13.997 5.17a5 5 0 0 0-8.162-4.772 5 5 0 0 0-2.071 6.551A5 5 0 0 0 1.002 11a5 5 0 0 0 5.485 4.965 5 5 0 0 0 6.136-2.618A5 5 0 0 0 13.997 5.17M8.5 14c-1.306 0-2.433-.827-2.903-2h11.234C16.433 13.173 15.306 14 14 14M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5m4.39-4.38a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5m-1.39 4.38a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5"/>
                                </svg>
                                Preencher Alimentos para a Exceção
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Salvar Dia Especial no Cardápio</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        // Lógica JS Básica para usabilidade dos Modais (Atualizada para contexto)

        const modalAlimento = document.getElementById('modalAdicionarAlimento');
        const labelDiaModal = document.getElementById('labelDiaModal');
        const labelHorarioModal = document.getElementById('labelHorarioModal');
        const contextoModalInput = document.getElementById('contextoModal');

        modalAlimento.addEventListener('show.bs.modal', function (event) {
            // Célula (td) que disparou o modal
            const td = event.relatedTarget;

            // Captura o CONTEXTO (Se vem da grid padrão ou da exceção)
            const contexto = td.getAttribute('data-contexto');
            contextoModalInput.value = contexto;

            if(contexto === 'padrao') {
                // Contexto da Grid Semanal (Mantido igual)
                const dia = td.getAttribute('data-dia');
                const horario = td.getAttribute('data-horario');
                labelDiaModal.innerText = 'Regra Semanal: ' + dia.charAt(0).toUpperCase() + dia.slice(1);
                labelHorarioModal.innerText = horario + 'h';
            } else {
                // Contexto da Exceção (data específica)
                // No cenário real, o JS leria o valor num modal anterior ou tabela
                labelDiaModal.innerText = 'Dia Especial / Sábado / Evento';
                labelHorarioModal.innerText = 'Horário Selecionado';
            }
        });
    </script>
@endsection
