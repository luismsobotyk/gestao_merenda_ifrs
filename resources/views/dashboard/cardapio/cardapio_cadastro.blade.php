@extends('dashboard.layout')

@section('custom_css')
    <style>
        .menu-grid-table th, .menu-grid-table td { vertical-align: middle; }
        .meal-time-column { width: 120px; font-weight: bold; background-color: #f8f9fa; }
        .h-row { border-left: 4px solid #0d6efd; }
        .add-food-btn { border-style: dashed; width: 100%; }
        .exception-card { border-left: 4px solid #ffc107; }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ isset($cardapio) ? 'Editando Cardápio: ' . $cardapio->nome : 'Criar Novo Cardápio Flexível' }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('cardapio') }}" class="btn btn-sm btn-outline-secondary">Cancelar</a>

                {{-- O botão principal agora salva apenas a base do cardápio --}}
                <button type="submit" form="formCardapio" class="btn btn-sm btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                        <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.293l2.646-2.646a.5.5 0 0 1 .708 0"/>
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                    </svg>
                    Salvar Informações Iniciais
                </button>
            </div>
        </div>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger small shadow-sm">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulário Mestre (Passo 1) --}}
    {{-- A DIV ROW AGORA FICA POR FORA DO FORMULÁRIO --}}
    <div class="row">

        {{-- COLUNA ESQUERDA --}}
        <div class="col-md-4 mb-4">

            {{-- 1. O FORMULÁRIO MESTRE AGORA ENVOLVE APENAS O CARD 1 --}}
            <form id="formCardapio" method="POST" action="{{ isset($cardapio) ? route('cardapio.atualizar', $cardapio->id) : route('cardapio.salvar') }}">
                @csrf
                @if(isset($cardapio))
                    @method('PUT')
                @endif

                {{-- Card 1: Informações Gerais --}}
                <div class="card shadow-sm mb-4 border-primary">
                    <div class="card-header bg-primary bg-opacity-10 text-primary text-uppercase fw-bold small">
                        1. Informações Gerais e Vigência
                    </div>
                    <div class="card-body row g-3">
                        <div class="col-12">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Nome do Cardápio</label>
                            <input type="text" class="form-control" name="nome_cardapio" placeholder="Ex: Regular - 1º Semestre 2026" required value="{{ $cardapio->nome ?? old('nome_cardapio') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Início da Vigência</label>
                            <input type="date" class="form-control" name="data_inicio" required value="{{ isset($cardapio) ? \Carbon\Carbon::parse($cardapio->data_inicio)->format('Y-m-d') : (old('data_inicio') ?? date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Fim da Vigência</label>
                            <input type="date" class="form-control" name="data_fim" required value="{{ isset($cardapio) ? \Carbon\Carbon::parse($cardapio->data_fim)->format('Y-m-d') : old('data_fim') }}">
                        </div>
                    </div>
                </div>
            </form>
            {{-- AQUI ENCERRAMOS O FORMULÁRIO PRINCIPAL --}}


            {{-- 2. Card 2: Horários (Agora está livre e fora do form principal!) --}}
            @if(isset($cardapio))
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0 pt-3">
                        <h5 class="card-title mb-0 text-uppercase fw-bold text-body-secondary small">2. Horários (Repetição)</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAdicionarHorario">
                            + Adicionar
                        </button>
                    </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush" id="listaHorarios">
                                @forelse($cardapio->horarios->sortBy('hora_inicio') as $horario)
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-3 h-row">
                                        <div>
                                            <strong class="text-uppercase text-primary">
                                                {{ $horario->nome }} - {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} às {{ \Carbon\Carbon::parse($horario->hora_fim)->format('H:i') }}
                                            </strong><br>
                                            <span class="text-muted small">{{ $horario->descricao_publico ?? 'Geral' }}</span>
                                        </div>
                                        <div>
                                            {{-- Botão de Excluir Horário --}}
                                            <form action="{{ route('cardapio.horario.excluir', $horario->id) }}" method="POST" class="d-inline" onsubmit="return confirm('ATENÇÃO: Excluir este horário apagará todos os alimentos da linha dele na Grid. Continuar?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted p-3 small">Nenhum horário cadastrado ainda.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            {{-- COLUNA DIREITA: Grid e Exceções --}}
            <div class="col-md-8">
                @if(!isset($cardapio))
                    {{-- TELA BLOQUEADA --}}
                    <div class="alert alert-info shadow-sm border-0 d-flex align-items-center p-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-info-circle flex-shrink-0 me-3" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                        </svg>
                        <div>
                            <h5 class="alert-heading fw-bold">Quase lá!</h5>
                            <p class="mb-0">Preencha o Nome e a Vigência ao lado e clique em <strong>"Salvar Informações Iniciais"</strong>. Após isso, a Grade Semanal e a ferramenta de Dias Especiais serão desbloqueadas para você organizar os alimentos.</p>
                        </div>
                    </div>
                @else
                    {{-- TELA DESBLOQUEADA --}}
                    {{-- Card 3: Grid Semanal Padrão --}}
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
                                    @php
                                        // Mapa para o banco de dados (1=Seg, 2=Ter...)
                                        $diasMapa = ['segunda' => 1, 'terca' => 2, 'quarta' => 3, 'quinta' => 4, 'sexta' => 5];
                                    @endphp

                                    @forelse($cardapio->horarios->sortBy('hora_inicio') as $horario)
                                        <tr>
                                            <td class="meal-time-column text-center align-middle p-2">
                                                <span class="text-primary fw-bold d-block">{{ $horario->nome }}</span>
                                                <span class="small text-muted">{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</span>
                                            </td>

                                            @foreach($diasMapa as $nomeDia => $numDia)
                                                <td class="align-top p-2" style="min-width: 140px;">

                                                    {{-- Lista os itens que JÁ ESTÃO no banco para este horário e dia --}}
                                                    <div class="d-flex flex-column gap-1 mb-2">
                                                        @foreach($horario->itensPadrao->where('dia_semana', $numDia) as $itemGrid)
                                                            <div class="badge bg-light text-dark border border-secondary border-opacity-25 d-flex justify-content-between align-items-center text-wrap text-start lh-sm p-2">
                                                                <span>{{ $itemGrid->itemContrato->nome }}</span>

                                                                <form action="{{ route('cardapio.item.excluir', $itemGrid->id) }}" method="POST" class="ms-2">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn-close text-danger" style="font-size: 0.5rem;" title="Remover"></button>
                                                                </form>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    {{-- Botão de Adicionar NOVO item nesta célula --}}
                                                    <button type="button" class="btn btn-sm btn-outline-success add-food-btn py-1 mt-auto w-100"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalAdicionarAlimento"
                                                            data-contexto="padrao"
                                                            data-dia-nome="{{ ucfirst($nomeDia) }}"
                                                            data-dia-num="{{ $numDia }}"
                                                            data-horario-id="{{ $horario->id }}">
                                                        + Alimento
                                                    </button>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center text-muted p-4">Adicione um horário no Passo 2 para gerar as linhas da tabela.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- NOVA SEÇÃO: Exceções e Dias Especiais --}}
                    <div class="card shadow-sm exception-card">
                        <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0 pt-3">
                            <h5 class="card-title mb-0 text-uppercase fw-bold text-body-secondary small text-warning">4. Exceções e Dias Especiais (Sábados, Eventos)</h5>
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
                                    <tbody>
                                    @forelse($cardapio->excecoes->sortBy('data_exata') as $excecao)
                                        <tr>
                                            <td class="align-middle">
                                                <strong>{{ \Carbon\Carbon::parse($excecao->data_exata)->format('d/m/Y') }}</strong><br>
                                                <span class="text-muted small">{{ \Carbon\Carbon::parse($excecao->data_exata)->translatedFormat('l') }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <strong class="text-primary text-uppercase">{{ $excecao->horario->nome }}</strong><br>
                                                <span class="text-muted small">{{ \Carbon\Carbon::parse($excecao->horario->hora_inicio)->format('H:i') }}</span>
                                            </td>
                                            <td class="align-middle">
                                                @if($excecao->tipo === 'inclusao')
                                                    <span class="badge bg-info text-uppercase">Inclusão</span>
                                                @elseif($excecao->tipo === 'substituicao')
                                                    <span class="badge bg-warning text-dark text-uppercase">Substituição</span>
                                                @else
                                                    <span class="badge bg-dark text-uppercase">Supressão</span>
                                                @endif
                                            </td>
                                            <td class="align-middle p-2" style="min-width: 200px;">

                                                {{-- Se for supressão, não tem comida! --}}
                                                @if($excecao->tipo === 'supressao')
                                                    <span class="text-muted small fst-italic">Refeição cancelada neste dia.</span>
                                                @else
                                                    {{-- Mostra os alimentos e o botão apenas se for Inclusão ou Substituição --}}
                                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                                        @foreach($excecao->itens as $itemExc)
                                                            <div class="badge bg-light text-dark border border-secondary border-opacity-25 d-flex align-items-center p-2">
                                                                {{ $itemExc->itemContrato->nome }}
                                                                <form action="{{ route('cardapio.excecao.item.excluir', $itemExc->id) }}" method="POST" class="ms-2">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn-close text-danger" style="font-size: 0.5rem;"></button>
                                                                </form>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-outline-success add-food-btn py-1"
                                                            data-bs-toggle="modal" data-bs-target="#modalAdicionarAlimento"
                                                            data-contexto="excecao" data-excecao-id="{{ $excecao->id }}" data-data-nome="{{ \Carbon\Carbon::parse($excecao->data_exata)->format('d/m/Y') }}">
                                                        + Alimento
                                                    </button>
                                                @endif

                                            </td>
                                            <td class="align-middle text-end">
                                                <form action="{{ route('cardapio.excecao.excluir', $excecao->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Excluir este dia especial?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/></svg></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted p-4">Nenhuma exceção cadastrada.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>

    {{-- OS MODAIS SÓ SÃO CARREGADOS SE O CARDÁPIO EXISTIR --}}
    @if(isset($cardapio))
        {{-- MODAL ADICIONAR HORÁRIO --}}
        <div class="modal fade" id="modalAdicionarHorario" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" method="POST" action="{{ route('cardapio.horario.salvar', $cardapio->id) }}">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title h5">Adicionar Novo Horário (Refeição)</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="alert alert-info small col-12 mb-0">
                            Ao cadastrar um horário, ele aparecerá automaticamente como uma nova linha na sua Grid Semanal.
                        </div>

                        <div class="col-12">
                            <label class="form-label small text-uppercase fw-bold text-body-secondary">Nome da Refeição</label>
                            <input type="text" class="form-control border-primary" name="nome" placeholder="Ex: MANHÃ (M) ou ALMOÇO" required>
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
                            <input type="text" class="form-control" name="descricao_publico" placeholder="Ex: Ensino Médio Integrado">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar Horário</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL ADICIONAR ALIMENTO --}}
        <div class="modal fade" id="modalAdicionarAlimento" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <<form class="modal-content" id="formAdicionarAlimento" method="POST">
                    @csrf
                    {{-- ADICIONE ESTES DOIS INPUTS OCULTOS NO INÍCIO DO MODAL BODY --}}
                    <input type="hidden" id="hiddenContexto" name="contexto">
                    <input type="hidden" id="hiddenHorarioId" name="cardapio_horario_id">
                    <input type="hidden" id="hiddenDiaSemana" name="dia_semana">
                    <div class="modal-header bg-success text-white">
                        <h1 class="modal-title h5">Preencher Alimentos</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-secondary shadow-none border-0 p-2 text-center small mb-3">
                            <input type="hidden" id="contextoModal" name="contexto">
                            <span id="labelDiaModal">...</span> / <span id="labelHorarioModal">...</span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase text-body-secondary small">Selecione Alimento (Com Saldo no Estoque)</label>
                            <select class="form-select border-primary" id="selectAlimento" name="item_contrato_uuid" required>
                                <option value="" selected disabled>Escolha...</option>

                                {{-- A MÁGICA DOS ITENS DINÂMICOS --}}
                                @foreach($itensDisponiveis as $item)
                                    @php
                                        $empenhada = $item->itensEmpenho->sum('quantidade_empenhada');
                                        $consumida = $item->itensEmpenho->flatMap->itensPedido->sum('quantidade');
                                        $saldo = $empenhada - $consumida;
                                        $sigla = $item->unidade->sigla ?? 'un';
                                    @endphp
                                    <option value="{{ $item->id }}">
                                        {{ $item->nome }} (Saldo: {{ number_format($saldo, 2, ',', '.') }} {{ $sigla }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Confirmar Alimentos</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL ADICIONAR EXCEÇÃO --}}
        <div class="modal fade" id="modalAdicionarExcecao" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form class="modal-content" id="formAdicionarExcecao" method="POST" action="{{ route('cardapio.excecao.salvar', $cardapio->id) }}">
                    @csrf
                    <div class="modal-header bg-warning text-dark">
                        <h1 class="modal-title h5">Adicionar Dia Especial ou Evento Pontual</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
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
                                    <option value="supressao">Supressão (Dia Não Letivo / Cancelado)</option>
                                </select>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label small text-uppercase fw-bold text-body-secondary">Selecione o Horário</label>
                                <select class="form-select border-primary form-select-lg" name="horario_id" required>
                                    @if($cardapio->horarios->count() > 0)
                                        <option value="" selected disabled>Escolha o horário...</option>
                                        @foreach($cardapio->horarios->sortBy('hora_inicio') as $horario)
                                            <option value="{{ $horario->id }}">
                                                {{ $horario->nome }} ({{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }})
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="" selected disabled>Cadastre horários no Passo 2 primeiro.</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Salvar Exceção</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
@section('custom_js')
    <script>
        const modalAlimento = document.getElementById('modalAdicionarAlimento');
        if (modalAlimento) {
            const formAlimento = document.getElementById('formAdicionarAlimento');
            const labelDiaModal = document.getElementById('labelDiaModal');
            const labelHorarioModal = document.getElementById('labelHorarioModal');
            const hiddenContexto = document.getElementById('hiddenContexto');
            const hiddenHorarioId = document.getElementById('hiddenHorarioId');
            const hiddenDiaSemana = document.getElementById('hiddenDiaSemana');

            // Pega o ID do cardápio atual direto do HTML para o JS usar
            const cardapioId = "{{ isset($cardapio) ? $cardapio->id : '' }}";

            modalAlimento.addEventListener('show.bs.modal', function (event) {
                const btn = event.relatedTarget;
                const contexto = btn.getAttribute('data-contexto');

                hiddenContexto.value = contexto;

                if(contexto === 'padrao') {
                    // Configura para salvar na GRID SEMANAL
                    formAlimento.action = `/cardapio/${cardapioId}/item-padrao`;

                    hiddenHorarioId.value = btn.getAttribute('data-horario-id');
                    hiddenDiaSemana.value = btn.getAttribute('data-dia-num');

                    labelDiaModal.innerText = 'Adicionando item para: ' + btn.getAttribute('data-dia-nome');
                    labelHorarioModal.innerText = btn.getAttribute('data-horario');

                } else if(contexto === 'excecao') {
                    // Configura para salvar na EXCEÇÃO
                    const excecaoId = btn.getAttribute('data-excecao-id');
                    formAlimento.action = `/cardapio/excecao/${excecaoId}/item`;

                    // Limpa os campos ocultos que não usamos aqui
                    hiddenHorarioId.value = '';
                    hiddenDiaSemana.value = '';

                    labelDiaModal.innerText = 'Dia Especial: ' + btn.getAttribute('data-data-nome');
                    labelHorarioModal.innerText = 'Alimentos do Evento';
                }
            });
        }
    </script>
@endsection
