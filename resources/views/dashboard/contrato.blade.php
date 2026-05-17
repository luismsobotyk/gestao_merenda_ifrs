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
                    <button class="btn btn-sm btn-primary d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalLancarPedido" title="Solicitar nova entrega">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-cart-plus" viewBox="0 0 16 16"><path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z"/><path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/></svg>
                        Novo
                    </button>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @php
                            $pedidos = $contrato->pedidos->sortByDesc('data_pedido')->take(5);
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

                                $codigoPedido = strtoupper(substr($pedido->id, 0, 6));

                                // =========================================================
                                // NOVA LÓGICA: Lista de itens separados por vírgula
                                // =========================================================
                                $itensFormatados = [];
                                foreach($pedido->itensPedido as $itemPedido) {
                                    $nome = $itemPedido->itemEmpenho->itemContrato->nome ?? 'Item Excluído';

                                    // Pega a quantidade (com 2 casas decimais) e tira o ",00" se for número inteiro
                                    $qtd = number_format($itemPedido->quantidade, 2, ',', '.');
                                    $qtd = preg_replace('/,00$/', '', $qtd);

                                    $sigla = $itemPedido->itemEmpenho->itemContrato->unidade->sigla ?? '';

                                    // Adiciona o item na lista no formato "Nome (10 kg)"
                                    $itensFormatados[] = "{$nome} ({$qtd} {$sigla})";
                                }

                                // Junta toda a lista com vírgula e espaço
                                $textoItensPedido = implode(', ', $itensFormatados);

                                if (empty($textoItensPedido)) {
                                    $textoItensPedido = 'Nenhum item registrado';
                                }
                            @endphp

                            <li class="list-group-item d-flex justify-content-between align-items-start p-3 {{ $bgClass }}">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold {{ $pedido->status === 'Atrasado' ? 'text-danger' : ($pedido->status === 'Recebido' ? 'text-muted text-decoration-line-through' : 'text-primary') }} mb-1">
                                        Pedido #{{ $codigoPedido }}
                                    </div>
                                    <span class="d-block small text-dark mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-box me-1" viewBox="0 0 16 16"><path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z"/></svg>
                                        {{ $textoItensPedido }}
                                    </span>
                                    <span class="d-block text-muted small" style="font-size: 0.75rem;">Solicitado: {{ \Carbon\Carbon::parse($pedido->data_pedido)->format('d/m/Y') }}</span>

                                    @if($pedido->status === 'Atrasado')
                                        <span class="d-block text-danger fw-bold small" style="font-size: 0.75rem;">Atrasado desde: {{ \Carbon\Carbon::parse($pedido->data_prevista_entrega)->format('d/m/Y') }}</span>
                                    @elseif($pedido->status === 'Recebido')
                                        <span class="d-block text-muted small" style="font-size: 0.75rem;">Recebido em: {{ \Carbon\Carbon::parse($pedido->data_prevista_entrega)->format('d/m/Y') }}</span>
                                    @else
                                        <span class="d-block text-muted small" style="font-size: 0.75rem;">Previsto: {{ \Carbon\Carbon::parse($pedido->data_prevista_entrega)->format('d/m/Y') }}</span>
                                    @endif
                                </div>
                                <div class="text-end d-flex flex-column align-items-end gap-2">
                                    <span class="badge {{ $badgeClass }} text-uppercase shadow-sm">{{ $pedido->status }}</span>
                                    @if($pedido->status !== 'Recebido' && $pedido->status !== 'Concluído')
                                        {{-- Transformamos o botão em um mini-form para enviar o POST seguro --}}
                                        <form action="{{ route('pedido.receber', $pedido->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja confirmar o recebimento deste pedido?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $btnClass }} py-0 px-2" style="font-size: 0.75rem;" title="Confirmar recebimento">
                                                Receber
                                            </button>
                                        </form>
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
                    <button class="btn btn-sm btn-outline-secondary w-100" data-bs-toggle="offcanvas" data-bs-target="#offcanvasHistoricoPedidos" aria-controls="offcanvasHistoricoPedidos">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-clock-history me-1" viewBox="0 0 16 16"><path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z"/><path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z"/><path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5"/></svg>
                        Ver Histórico Completo
                    </button>
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
                                        <button class="btn btn-sm btn-outline-secondary" title="Ver Detalhes do Empenho" data-bs-toggle="modal" data-bs-target="#modalDetalhesEmpenho-{{ $empenho->id }}">
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

    {{-- ========================================================= --}}
    {{-- MODAIS DE EXTRATO DE EMPENHO (Gerados dinamicamente) --}}
    {{-- ========================================================= --}}
    @foreach($contrato->empenhos as $empenho)
        @php
            // Cálculos Financeiros
            $vlrTotal = $empenho->valor_total ?? 0;
            $vlrUtilizado = $empenho->valor_utilizado ?? 0;
            $saldoVlr = $vlrTotal - $vlrUtilizado;
            $progVlr = $vlrTotal > 0 ? ($vlrUtilizado / $vlrTotal) * 100 : 0;
            $corProgVlr = $progVlr > 85 ? 'danger' : ($progVlr > 60 ? 'warning' : 'success');

            // Cálculos Quantitativos (Assumindo 1 item por Empenho, conforme sua estrutura atual)
            $itemEmp = $empenho->itensEmpenho->first();
            $nomeAlimento = $itemEmp ? $itemEmp->itemContrato->nome : 'Item não identificado';
            $siglaUni = ($itemEmp && $itemEmp->itemContrato->unidade) ? $itemEmp->itemContrato->unidade->sigla : 'un';

            $qtdEmpenhada = $itemEmp ? $itemEmp->quantidade_empenhada : 0;
            $qtdConsumida = $itemEmp ? $itemEmp->itensPedido->sum('quantidade') : 0;
            $saldoQtd = $qtdEmpenhada - $qtdConsumida;
        @endphp

        <div class="modal fade" id="modalDetalhesEmpenho-{{ $empenho->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h1 class="modal-title h5 fw-bold text-secondary">
                            Extrato da NE: <span class="text-dark">{{ $empenho->numero_empenho ?? 'Sem Número' }}</span>
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Resumo em Cards --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 bg-body-tertiary h-100">
                                    <div class="card-body">
                                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Resumo Financeiro</h6>
                                        <div class="d-flex justify-content-between mb-1 small">
                                            <span>Valor Original:</span>
                                            <strong>R$ {{ number_format($vlrTotal, 2, ',', '.') }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1 small text-danger">
                                            <span>Valor Consumido:</span>
                                            <strong>- R$ {{ number_format($vlrUtilizado, 2, ',', '.') }}</strong>
                                        </div>
                                        <hr class="my-2">
                                        <div class="d-flex justify-content-between text-success">
                                            <span>Saldo Disponível:</span>
                                            <strong class="fs-5">R$ {{ number_format($saldoVlr, 2, ',', '.') }}</strong>
                                        </div>

                                        <div class="progress mt-3" style="height: 6px;">
                                            <div class="progress-bar bg-{{ $corProgVlr }}" style="width: {{ $progVlr }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 bg-body-tertiary h-100">
                                    <div class="card-body">
                                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Resumo Físico ({{ $nomeAlimento }})</h6>
                                        <div class="d-flex justify-content-between mb-1 small">
                                            <span>Qtd. Empenhada:</span>
                                            <strong>{{ number_format($qtdEmpenhada, 2, ',', '.') }} {{ $siglaUni }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1 small text-danger">
                                            <span>Qtd. Pedida:</span>
                                            <strong>- {{ number_format($qtdConsumida, 2, ',', '.') }} {{ $siglaUni }}</strong>
                                        </div>
                                        <hr class="my-2">
                                        <div class="d-flex justify-content-between text-success">
                                            <span>Saldo Físico:</span>
                                            <strong class="fs-5">{{ number_format($saldoQtd, 2, ',', '.') }} {{ $siglaUni }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tabela de Histórico de Pedidos que consumiram esta NE --}}
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Pedidos Vinculados a esta NE</h6>
                        @if($itemEmp && $itemEmp->itensPedido->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover small align-middle">
                                    <thead class="table-light text-secondary">
                                    <tr>
                                        <th>ID Pedido</th>
                                        <th>Data do Pedido</th>
                                        <th>Status</th>
                                        <th class="text-end">Qtd. Abatida</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($itemEmp->itensPedido->sortByDesc('created_at') as $itemPed)
                                        <tr>
                                            {{-- Usamos ?->id e um fallback caso seja null --}}
                                            <td class="fw-bold">#{{ strtoupper(substr($itemPed->pedido?->id ?? '000000', 0, 6)) }}</td>

                                            {{-- Verificamos se o pedido existe antes de formatar a data --}}
                                            <td>{{ $itemPed->pedido ? \Carbon\Carbon::parse($itemPed->pedido->data_pedido)->format('d/m/Y') : '-' }}</td>

                                            <td><span class="badge bg-secondary">{{ $itemPed->pedido?->status ?? 'Desconhecido' }}</span></td>

                                            <td class="text-end fw-bold text-danger">- {{ number_format($itemPed->quantidade, 2, ',', '.') }} {{ $siglaUni }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-light text-center text-muted border border-dashed py-3">
                                Nenhum pedido consumiu esta Nota de Empenho até o momento.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Modal Cadastrar Empenho (Etapa 2) --}}
    <div class="modal fade" id="modalCadastrarEmpenho" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            {{-- A action agora aponta para a rota real passando o ID do contrato --}}
            <form class="modal-content" method="POST" action="{{ route('empenho.salvar', $contrato->id) }}">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title h5">Inserir Nova Nota de Empenho (NE)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Selecione o Item do Contrato a Empenhar</label>
                            <select class="form-select form-select-lg border-primary" name="item_contrato_uuid" id="selectItemEmpenho" required>
                                <option value="" selected disabled>Escolha o item...</option>

                                {{-- Loop Dinâmico para calcular o Saldo a Empenhar --}}
                                @foreach($contrato->itens as $item)
                                    @php
                                        // Quanto o contrato permite comprar?
                                        $qtdContratada = $item->quantidade;
                                        // Quanto já foi empenhado (liberado pra comprar)?
                                        $qtdJaEmpenhada = $item->itensEmpenho->sum('quantidade_empenhada');
                                        // Quanto ainda falta empenhar?
                                        $saldoAEmpenhar = $qtdContratada - $qtdJaEmpenhada;

                                        $siglaUnidade = $item->unidade->sigla ?? 'un';
                                    @endphp

                                    {{-- Só mostra na lista se ainda tiver saldo sobrando --}}
                                    @if($saldoAEmpenhar > 0)
                                        <option value="{{ $item->id }}" data-saldo-empenhar="{{ $saldoAEmpenhar }}" data-valor-unitario="{{ $item->valor_unitario }}">
                                            {{ $item->nome }} (Saldo a Empenhar: {{ number_format($saldoAEmpenhar, 2, ',', '.') }} {{ $siglaUnidade }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Número da NE</label>
                            <input type="text" class="form-control" name="numero_empenho" required placeholder="Ex: 2026NE00123">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Data de Emissão (Opcional)</label>
                            <input type="date" class="form-control" name="data_emissao">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Quantidade a Empenhar</label>
                            <input type="number" step="0.01" class="form-control" name="quantidade_empenhada" id="qtdEmpenharInput" required placeholder="0,00" disabled>
                            <div class="invalid-feedback text-danger" id="feedbackErroEmpenho" style="display: none; font-size: 0.8rem;">
                                Quantidade excede o limite contratado.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valor Total Gerado (R$)</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control bg-light" name="valor_total" id="valorTotalEmpenhoVisual" readonly placeholder="0,00">
                                {{-- O input hidden é o que realmente vai para o backend salvar no banco --}}
                                <input type="hidden" name="valor_total_real" id="valorTotalEmpenhoReal">
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">Calculado automaticamente (Qtd × Valor Unitário do Item).</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnSalvarEmpenho" disabled>Salvar Empenho</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Lançar Pedido com Múltiplos Itens --}}
    <div class="modal fade" id="modalLancarPedido" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg"> {{-- Deixei o modal mais largo (modal-lg) --}}
            <form class="modal-content" id="formLancarPedido" method="POST" action="{{ route('pedido.salvar', $contrato->id) }}">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title h5">Lançar Novo Pedido Múltiplo</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small mb-4">
                        Adicione os itens desejados. O sistema abaterá automaticamente dos empenhos ativos mais antigos de cada alimento.
                    </div>

                    {{-- Data e Hora no topo --}}
                    <div class="row mb-4 bg-light p-3 rounded">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Data do Pedido</label>
                            <input type="date" class="form-control" name="data_pedido" id="dataPedidoInput" required value="{{ date('Y-m-d') }}">

                            {{-- Alertas e Pergunta Dinâmica --}}
                            <div class="alert alert-warning d-none mt-2 mb-0 py-2 small" id="alertaRetroativo">
                                <strong>Aviso:</strong> Lançamento retroativo.
                            </div>

                            <div class="form-check form-switch d-none mt-2" id="divJaRecebido">
                                <input class="form-check-input" type="checkbox" role="switch" id="switchJaRecebido" name="ja_recebido" value="1">
                                <label class="form-check-label small fw-bold text-success" for="switchJaRecebido">
                                    Este pedido já foi recebido/entregue?
                                </label>
                            </div>
                        </div>

                        {{-- AQUI ESTÁ O CAMPO QUE TINHA SUMIDO! --}}
                        <div class="col-md-6 d-none" id="divHoraPedido">
                            <label class="form-label fw-bold">Horário (HH:MM)</label>
                            <input type="time" class="form-control" name="hora_pedido" id="horaPedidoInput">
                            <small class="text-muted" style="font-size: 0.75rem;">Obrigatório para datas diferentes de hoje.</small>
                        </div>
                    </div>

                    {{-- Tabela Dinâmica de Itens --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0">Itens da Solicitação</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-item-pedido">+ Adicionar Linha</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle" id="tabela-itens-pedido">
                            <thead class="table-light small text-center text-uppercase">
                            <tr>
                                <th width="45%">Alimento</th>
                                <th width="25%">Qtd. Desejada</th>
                                <th width="25%">Saldo Disponível</th>
                                <th width="5%"></th>
                            </tr>
                            </thead>
                            <tbody id="tbody-itens-pedido">
                            {{-- Linhas geradas via JavaScript --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnEnviarPedido">Confirmar Pedido</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Bonitão de Confirmação de Pedido Futuro --}}
    <div class="modal fade" id="modalConfirmacaoFutura" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-warning">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16"><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/></svg>
                        Atenção: Agendamento Futuro
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fs-6">
                    Você está planejando um pedido para uma data no futuro: <br>
                    <strong id="spanDataFutura" class="text-danger fs-4 d-block mt-2 text-center"></strong><br>
                    Tem certeza de que deseja registrar este agendamento agora?
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-warning fw-bold" id="btnConfirmarPedidoFuturo">Sim, Agendar Pedido</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Offcanvas de Histórico Completo de Pedidos --}}
    <div class="offcanvas offcanvas-end shadow" tabindex="-1" id="offcanvasHistoricoPedidos" aria-labelledby="offcanvasHistoricoPedidosLabel">
        <div class="offcanvas-header bg-light border-bottom">
            <h5 class="offcanvas-title fw-bold text-uppercase small" id="offcanvasHistoricoPedidosLabel">Todos os Pedidos do Contrato</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <ul class="list-group list-group-flush">
                {{-- Aqui nós NÃO usamos o take(5), pegamos TODOS ordenados por data --}}
                @forelse($contrato->pedidos->sortByDesc('data_pedido') as $pedido)
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

                        $codigoPedido = strtoupper(substr($pedido->id, 0, 6));

                        $itensFormatados = [];
                        foreach($pedido->itensPedido as $itemPedido) {
                            $nome = $itemPedido->itemEmpenho->itemContrato->nome ?? 'Item Excluído';
                            $qtd = number_format($itemPedido->quantidade, 2, ',', '.');
                            $qtd = preg_replace('/,00$/', '', $qtd);
                            $sigla = $itemPedido->itemEmpenho->itemContrato->unidade->sigla ?? '';
                            $itensFormatados[] = "{$nome} ({$qtd} {$sigla})";
                        }
                        $textoItensPedido = implode(', ', $itensFormatados) ?: 'Nenhum item registrado';
                    @endphp

                    <li class="list-group-item p-3 {{ $bgClass }}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="fw-bold {{ $pedido->status === 'Atrasado' ? 'text-danger' : ($pedido->status === 'Recebido' ? 'text-muted' : 'text-primary') }}">
                                Pedido #{{ $codigoPedido }}
                            </div>
                            <span class="badge {{ $badgeClass }} text-uppercase shadow-sm">{{ $pedido->status }}</span>
                        </div>

                        <span class="d-block small text-dark mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-box me-1" viewBox="0 0 16 16"><path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z"/></svg>
                            {{ $textoItensPedido }}
                        </span>

                        <div class="d-flex justify-content-between align-items-end mt-2 pt-2 border-top">
                            <div>
                                <span class="d-block text-muted small" style="font-size: 0.70rem;">Solicitado: {{ \Carbon\Carbon::parse($pedido->data_pedido)->format('d/m/Y') }}</span>
                                @if($pedido->status === 'Atrasado')
                                    <span class="d-block text-danger fw-bold small" style="font-size: 0.70rem;">Atrasado desde: {{ \Carbon\Carbon::parse($pedido->data_prevista_entrega)->format('d/m/Y') }}</span>
                                @elseif($pedido->status === 'Recebido')
                                    <span class="d-block text-muted small" style="font-size: 0.70rem;">Recebido em: {{ \Carbon\Carbon::parse($pedido->data_prevista_entrega)->format('d/m/Y') }}</span>
                                @else
                                    <span class="d-block text-muted small" style="font-size: 0.70rem;">Previsto: {{ \Carbon\Carbon::parse($pedido->data_prevista_entrega)->format('d/m/Y') }}</span>
                                @endif
                            </div>

                            @if($pedido->status !== 'Recebido' && $pedido->status !== 'Concluído')
                                <form action="{{ route('pedido.receber', $pedido->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja confirmar o recebimento deste pedido?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $btnClass }} py-0 px-2" style="font-size: 0.70rem;" title="Confirmar recebimento">Receber</button>
                                </form>
                            @else
                                <a href="#" class="text-decoration-none small text-primary" style="font-size: 0.70rem;">Ver NF</a>
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted p-5">Nenhum pedido registrado no histórico.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        // ==========================================
        // LÓGICA DO MODAL DE CADASTRAR EMPENHO
        // ==========================================
        const selectItemEmpenho = document.getElementById('selectItemEmpenho');
        const qtdEmpenharInput = document.getElementById('qtdEmpenharInput');
        const valorTotalEmpenhoVisual = document.getElementById('valorTotalEmpenhoVisual');
        const valorTotalEmpenhoReal = document.getElementById('valorTotalEmpenhoReal');
        const btnSalvarEmpenho = document.getElementById('btnSalvarEmpenho');
        const feedbackErroEmpenho = document.getElementById('feedbackErroEmpenho');

        let limiteEmpenho = 0;
        let valorUnitarioItem = 0;

        if (selectItemEmpenho) {
            selectItemEmpenho.addEventListener('change', function() {
                const option = this.options[this.selectedIndex];
                limiteEmpenho = parseFloat(option.getAttribute('data-saldo-empenhar'));
                valorUnitarioItem = parseFloat(option.getAttribute('data-valor-unitario'));

                qtdEmpenharInput.disabled = false;
                qtdEmpenharInput.value = '';
                valorTotalEmpenhoVisual.value = '';
                valorTotalEmpenhoReal.value = '';

                qtdEmpenharInput.classList.remove('is-invalid');
                btnSalvarEmpenho.disabled = true;
                feedbackErroEmpenho.style.display = 'none';
            });

            qtdEmpenharInput.addEventListener('input', function() {
                const qtdDigitada = parseFloat(this.value) || 0;

                if (qtdDigitada > limiteEmpenho || qtdDigitada <= 0) {
                    this.classList.add('is-invalid');
                    btnSalvarEmpenho.disabled = true;
                    valorTotalEmpenhoVisual.value = 'Erro';
                    valorTotalEmpenhoReal.value = '';

                    if(qtdDigitada > limiteEmpenho) {
                        feedbackErroEmpenho.style.display = 'block';
                    }
                } else {
                    this.classList.remove('is-invalid');
                    btnSalvarEmpenho.disabled = false;
                    feedbackErroEmpenho.style.display = 'none';

                    const totalReais = qtdDigitada * valorUnitarioItem;
                    valorTotalEmpenhoReal.value = totalReais.toFixed(2);
                    valorTotalEmpenhoVisual.value = totalReais.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
            });
        }

        // ==========================================
        // LÓGICA DO MODAL DE LANÇAR PEDIDO (MÚLTIPLOS ITENS)
        // ==========================================
        const dataPedidoInput = document.getElementById('dataPedidoInput');
        const divHoraPedido = document.getElementById('divHoraPedido');
        const horaPedidoInput = document.getElementById('horaPedidoInput');
        const alertaRetroativo = document.getElementById('alertaRetroativo');
        const formLancarPedido = document.getElementById('formLancarPedido');
        const btnEnviarPedido = document.getElementById('btnEnviarPedido');
        const hojeString = "{{ date('Y-m-d') }}";
        const divJaRecebido = document.getElementById('divJaRecebido');
        const switchJaRecebido = document.getElementById('switchJaRecebido');

        // 1. Controle da Data e Hora (Avisos e Switch)
        if (dataPedidoInput) {
            dataPedidoInput.addEventListener('change', function() {
                const dataEscolhida = this.value;

                if (dataEscolhida !== hojeString) {
                    divHoraPedido.classList.remove('d-none');
                    horaPedidoInput.setAttribute('required', 'required');
                } else {
                    divHoraPedido.classList.add('d-none');
                    horaPedidoInput.removeAttribute('required');
                    horaPedidoInput.value = '';
                }

                if (dataEscolhida < hojeString) {
                    alertaRetroativo.classList.remove('d-none');
                    divJaRecebido.classList.remove('d-none');
                } else {
                    alertaRetroativo.classList.add('d-none');
                    divJaRecebido.classList.add('d-none');
                    if (switchJaRecebido) switchJaRecebido.checked = false;
                }
            });
        }

        // ========================================================
        // 2. INTERCEPTADOR DE ENVIO (Modal Amarelo para Data Futura)
        // ========================================================
        let agendamentoConfirmado = false;
        const modalPedidoEl = document.getElementById('modalLancarPedido');
        const modalConfirmacaoEl = document.getElementById('modalConfirmacaoFutura');

        if (formLancarPedido) {
            formLancarPedido.addEventListener('submit', function(e) {
                const dataEscolhida = dataPedidoInput.value;

                // Se a data for no futuro E o usuário ainda não confirmou no modal amarelo
                if (dataEscolhida > hojeString && !agendamentoConfirmado) {
                    e.preventDefault();

                    const partes = dataEscolhida.split('-');
                    const dataFormatada = `${partes[2]}/${partes[1]}/${partes[0]}`;
                    document.getElementById('spanDataFutura').innerText = dataFormatada;

                    const modalPedidoInstancia = bootstrap.Modal.getInstance(modalPedidoEl);
                    const modalConfirmacaoInstancia = bootstrap.Modal.getOrCreateInstance(modalConfirmacaoEl);

                    btnEnviarPedido.disabled = false;
                    modalPedidoInstancia.hide();

                    modalPedidoEl.addEventListener('hidden.bs.modal', function showYellowModal() {
                        modalConfirmacaoInstancia.show();
                        modalPedidoEl.removeEventListener('hidden.bs.modal', showYellowModal);
                    });
                }
            });
        }

        if (modalConfirmacaoEl) {
            modalConfirmacaoEl.addEventListener('hidden.bs.modal', function() {
                if (!agendamentoConfirmado) {
                    const modalPedidoInstancia = bootstrap.Modal.getOrCreateInstance(modalPedidoEl);
                    modalPedidoInstancia.show();
                }
            });
        }

        const btnConfirmarPedidoFuturo = document.getElementById('btnConfirmarPedidoFuturo');
        if (btnConfirmarPedidoFuturo) {
            btnConfirmarPedidoFuturo.addEventListener('click', function() {
                agendamentoConfirmado = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Agendando...';
                this.disabled = true;
                formLancarPedido.submit();
            });
        }

        // ========================================================
        // 3. TABELA DINÂMICA DE MÚLTIPLOS ITENS
        // ========================================================
        const optionsItensPedidoHtml = `
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
                        {{ $item->nome }}
        </option>
@endif
        @endforeach
        `;

        let itemPedidoIndex = 0;

        function adicionarLinhaPedido() {
            const tbody = document.getElementById('tbody-itens-pedido');
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>
                    <select name="itens[${itemPedidoIndex}][item_contrato_id]" class="form-select form-select-sm select-alimento" required>
                        ${optionsItensPedidoHtml}
                    </select>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" step="0.01" class="form-control input-qtd-pedida" name="itens[${itemPedidoIndex}][quantidade_pedida]" placeholder="0,00" required disabled>
                        <span class="input-group-text span-unidade">--</span>
                    </div>
                </td>
                <td class="text-center align-middle">
                    <span class="badge bg-success span-saldo-visual d-none"></span>
                    <input type="hidden" class="input-saldo-oculto" value="0">
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-linha-pedido" title="Remover">X</button>
                </td>
            `;
            tbody.appendChild(tr);
            itemPedidoIndex++;
        }

        if(document.getElementById('tbody-itens-pedido')) {
            adicionarLinhaPedido();
        }

        const btnAddItemPedido = document.getElementById('btn-add-item-pedido');
        if(btnAddItemPedido) {
            btnAddItemPedido.addEventListener('click', adicionarLinhaPedido);
        }

        document.getElementById('tbody-itens-pedido')?.addEventListener('change', function(e) {
            if (e.target.classList.contains('select-alimento')) {
                const tr = e.target.closest('tr');
                const option = e.target.options[e.target.selectedIndex];
                const saldo = parseFloat(option.getAttribute('data-saldo'));
                const unidade = option.getAttribute('data-unidade');

                tr.querySelector('.input-saldo-oculto').value = saldo;
                tr.querySelector('.span-unidade').innerText = unidade;

                const spanSaldo = tr.querySelector('.span-saldo-visual');
                spanSaldo.innerText = saldo.toLocaleString('pt-BR', {minimumFractionDigits: 2}) + ' ' + unidade;
                spanSaldo.classList.remove('d-none');

                const qtdInput = tr.querySelector('.input-qtd-pedida');
                qtdInput.disabled = false;
                qtdInput.value = '';
                qtdInput.classList.remove('is-invalid');
            }
        });

        document.getElementById('tbody-itens-pedido')?.addEventListener('input', function(e) {
            if (e.target.classList.contains('input-qtd-pedida')) {
                const tr = e.target.closest('tr');
                const qtdDigitada = parseFloat(e.target.value) || 0;
                const saldoDisponivel = parseFloat(tr.querySelector('.input-saldo-oculto').value);

                if (qtdDigitada > saldoDisponivel || qtdDigitada <= 0) {
                    e.target.classList.add('is-invalid');
                    btnEnviarPedido.disabled = true;
                } else {
                    e.target.classList.remove('is-invalid');
                    const temErro = document.querySelectorAll('.input-qtd-pedida.is-invalid').length > 0;
                    btnEnviarPedido.disabled = temErro;
                }
            }
        });

        document.getElementById('tbody-itens-pedido')?.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-linha-pedido')) {
                const totalLinhas = document.querySelectorAll('#tbody-itens-pedido tr').length;
                if (totalLinhas > 1) {
                    e.target.closest('tr').remove();
                    const temErro = document.querySelectorAll('.input-qtd-pedida.is-invalid').length > 0;
                    btnEnviarPedido.disabled = temErro;
                } else {
                    alert('O pedido precisa ter pelo menos um item!');
                }
            }
        });
    </script>
@endsection
