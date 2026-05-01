@extends('dashboard.layout')

@section('custom_css')
    <style>
        .money-card { transition: transform 0.2s; }
        .money-card:hover { transform: translateY(-5px); }
        .progress-consumption { height: 8px; border-radius: 5px; }
        .table-vencimento td, .table-vencimento th,
        .table-empenhos td, .table-empenhos th { vertical-align: middle; }
        .item-row:hover { background-color: rgba(25, 135, 84, 0.05) !important; }
    </style>
@endsection

@section('content')
    @php
        // Encontra o contato principal
        $responsavelPrincipal = $contrato->fornecedor->responsaveis->where('is_principal', true)->first();
        $emailPrincipal = $responsavelPrincipal ? $responsavelPrincipal->contatos->where('tipo', 'Email')->first() : null;

        // Matemática Financeira
        $totalContratado = $contrato->valor_global;
        $totalEmpenhado = $contrato->empenhos->sum('valor_total') ?? 0;
        $saldoContrato = $totalContratado - $totalEmpenhado;

        // Controle de cor da badge de Status
        $corStatus = 'success';
        if($contrato->status === 'Encerrado') $corStatus = 'danger';
        if($contrato->status === 'Pausado') $corStatus = 'warning text-dark';
    @endphp

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Gestão de Contrato Individualizado</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('contratos') }}" class="btn btn-sm btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                    Voltar à Lista
                </a>
            </div>
            <span class="badge bg-{{ $corStatus }} d-flex align-items-center p-2 text-uppercase">Contrato {{ $contrato->status }}</span>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light text-uppercase fw-bold small text-body-secondary">
                    Dados do Processo Principal
                </div>

                <div class="card-body">
                    <h5 class="card-title text-primary">Fornecedor: {{ $contrato->fornecedor->nome }}</h5>
                    <p class="card-text mb-1"><strong>Nº Pregão/Chamada:</strong> {{ $contrato->pregao }}</p>
                    <p class="card-text mb-1"><strong>Processo SIPAC:</strong> {{ $contrato->processo }}</p>
                    <p class="card-text mb-2"><strong>Vigência:</strong> {{ \Carbon\Carbon::parse($contrato->inicio_vigencia)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($contrato->fim_vigencia)->format('d/m/Y') }}</p>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                        <div>
                            <span class="d-block small text-muted">Contato Principal:</span>
                            <strong>{{ $emailPrincipal ? $emailPrincipal->valor : 'Não cadastrado' }}</strong>
                        </div>
                        <button class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalDetalhesContrato" title="Ver contatos adicionais">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                                <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                            </svg>
                            Outros
                        </button>
                    </div>
                </div>

                <div class="card-footer bg-white border-top-0">
                    <a href="{{ route('contrato.editar', $contrato->id) }}" class="btn btn-sm btn-outline-secondary w-100">Editar Dados do Contrato</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card money-card shadow-sm border-primary bg-primary bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <h6 class="text-uppercase text-muted small">Total Contratado</h6>
                            <h3 class="card-title text-primary">R$ {{ number_format($totalContratado, 2, ',', '.') }}</h3>
                            <p class="text-muted small mb-0">Valor global do processo</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card money-card shadow-sm border-info bg-info bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <h6 class="text-uppercase text-muted small">Total Empenhado</h6>
                            <h3 class="card-title text-info">R$ {{ number_format($totalEmpenhado, 2, ',', '.') }}</h3>
                            <p class="text-muted small mb-0">Soma de todas as notas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card money-card shadow-sm border-{{ $saldoContrato <= 0 ? 'danger' : 'success' }} bg-{{ $saldoContrato <= 0 ? 'danger' : 'success' }} bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <h6 class="text-uppercase text-muted small">Saldo do Contrato</h6>
                            <h3 class="card-title text-{{ $saldoContrato <= 0 ? 'danger' : 'success' }}">R$ {{ number_format($saldoContrato, 2, ',', '.') }}</h3>
                            <p class="text-muted small mb-0">Disponível em R$</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetalhesContrato" tabindex="-1" aria-labelledby="modalDetalhesContratoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title h5 text-uppercase fw-bold" id="modalDetalhesContratoLabel">Todos os Contatos</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card bg-body-tertiary border-0 shadow-none mb-3">
                        <div class="card-body">
                            <ul class="list-group list-group-flush" id="listaResponsaveis">
                                @forelse($contrato->fornecedor->responsaveis as $responsavel)
                                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center p-2">
                                        <div>
                                            <strong>{{ $responsavel->nome }}</strong> {!! $responsavel->is_principal ? '<span class="badge bg-primary ms-1">Principal</span>' : '' !!} <br>
                                            @foreach($responsavel->contatos as $contato)
                                                <strong class="text-primary">•</strong> {{ $contato->tipo }}: {{ $contato->valor }} <br>
                                            @endforeach
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item bg-transparent text-muted text-center p-3">Nenhum contato cadastrado.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <h5 class="card-title mb-0 text-uppercase fw-bold text-body-secondary small">Saldos Quantitativos por Item</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-vencimento mb-0">
                            <thead class="table-light text-uppercase small">
                            <tr>
                                <th>Item</th>
                                <th>Unidade</th>
                                <th>Qtd. Empenhada</th>
                                <th>Qtd. Consumida</th>
                                <th>Saldo Disponível</th>
                                <th width="20%">Progresso de Consumo</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($contrato->itens as $item)
                                @php
                                    $empenhada = $item->itensEmpenho->sum('quantidade_empenhada');

                                    $consumida = $item->itensEmpenho->flatMap->itensPedido->sum('quantidade');

                                    $saldoItem = $empenhada - $consumida;
                                    $porcentagem = $empenhada > 0 ? ($consumida / $empenhada) * 100 : 0;
                                    $corBarra = $porcentagem > 85 ? 'danger' : ($porcentagem > 60 ? 'warning' : 'success');

                                    $sigla = $item->unidade->sigla ?? '-';
                                @endphp
                                <tr class="item-row">
                                    <td><strong>{{ $item->nome }}</strong></td>
                                    <td>{{ $sigla }}</td>
                                    <td>{{ number_format($empenhada, 2, ',', '.') }}</td>
                                    <td>{{ number_format($consumida, 2, ',', '.') }}</td>
                                    <td class="fw-bold text-{{ $saldoItem <= 0 ? 'danger' : 'success' }} fs-5">
                                        {{ number_format($saldoItem, 2, ',', '.') }}
                                    </td>
                                    <td>
                                        <div class="progress progress-consumption" role="progressbar" aria-valuenow="{{ $porcentagem }}" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar bg-{{ $corBarra }}" style="width: {{ $porcentagem }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ number_format($porcentagem, 1, ',', '.') }}% do empenhado</small>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted p-4">Nenhum item cadastrado para este contrato.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-uppercase fw-bold text-body-secondary small">Últimos Pedidos</h5>
                    <button class="btn btn-sm btn-primary d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalLancarConsumo" title="Solicitar nova entrega">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-cart-plus" viewBox="0 0 16 16"><path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z"/><path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/></svg>
                        Novo
                    </button>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @php
                            $pedidos = $contrato->empenhos->flatMap->pedidos->sortByDesc('data_pedido')->take(5);
                        @endphp

                        @forelse($pedidos as $pedido)
                            @php
                                $bgClass = '';
                                $badgeClass = 'bg-warning text-dark';
                                $btnClass = 'btn-outline-success';

                                if($pedido->status === 'Atrasado') {
                                    $bgClass = 'bg-danger bg-opacity-10 border-danger border-opacity-25';
                                    $badgeClass = 'bg-danger';
                                    $btnClass = 'btn-outline-danger';
                                } elseif($pedido->status === 'Recebido' || $pedido->status === 'Concluído') {
                                    $bgClass = 'bg-light opacity-75';
                                    $badgeClass = 'bg-secondary';
                                }

                                $primeiroItem = $pedido->itensPedido->first();
                                $nomeAlimento = $primeiroItem ? $primeiroItem->itemEmpenho->itemContrato->nome : 'Item não identificado';
                                $quantidade = $primeiroItem ? $primeiroItem->quantidade : 0;
                                $codigoPedido = strtoupper(substr($pedido->id, 0, 6));
                            @endphp

                            <li class="list-group-item d-flex justify-content-between align-items-start p-3 {{ $bgClass }}">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold {{ $pedido->status === 'Atrasado' ? 'text-danger' : ($pedido->status === 'Recebido' ? 'text-muted text-decoration-line-through' : 'text-primary') }} mb-1">
                                        Pedido #{{ $codigoPedido }}
                                    </div>
                                    <span class="d-block small text-dark mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-box me-1" viewBox="0 0 16 16"><path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z"/></svg>
                                        {{ $nomeAlimento }} ({{ number_format($quantidade, 0, ',', '.') }})
                                    </span>
                                    <span class="d-block text-muted small" style="font-size: 0.75rem;">Solicitado: {{ \Carbon\Carbon::parse($pedido->data_pedido)->format('d/m/Y') }}</span>

                                    @if($pedido->status === 'Atrasado')
                                        <span class="d-block text-danger fw-bold small" style="font-size: 0.75rem;">Atrasado desde: {{ \Carbon\Carbon::parse($pedido->data_prevista_entrega)->format('d/m/Y') }}</span>
                                    @elseif($pedido->status === 'Recebido')
                                        <span class="d-block text-muted small" style="font-size: 0.75rem;">Recebido em: {{ \Carbon\Carbon::parse($pedido->updated_at)->format('d/m/Y') }}</span>
                                    @else
                                        <span class="d-block text-muted small" style="font-size: 0.75rem;">Previsto: {{ \Carbon\Carbon::parse($pedido->data_prevista_entrega)->format('d/m/Y') }}</span>
                                    @endif
                                </div>
                                <div class="text-end d-flex flex-column align-items-end gap-2">
                                    <span class="badge {{ $badgeClass }} text-uppercase shadow-sm">{{ $pedido->status }}</span>
                                    @if($pedido->status !== 'Recebido' && $pedido->status !== 'Concluído')
                                        <button class="btn btn-sm {{ $btnClass }} py-0 px-2" style="font-size: 0.75rem;" title="Confirmar recebimento">
                                            Receber
                                        </button>
                                    @else
                                        <a href="#" class="text-decoration-none small text-primary" style="font-size: 0.75rem;">Ver NF</a>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted p-5">Nenhum pedido registrado.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer bg-white border-top-0">
                    <button class="btn btn-sm btn-outline-secondary w-100">Ver Histórico Completo</button>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0 pt-3">
                    <h5 class="card-title mb-0 text-uppercase fw-bold text-body-secondary small">Notas de Empenho (NE) Individualizadas</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalCadastrarEmpenho">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-plus" viewBox="0 0 16 16">
                            <path d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5"/>
                            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 1-1 1v12a1 1 0 0 1 1 1h8a1 1 0 0 1 1-1V4.5z"/>
                        </svg>
                        Inserir Novo Empenho
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-empenhos mb-0">
                            <thead class="table-light text-uppercase small">
                            <tr>
                                <th>NE / Data</th>
                                <th>Nº Item</th>
                                <th>Item Empenhado</th>
                                <th>Qtd Empenhada</th>
                                <th>Valor Total</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($contrato->empenhos as $index => $empenho)
                                @php
                                    $valorTotal = $empenho->valor_total ?? 0;
                                    $valorUtilizado = $empenho->valor_utilizado ?? 0;
                                    $usoPercentual = $valorTotal > 0 ? ($valorUtilizado / $valorTotal) * 100 : 0;

                                    if ($usoPercentual >= 100) {
                                        $statusBadge = '<span class="badge bg-danger">Finalizado</span>';
                                    } elseif ($usoPercentual >= 80) {
                                        $statusBadge = '<span class="badge bg-warning text-dark">Esgotando</span>';
                                    } else {
                                        $statusBadge = '<span class="badge bg-success">Ativo</span>';
                                    }

                                    $itemEmpenho = $empenho->itensEmpenho->first() ?? null;
                                    $nomeItem = $itemEmpenho ? $itemEmpenho->itemContrato->nome : '-';
                                    $qtdEmpenhada = $itemEmpenho ? $itemEmpenho->quantidade_empenhada : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $empenho->numero_empenho ?? 'Sem Número' }}</strong><br>
                                        <span class="text-muted small">{{ isset($empenho->created_at) ? \Carbon\Carbon::parse($empenho->created_at)->format('d/m/Y') : '-' }}</span>
                                    </td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $nomeItem }}</td>
                                    <td>{{ number_format($qtdEmpenhada, 2, ',', '.') }}</td>
                                    <td class="fw-bold">R$ {{ number_format($valorTotal, 2, ',', '.') }}</td>
                                    <td>{!! $statusBadge !!}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary" title="Ver Detalhes">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted p-4">Nenhum empenho registrado.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Lançar Consumo (Etapa 3 e 4) --}}
    <div class="modal fade" id="modalLancarConsumo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="formLancarConsumo" method="POST" action="#">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title h5">Lançar Consumo Diário Individualizado</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small">
                        Selecione o item e a quantidade. O sistema abaterá automaticamente dos empenhos ativos mais antigos deste item (Etapa 3).
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Item Consumido</label>
                        <select class="form-select form-select-lg border-primary" name="item_contrato_id" id="selectItemConsumo" required>
                            <option value="" selected disabled>Escolha o item...</option>

                            @foreach($contrato->itens as $item)
                                @php
                                    $empenhada = $item->itensEmpenho->sum('quantidade_empenhada');
                                    $consumida = $item->itensEmpenho->flatMap->itensPedido->sum('quantidade');
                                    $saldoDisponivel = $empenhada - $consumida;

                                    $siglaUnidade = $item->unidade->sigla ?? 'un';
                                @endphp
                                @if($saldoDisponivel > 0)
                                    <option value="{{ $item->id }}" data-saldo="{{ $saldoDisponivel }}" data-unidade="{{ $siglaUnidade }}">
                                        {{ $item->nome }} (Saldo: {{ number_format($saldoDisponivel, 2, ',', '.') }} {{ $siglaUnidade }})
                                    </option>
                                @endif
                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-success">Saldo Atual Disponível</label>
                        <div class="fs-4 fw-bold text-success" id="saldoVisual">Selecione um item...</div>
                        <input type="hidden" id="valorSaldoNumerico" value="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Data do Consumo</label>
                        <input type="date" class="form-control" name="data_consumo" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantidade Consumida (<span id="labelUnidade">...</span>)</label>
                        <input type="number" step="0.01" class="form-control form-control-lg" name="quantidade_consumida" id="qtdConsumida" required placeholder="0,00" disabled>

                        <div class="invalid-feedback" id="feedbackErroSaldo">
                            <strong>Bloqueio de Segurança:</strong> A quantidade informada supera o saldo disponível para este item. O lançamento não é permitido.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnEnviarConsumo" disabled>Confirmar Lançamento</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        const selectItem = document.getElementById('selectItemConsumo');
        const saldoVisual = document.getElementById('saldoVisual');
        const valorSaldoNumerico = document.getElementById('valorSaldoNumerico');
        const labelUnidade = document.getElementById('labelUnidade');
        const qtdInput = document.getElementById('qtdConsumida');
        const btnEnviar = document.getElementById('btnEnviarConsumo');
        const feedbackErro = document.getElementById('feedbackErroSaldo');

        selectItem.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const saldo = parseFloat(selectedOption.getAttribute('data-saldo'));
            const unidade = selectedOption.getAttribute('data-unidade');

            valorSaldoNumerico.value = saldo;
            saldoVisual.innerText = saldo.toLocaleString('pt-BR', {minimumFractionDigits: 2}) + ' ' + unidade;
            labelUnidade.innerText = unidade;

            qtdInput.disabled = false;
            qtdInput.value = '';
            qtdInput.classList.remove('is-invalid');
            btnEnviar.disabled = true;
            feedbackErro.style.display = 'none';
        });

        qtdInput.addEventListener('input', function() {
            const qtdDigitada = parseFloat(this.value) || 0;
            const saldoDisponivel = parseFloat(valorSaldoNumerico.value);

            if (qtdDigitada > saldoDisponivel || qtdDigitada <= 0) {
                this.classList.add('is-invalid');
                btnEnviar.disabled = true;
                if(qtdDigitada > saldoDisponivel) {
                    feedbackErro.style.display = 'block';
                }
            } else {
                this.classList.remove('is-invalid');
                btnEnviar.disabled = false;
                feedbackErro.style.display = 'none';
            }
        });
    </script>
@endsection
