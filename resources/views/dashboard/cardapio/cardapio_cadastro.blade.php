@extends('dashboard.layout')

@section('custom_css')
    <style>
        .menu-grid-table th, .menu-grid-table td { vertical-align: middle; }
        .meal-time-column { width: 120px; font-weight: bold; background-color: #f8f9fa; }
        .h-row { border-left: 4px solid #0d6efd; }
        .add-food-btn { border-style: dashed; width: 100%; }
        .exception-card { border-left: 4px solid #ffc107; }

        .cell-actions {
            opacity: 0.3;
            transition: opacity 0.2s;
        }
        td:hover .cell-actions {
            opacity: 1;
        }
        .btn-cell-action {
            padding: 2px 6px;
            font-size: 0.7rem;
            line-height: 1;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ isset($cardapio) ? 'Editando Cardápio: ' . $cardapio->nome : 'Criar Novo Cardápio Flexível' }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('cardapio') }}" class="btn btn-sm btn-outline-secondary">Cancelar</a>

                <button type="submit" form="formCardapio" class="btn btn-sm btn-success shadow-sm fw-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-arrow-up me-1" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z"/>
                        <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/>
                    </svg>
                    Salvar
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show small shadow-sm" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li><i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- COLUNA ESQUERDA --}}
        <div class="col-md-4 mb-4">
            <form id="formCardapio" method="POST" action="{{ isset($cardapio) ? '#' : route('cardapio.salvar') }}">
                @csrf
                <div class="card shadow-sm mb-4 border-primary">
                    <div class="card-header bg-primary bg-opacity-10 text-primary text-uppercase fw-bold small">
                        1. Informações Gerais e Vigência
                    </div>
                    <div class="card-body row g-3">
                        <div class="col-12">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Nome do Cardápio</label>
                            <input type="text" class="form-control" name="nome_cardapio" required value="{{ $cardapio->nome ?? old('nome_cardapio') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Início da Vigência</label>
                            <input type="date" class="form-control" name="data_inicio" required value="{{ isset($cardapio) ? \Carbon\Carbon::parse($cardapio->data_inicio)->format('Y-m-d') : date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Fim da Vigência</label>
                            <input type="date" class="form-control" name="data_fim" required value="{{ isset($cardapio) ? \Carbon\Carbon::parse($cardapio->data_fim)->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                </div>
            </form>

            @if(isset($cardapio))
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0 pt-3">
                        <h5 class="card-title mb-0 text-uppercase fw-bold text-body-secondary small">2. Horários (Repetição)</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAdicionarHorario">
                            + Adicionar
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush" id="listaHorarios"></ul>
                    </div>
                </div>
            @endif
        </div>

        {{-- COLUNA DIREITA --}}
        <div class="col-md-8">
            @if(!isset($cardapio))
                <div class="alert alert-info shadow-sm border-0 d-flex align-items-center p-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-info-circle flex-shrink-0 me-3" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                    </svg>
                    <div>
                        <h5 class="alert-heading fw-bold">Quase lá!</h5>
                        <p class="mb-0">Preencha o Nome e a Vigência ao lado e clique em <strong>"Salvar"</strong>. Após isso, a Grade Semanal e os Dias Especiais serão liberados.</p>
                    </div>
                </div>
            @else
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-3">
                        <h5 class="card-title mb-0 text-uppercase fw-bold text-body-secondary small">3. Grid Semanal Padrão</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered menu-grid-table mb-0">
                                <thead class="table-light text-uppercase small text-center">
                                <tr>
                                    <th class="meal-time-column">Horário</th>
                                    <th>Seg.</th>
                                    <th>Ter.</th>
                                    <th>Qua.</th>
                                    <th>Qui.</th>
                                    <th>Sex.</th>
                                </tr>
                                </thead>
                                <tbody id="tbodyGrid"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm exception-card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0 pt-3">
                        <h5 class="card-title mb-0 text-uppercase fw-bold text-body-secondary small text-warning">4. Exceções e Dias Especiais</h5>
                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalAdicionarExcecao">
                            + Adicionar Data Específica
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0 align-middle">
                                <thead class="table-light text-uppercase small">
                                <tr>
                                    <th>Data Exata</th>
                                    <th>Refeição</th>
                                    <th>Tipo</th>
                                    <th>Alimentos</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tbody id="tbodyExcecoes"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- MODAIS --}}
    @if(isset($cardapio))
        <div class="modal fade" id="modalAdicionarHorario" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" id="formAdicionarHorario">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title h5">Adicionar Novo Horário (Refeição)</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-12">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Nome da Refeição</label>
                            <input type="text" class="form-control border-primary" name="nome" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Hora Início</label>
                            <input type="time" class="form-control" name="hora_inicio" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Hora Fim</label>
                            <input type="time" class="form-control" name="hora_fim" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Público / Descrição (Opcional)</label>
                            <input type="text" class="form-control" name="descricao_publico">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Adicionar na Tela</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="modalAdicionarAlimento" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" id="formAdicionarAlimento">
                    <div class="modal-header bg-success text-white">
                        <h1 class="modal-title h5">Selecionar Alimento</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-secondary shadow-none border-0 p-2 text-center small mb-3">
                            <span id="labelDiaModal">...</span> / <span id="labelHorarioModal">...</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase text-body-secondary small">Estoque Disponível</label>
                            <select class="form-select border-primary" id="selectAlimento" name="item_contrato_uuid" required>
                                <option value="" selected disabled>Escolha...</option>
                                @foreach($itensDisponiveis as $item)
                                    @php
                                        $empenhada = $item->itensEmpenho->sum('quantidade_empenhada');
                                        $consumida = $item->itensEmpenho->flatMap->itensPedido->sum('quantidade');
                                    @endphp
                                    <option value="{{ $item->id }}">{{ $item->nome }} (Saldo: {{ number_format($empenhada - $consumida, 2, ',', '.') }} {{ $item->unidade->sigla ?? 'un' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Inserir na Grade</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL ADICIONAR EXCEÇÃO (Reestruturado para Múltiplos Checkboxes) --}}
        <div class="modal fade" id="modalAdicionarExcecao" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form class="modal-content" id="formAdicionarExcecao">
                    <div class="modal-header bg-warning text-dark">
                        <h1 class="modal-title h5">Adicionar Dia Especial ou Evento</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Data Exata</label>
                            <input type="date" class="form-control border-warning" name="data_exata" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Tipo</label>
                            <select class="form-select border-warning" name="tipo_excecao" required>
                                <option value="" selected disabled>Escolha...</option>
                                <option value="inclusao">Inclusão (ex: Sábado letivo)</option>
                                <option value="substituicao">Substituição (Mudar cardápio do dia)</option>
                                <option value="supressao">Supressão (Dia Não Letivo)</option>
                            </select>
                        </div>
                        <div class="col-12 mt-4">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary d-block mb-2">Quais horários serão afetados neste dia?</label>
                            <div class="alert alert-light border border-secondary border-opacity-25 p-3">
                                <div class="mb-2">
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0" style="font-size: 0.75rem" onclick="toggleTodosHorariosExcecao(true)">Marcar Todos</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0" style="font-size: 0.75rem" onclick="toggleTodosHorariosExcecao(false)">Desmarcar Todos</button>
                                </div>
                                <div id="containerCheckboxesHorarios" class="row row-cols-1 row-cols-md-2 g-2 pt-2">
                                    {{-- Preenchido dinamicamente via JS --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Adicionar na Tela</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@section('custom_js')
    @if(isset($cardapio))
        @php
            $horariosIniciais = [];
            foreach($cardapio->horarios->sortBy('hora_inicio')->values() as $h) {
                $itens = [];
                foreach($h->itensPadrao as $ip) {
                    $itens[] = [
                        'dia_semana' => $ip->dia_semana,
                        'item_contrato_uuid' => $ip->item_contrato_uuid,
                        'nome_visual' => $ip->itemContrato->nome
                    ];
                }
                $horariosIniciais[] = [
                    'nome' => $h->nome,
                    'hora_inicio' => \Carbon\Carbon::parse($h->hora_inicio)->format('H:i'),
                    'hora_fim' => \Carbon\Carbon::parse($h->hora_fim)->format('H:i'),
                    'descricao_publico' => $h->descricao_publico,
                    'itens' => $itens
                ];
            }

            $excecoesIniciais = [];
            foreach($cardapio->excecoes->sortBy('data_exata')->values() as $e) {
                $itensExc = [];
                foreach($e->itens as $ie) {
                    $itensExc[] = [
                        'item_contrato_uuid' => $ie->item_contrato_uuid,
                        'nome_visual' => $ie->itemContrato->nome
                    ];
                }
                $hIndex = $cardapio->horarios->sortBy('hora_inicio')->values()->search(fn($h) => $h->id === $e->cardapio_horario_id);

                $excecoesIniciais[] = [
                    'data_exata' => $e->data_exata,
                    'tipo' => $e->tipo,
                    'horario_index' => $hIndex,
                    'itens' => $itensExc
                ];
            }
        @endphp

        <script>
            let state = {
                horarios: {!! json_encode($horariosIniciais) !!},
                excecoes: {!! json_encode($excecoesIniciais) !!}
            };

            let clipboard = null;

            const diasMapa = [ {nome: 'Segunda', num: 1}, {nome: 'Terça', num: 2}, {nome: 'Quarta', num: 3}, {nome: 'Quinta', num: 4}, {nome: 'Sexta', num: 5} ];

            let targetContexto = '';
            let targetHIndex = null;
            let targetDiaNum = null;
            let targetExcIndex = null;

            function renderAll() {
                state.horarios.sort((a, b) => a.hora_inicio.localeCompare(b.hora_inicio));
                renderHorarios();
                renderGrid();
                renderExcecoes();
                atualizarCheckboxesExcecoes();
            }

            function renderHorarios() {
                const ul = document.getElementById('listaHorarios');
                ul.innerHTML = '';
                if(state.horarios.length === 0) {
                    ul.innerHTML = '<li class="list-group-item text-center text-muted p-3 small">Nenhum horário cadastrado.</li>';
                    return;
                }

                state.horarios.forEach((h, index) => {
                    ul.innerHTML += `
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3 h-row">
                        <div>
                            <strong class="text-uppercase text-primary">${h.nome} - ${h.hora_inicio} às ${h.hora_fim}</strong><br>
                            <span class="text-muted small">${h.descricao_publico || 'Geral'}</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeHorario(${index})" title="Excluir Horário">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/></svg>
                        </button>
                    </li>
                `;
                });
            }

            function renderGrid() {
                const tbody = document.getElementById('tbodyGrid');
                tbody.innerHTML = '';
                if(state.horarios.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted p-4">Adicione um horário no Passo 2 para gerar a grid.</td></tr>';
                    return;
                }

                state.horarios.forEach((h, hIndex) => {
                    let tr = `<tr><td class="meal-time-column text-center align-middle p-2">
                            <span class="text-primary fw-bold d-block">${h.nome}</span>
                            <span class="small text-muted">${h.hora_inicio}</span>
                          </td>`;

                    diasMapa.forEach(dia => {
                        const itensDoDia = h.itens.filter(i => parseInt(i.dia_semana) === dia.num);
                        let itensHtml = itensDoDia.map((item) => {
                            let realIndex = h.itens.indexOf(item);
                            return `
                            <div class="badge bg-light text-dark border border-secondary border-opacity-25 d-flex justify-content-between align-items-center text-wrap text-start lh-sm p-2 mb-1">
                                <span>${item.nome_visual}</span>
                                <button type="button" class="btn-close text-danger ms-2" style="font-size: 0.5rem;" onclick="removeItemGrid(${hIndex}, ${realIndex})"></button>
                            </div>
                        `;
                        }).join('');

                        let botaoColar = (clipboard !== null)
                            ? `<button type="button" class="btn btn-cell-action btn-success me-1" onclick="colarCelula(${hIndex}, ${dia.num})" title="Colar alimentos copiados aqui">Colar</button>`
                            : '';

                        tr += `
                        <td class="align-top p-2" style="min-width: 150px;">
                            <div class="d-flex justify-content-between align-items-center mb-1 cell-actions">
                                <div class="d-flex">
                                    <button type="button" class="btn btn-cell-action btn-outline-secondary me-1" onclick="copiarCelula(${hIndex}, ${dia.num})" title="Copiar alimentos deste quadrado">Copiar</button>
                                    ${botaoColar}
                                </div>
                            </div>
                            <div class="d-flex flex-column mb-2">${itensHtml}</div>

                            <button type="button" class="btn btn-sm btn-outline-success add-food-btn py-1 mt-auto w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#modalAdicionarAlimento"
                                data-contexto="padrao"
                                data-horario-index="${hIndex}"
                                data-dia-num="${dia.num}"
                                data-dia-nome="${dia.nome}"
                                data-horario="${h.hora_inicio}">
                                + Alimento
                            </button>
                        </td>
                    `;
                    });
                    tr += '</tr>';
                    tbody.innerHTML += tr;
                });
            }

            function renderExcecoes() {
                const tbody = document.getElementById('tbodyExcecoes');
                tbody.innerHTML = '';
                if(state.excecoes.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted p-4">Nenhuma exceção cadastrada.</td></tr>';
                    return;
                }

                state.excecoes.forEach((exc, eIndex) => {
                    const horarioRef = state.horarios[exc.horario_index];
                    if(!horarioRef) return;

                    let badgeTipo = exc.tipo === 'inclusao' ? '<span class="badge bg-info text-uppercase">Inclusão</span>'
                        : exc.tipo === 'substituicao' ? '<span class="badge bg-warning text-dark text-uppercase">Substituição</span>'
                            : '<span class="badge bg-dark text-uppercase">Supressão</span>';

                    let tdAlimentos = '';
                    if(exc.tipo === 'supressao') {
                        tdAlimentos = '<span class="text-muted small fst-italic">Refeição cancelada.</span>';
                    } else {
                        let itensHtml = exc.itens.map((item, iIndex) => `
                        <div class="badge bg-light text-dark border border-secondary border-opacity-25 d-flex justify-content-between align-items-center text-wrap text-start lh-sm p-2 mb-1">
                            <span>${item.nome_visual}</span>
                            <button type="button" class="btn-close text-danger ms-2" style="font-size: 0.5rem;" onclick="removeItemExcecao(${eIndex}, ${iIndex})"></button>
                        </div>
                    `).join('');

                        let botaoColarExc = (clipboard !== null)
                            ? `<button type="button" class="btn btn-cell-action btn-success me-1 mb-2" onclick="colarCelulaExcecao(${eIndex})" title="Colar alimentos copiados aqui">Colar</button>`
                            : '';

                        tdAlimentos = `
                        <div class="mb-1 cell-actions">
                            <button type="button" class="btn btn-cell-action btn-outline-secondary me-1" onclick="copiarCelulaExcecao(${eIndex})" title="Copiar alimentos desta exceção">Copiar</button>
                            ${botaoColarExc}
                        </div>
                        <div class="d-flex flex-column mb-2">${itensHtml}</div>
                        <button type="button" class="btn btn-sm btn-outline-success add-food-btn py-1"
                            data-bs-toggle="modal"
                            data-bs-target="#modalAdicionarAlimento"
                            data-contexto="excecao"
                            data-excecao-index="${eIndex}"
                            data-data-nome="${exc.data_exata}">
                            + Alimento
                        </button>
                    `;
                    }

                    tbody.innerHTML += `
                    <tr>
                        <td class="align-middle"><strong>${exc.data_exata.split('-').reverse().join('/')}</strong></td>
                        <td class="align-middle"><strong class="text-primary text-uppercase">${horarioRef.nome}</strong><br><span class="text-muted small">${horarioRef.hora_inicio}</span></td>
                        <td class="align-middle">${badgeTipo}</td>
                        <td class="align-top p-2" style="min-width: 200px;">${tdAlimentos}</td>
                        <td class="align-middle text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeExcecao(${eIndex})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/></svg>
                            </button>
                        </td>
                    </tr>
                `;
                });
            }

            // Atualiza dinamicamente os Checkboxes do Modal de Exceção baseado nos horários existentes
            function atualizarCheckboxesExcecoes() {
                const container = document.getElementById('containerCheckboxesHorarios');
                if(!container) return;

                container.innerHTML = '';
                if(state.horarios.length === 0) {
                    container.innerHTML = '<div class="col-12 text-muted small">Nenhum horário cadastrado no Passo 2 ainda.</div>';
                    return;
                }

                state.horarios.forEach((h, index) => {
                    container.innerHTML += `
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input check-horario-excecao" type="checkbox" value="${index}" id="chkHorario_${index}">
                                <label class="form-check-label small" for="chkHorario_${index}">
                                    <strong>${h.nome}</strong> (${h.hora_inicio})
                                </label>
                            </div>
                        </div>
                    `;
                });
            }

            // Função auxiliar dos botões Marcar/Desmarcar todos do modal de exceções
            function toggleTodosHorariosExcecao(status) {
                const checkboxes = document.querySelectorAll('.check-horario-excecao');
                checkboxes.forEach(cb => cb.checked = status);
            }

            // FUNÇÕES DE ÁREA DE TRANSFERÊNCIA DE CÉLULAS
            function copiarCelula(hIndex, diaNum) {
                const itensCopiados = state.horarios[hIndex].itens.filter(i => parseInt(i.dia_semana) === parseInt(diaNum));
                if(itensCopiados.length === 0) {
                    alert('Este quadrado está vazio. Nada para copiar!');
                    return;
                }
                clipboard = itensCopiados.map(item => ({
                    item_contrato_uuid: item.item_contrato_uuid,
                    nome_visual: item.nome_visual
                }));
                renderAll();
            }

            function colarCelula(hIndex, diaNum) {
                if(!clipboard) return;
                clipboard.forEach(item => {
                    state.horarios[hIndex].itens.push({
                        dia_semana: diaNum,
                        item_contrato_uuid: item.item_contrato_uuid,
                        nome_visual: item.nome_visual
                    });
                });
                renderAll();
            }

            function copiarCelulaExcecao(eIndex) {
                const itensCopiados = state.excecoes[eIndex].itens;
                if(itensCopiados.length === 0) {
                    alert('Esta exceção está vazia. Nada para copiar!');
                    return;
                }
                clipboard = itensCopiados.map(item => ({
                    item_contrato_uuid: item.item_contrato_uuid,
                    nome_visual: item.nome_visual
                }));
                renderAll();
            }

            function colarCelulaExcecao(eIndex) {
                if(!clipboard) return;
                clipboard.forEach(item => {
                    state.excecoes[eIndex].itens.push({
                        item_contrato_uuid: item.item_contrato_uuid,
                        nome_visual: item.nome_visual
                    });
                });
                renderAll();
            }

            // AÇÕES DE MANIPULAÇÃO PADRÃO
            function removeHorario(index) {
                if(confirm('Excluir este horário apagará a linha inteira na Grid e suas exceções. Continuar?')) {
                    state.horarios.splice(index, 1);
                    state.excecoes = state.excecoes.filter(e => parseInt(e.horario_index) !== parseInt(index));
                    state.excecoes.forEach(e => { if(e.horario_index > index) e.horario_index--; });
                    renderAll();
                }
            }
            function removeItemGrid(hIndex, itemIndex) { state.horarios[hIndex].itens.splice(itemIndex, 1); renderAll(); }
            function removeExcecao(index) { state.excecoes.splice(index, 1); renderAll(); }
            function removeItemExcecao(excIndex, itemIndex) { state.excecoes[excIndex].itens.splice(itemIndex, 1); renderAll(); }

            // PREENCHE O MODAL DE ALIMENTOS QUANDO ELE É ABERTO PELO BOOTSTRAP
            const modalAlimentoEl = document.getElementById('modalAdicionarAlimento');
            if (modalAlimentoEl) {
                modalAlimentoEl.addEventListener('show.bs.modal', function (event) {
                    const btn = event.relatedTarget;
                    if (!btn) return;

                    targetContexto = btn.getAttribute('data-contexto');

                    if (targetContexto === 'padrao') {
                        targetHIndex = btn.getAttribute('data-horario-index');
                        targetDiaNum = btn.getAttribute('data-dia-num');

                        document.getElementById('labelDiaModal').innerText = 'Grade: ' + btn.getAttribute('data-dia-nome');
                        document.getElementById('labelHorarioModal').innerText = btn.getAttribute('data-horario') + 'h';
                    } else if (targetContexto === 'excecao') {
                        targetExcIndex = btn.getAttribute('data-excecao-index');

                        const dataNome = btn.getAttribute('data-data-nome');
                        document.getElementById('labelDiaModal').innerText = 'Dia Especial: ' + dataNome.split('-').reverse().join('/');
                        document.getElementById('labelHorarioModal').innerText = 'Alimentos do Evento';
                    }
                });
            }

            // SALVANDO OS DADOS DOS MODAIS NO ESTADO LOCAL
            document.getElementById('formAdicionarHorario').addEventListener('submit', function(e) {
                e.preventDefault();
                state.horarios.push({ nome: this.nome.value, hora_inicio: this.hora_inicio.value, hora_fim: this.hora_fim.value, descricao_publico: this.descricao_publico.value, itens: [] });
                this.reset();
                this.closest('.modal').querySelector('.btn-close').click();
                renderAll();
            });

            // SALVA A EXCEÇÃO (Suporta múltiplos horários selecionados em loops)
            document.getElementById('formAdicionarExcecao').addEventListener('submit', function(e) {
                e.preventDefault();

                const dataExata = this.data_exata.value;
                const tipoExcecao = this.tipo_excecao.value;

                // Pega todos os indexes de horários que foram marcados
                const checkboxesMarcados = Array.from(document.querySelectorAll('.check-horario-excecao:checked'));

                if(checkboxesMarcados.length === 0) {
                    alert('Selecione pelo menos um horário para aplicar a exceção!');
                    return;
                }

                // Cria uma linha independente na lista para cada horário selecionado
                checkboxesMarcados.forEach(cb => {
                    state.excecoes.push({
                        data_exata: dataExata,
                        tipo: tipoExcecao,
                        horario_index: parseInt(cb.value),
                        itens: []
                    });
                });

                this.reset();
                this.closest('.modal').querySelector('.btn-close').click();
                renderAll();
            });

            document.getElementById('formAdicionarAlimento').addEventListener('submit', function(e) {
                e.preventDefault();
                const select = document.getElementById('selectAlimento');
                const novoItem = { item_contrato_uuid: this.item_contrato_uuid.value, nome_visual: select.options[select.selectedIndex].text.split(' (')[0] };

                if(targetContexto === 'padrao') {
                    novoItem.dia_semana = targetDiaNum;
                    state.horarios[targetHIndex].itens.push(novoItem);
                } else {
                    state.excecoes[targetExcIndex].itens.push(novoItem);
                }

                this.reset();
                this.closest('.modal').querySelector('.btn-close').click();
                renderAll();
            });

            // O SALVAMENTO FINAL NO BANCO DE DADOS (SYNC)
            document.getElementById('formCardapio').addEventListener('submit', async function(e) {
                e.preventDefault();

                const btnSalvar = document.querySelector('button[form="formCardapio"]');
                const txtOriginal = btnSalvar.innerHTML;

                btnSalvar.innerHTML = 'Salvando...';
                btnSalvar.disabled = true;

                const payload = {
                    nome_cardapio: this.nome_cardapio.value,
                    data_inicio: this.data_inicio.value,
                    data_fim: this.data_fim.value,
                    horarios: state.horarios,
                    excecoes: state.excecoes
                };

                try {
                    const response = await fetch(`/cardapio/{{ $cardapio->id ?? '' }}/sync`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();

                    if(result.success) {
                        alert('Cardápio sincronizado e salvo no banco de dados com sucesso!');
                        window.location.reload();
                    } else {
                        alert('Erro no servidor: ' + result.error);
                        btnSalvar.innerHTML = txtOriginal;
                        btnSalvar.disabled = false;
                    }
                } catch(e) {
                    console.error(e);
                    alert('Erro de comunicação. Verifique sua conexão.');
                    btnSalvar.innerHTML = txtOriginal;
                    btnSalvar.disabled = false;
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                renderAll();
            });
        </script>
    @endif
@endsection
