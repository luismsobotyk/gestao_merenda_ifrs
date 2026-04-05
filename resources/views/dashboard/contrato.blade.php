@extends('dashboard.layout')

@section('custom_css')
    <style>
        /* Estilos específicos para a gestão de saldos */
        .money-card {
            transition: transform 0.2s;
        }
        .money-card:hover {
            transform: translateY(-5px);
        }
        .progress-consumption {
            height: 8px; /* Ligeiramente menor para caber na tabela de itens */
            border-radius: 5px;
        }
        .table-vencimento td, .table-vencimento th,
        .table-empenhos td, .table-empenhos th {
            vertical-align: middle;
        }
        .item-row:hover {
            background-color: rgba(25, 135, 84, 0.05) !important; /* Verde IFRS bem claro no hover */
        }
    </style>
@endsection

@section('content')
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
                {{-- Botão Fazer Pedido removido daqui --}}
            </div>
            <span class="badge bg-success d-flex align-items-center p-2 text-uppercase">Contrato Vigente</span>
        </div>
    </div>

    {{-- ETAPA 1 e 3: Resumo do Contrato e Saldos Financeiros Totais --}}
    <div class="row mb-4">
        {{-- Detalhes Burocráticos (Etapa 1) --}}
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light text-uppercase fw-bold small text-body-secondary">
                    Dados do Processo Principal
                </div>

                <div class="card-body">
                    <h5 class="card-title text-primary">Fornecedor: Coomavit</h5>
                    <p class="card-text mb-1"><strong>Nº Pregão/Chamada:</strong> 05/2026</p>
                    <p class="card-text mb-1"><strong>Processo SIPAC:</strong> 23344.001234/2026-10</p>
                    <p class="card-text mb-2"><strong>Vigência:</strong> 01/01/2026 a 31/12/2026</p>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                        <div>
                            <span class="d-block small text-muted">Contato Principal:</span>
                            <strong>contato@coomavit.com.br</strong>
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
                    <button class="btn btn-sm btn-outline-secondary w-100">Editar Dados do Contrato</button>
                </div>
            </div>
        </div>

        {{-- Visão Geral Financeira Monetária (VALORES ATUALIZADOS) --}}
        <div class="col-md-8">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card money-card shadow-sm border-primary bg-primary bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <h6 class="text-uppercase text-muted small">Total Contratado</h6>
                            <h3 class="card-title text-primary">R$ 150.000,00</h3>
                            <p class="text-muted small mb-0">Valor global do processo</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card money-card shadow-sm border-info bg-info bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <h6 class="text-uppercase text-muted small">Total Empenhado</h6>
                            {{-- Valor ajustado: 5966 + 9030 + 6900 = 21896 --}}
                            <h3 class="card-title text-info">R$ 21.896,00</h3>
                            <p class="text-muted small mb-0">Soma de todas pedidos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card money-card shadow-sm border-success bg-success bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <h6 class="text-uppercase text-muted small">Saldo Empenhos</h6>
                            {{-- Valor ajustado com base nos saldos quantitativos --}}
                            <h3 class="card-title text-success">R$ 3.785,00</h3>
                            <p class="text-muted small mb-0">Disponível em R$</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detalhes Completos do Contrato --}}
    <div class="modal fade" id="modalDetalhesContrato" tabindex="-1" aria-labelledby="modalDetalhesContratoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title h5 text-uppercase fw-bold" id="modalDetalhesContratoLabel">Contatos adicionais</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card bg-body-tertiary border-0 shadow-none mb-3">
                        <div class="card-body">
                            <ul class="list-group list-group-flush" id="listaResponsaveis">
                                <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center p-2">
                                    <div>
                                        <strong>Fulano Alves</strong><br>
                                        <strong class="text-primary">•</strong> fulano@coomavita.com.br <br>
                                        <strong class="text-primary">•</strong> (51) 91234 9876
                                    </div>
                                </li>
                                <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center p-2">
                                    <div>
                                        <strong>João Silva</strong><br>
                                        <strong class="text-primary">•</strong> joao@coomavita.com.br <br>
                                        <strong class="text-primary">•</strong> (51) 99654 3514
                                    </div>
                                </li>
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

    {{-- NOVA SEÇÃO: Saldos de Itens Individualizados --}}
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
                                <th>#</th>
                                <th>Item</th>
                                <th>Unidade</th>
                                <th>Qtd. Empenhada</th>
                                <th>Qtd. Consumida</th>
                                <th>Saldo Disponível</th>
                                <th width="20%">Progresso de Consumo</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="item-row">
                                <td>1</td>
                                <td><strong>Banana</strong></td>
                                <td>Quilo (kg)</td>
                                <td>950,00</td>
                                <td>400,00</td>
                                <td class="fw-bold text-success fs-5">500,00 kg</td>
                                <td>
                                    <div class="progress progress-consumption" role="progressbar" aria-label="Consumo Arroz" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar bg-success" style="width: 42.1%"></div>
                                    </div>
                                    <small class="text-muted">42,10% do empenhado</small>
                                </td>
                            </tr>
                            <tr class="item-row">
                                <td>5</td>
                                <td><strong>Maçã</strong></td>
                                <td>Quilo (kg)</td>
                                <td>700,00</td>
                                <td>650,00</td>
                                <td class="fw-bold text-danger fs-5">50,00 kg</td>
                                <td>
                                    <div class="progress progress-consumption" role="progressbar" aria-label="Consumo Feijão" aria-valuenow="93" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar bg-danger" style="width: 92.85%"></div>
                                    </div>
                                    <small class="text-muted">92,85% - Necessário novo empenho</small>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DIVS INVERTIDAS --}}
    <div class="row">

        {{-- ÁREA DOS ÚLTIMOS PEDIDOS AGORA NA ESQUERDA (col-lg-4) --}}
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-uppercase fw-bold text-body-secondary small">Últimos Pedidos</h5>
                    {{-- Botão para gerar um novo pedido de fornecimento --}}
                    <button class="btn btn-sm btn-primary d-flex align-items-center gap-1" title="Solicitar nova entrega">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-cart-plus" viewBox="0 0 16 16">
                            <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z"/>
                            <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                        </svg>
                        Novo
                    </button>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-start p-3">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold text-primary mb-1">Pedido #1042</div>
                                <span class="d-block small text-dark mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-box me-1" viewBox="0 0 16 16"><path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z"/></svg>
                                Banana (70kg)
                            </span>
                                <span class="d-block text-muted small" style="font-size: 0.75rem;">Solicitado: 29/04/2026</span>
                                <span class="d-block text-muted small" style="font-size: 0.75rem;">Previsto: 08/05/2026</span>
                            </div>
                            <div class="text-end d-flex flex-column align-items-end gap-2">
                                <span class="badge bg-warning text-dark text-uppercase shadow-sm">Aguardando</span>
                                <button class="btn btn-sm btn-outline-success py-0 px-2" style="font-size: 0.75rem;" title="Confirmar recebimento dos itens">
                                    Receber
                                </button>
                            </div>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-start p-3 bg-danger bg-opacity-10 border-danger border-opacity-25">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold text-danger mb-1">Pedido #1038</div>
                                <span class="d-block small text-dark mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-boxes me-1" viewBox="0 0 16 16"><path d="M7.752.066a.5.5 0 0 1 .496 0l3.75 2.143a.5.5 0 0 1 .252.434v3.995l3.498 2A.5.5 0 0 1 16 9.07v4.286a.5.5 0 0 1-.252.434l-3.75 2.143a.5.5 0 0 1-.496 0l-3.502-2-3.502 2.001a.5.5 0 0 1-.496 0l-3.75-2.143A.5.5 0 0 1 0 13.357V9.071a.5.5 0 0 1 .252-.434L3.75 6.638V2.643a.5.5 0 0 1 .252-.434zM4.25 7.504 1.508 9.071l2.742 1.567 2.742-1.567zM7.5 9.933l-2.75 1.571v3.134l2.75-1.571zm1 3.134 2.75 1.571v-3.134L8.5 9.933zm.508-3.996 2.742 1.567 2.742-1.567-2.742-1.567zm2.242-2.433V3.504L8.5 5.076V8.21zM7.5 8.21V5.076L4.75 3.504v3.134zM5.258 2.643 8 4.21l2.742-1.567L8 1.076zM15 9.933l-2.75 1.571v3.134L15 13.067zM3.75 14.638v-3.134L1 9.933v3.134z"/></svg>
                                Maçã
                            </span>
                                <span class="d-block text-muted small" style="font-size: 0.75rem;">Solicitado: 20/03/2026</span>
                                <span class="d-block text-danger fw-bold small" style="font-size: 0.75rem;">Atrasado desde: 02/04/2026</span>
                            </div>
                            <div class="text-end d-flex flex-column align-items-end gap-2">
                                <span class="badge bg-danger text-uppercase shadow-sm">Atrasado</span>
                                <button class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size: 0.75rem;" title="Registrar chegada com atraso">
                                    Receber
                                </button>
                            </div>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-start p-3 bg-light opacity-75">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold text-muted mb-1 text-decoration-line-through">Pedido #1020</div>
                                <span class="d-block small text-muted mb-1">Óleo de Soja (50L)</span>
                                <span class="d-block text-muted small" style="font-size: 0.75rem;">Recebido em: 15/03/2026</span>
                            </div>
                            <div class="text-end d-flex flex-column align-items-end gap-2">
                                <span class="badge bg-secondary text-uppercase shadow-sm">Concluído</span>
                                <a href="#" class="text-decoration-none small text-primary" style="font-size: 0.75rem;">
                                    Ver NF #5543
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-white border-top-0">
                    <button class="btn btn-sm btn-outline-secondary w-100">Ver Histórico Completo</button>
                </div>
            </div>
        </div>

        {{-- ÁREA NOTAS DE EMPENHO AGORA NA DIREITA (col-lg-8) --}}
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
                            <tr>
                                <td>
                                    <strong>2026NE00123</strong><br>
                                    <span class="text-muted small">15/01/2026</span>
                                </td>
                                <td>1</td>
                                <td>Banana</td>
                                <td>950,00 kg</td>
                                <td>R$ 5.966,00</td>
                                <td><span class="badge bg-success">Ativo</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary" title="Ver Detalhes">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>2026NE00122</strong><br>
                                    <span class="text-muted small">15/01/2026</span>
                                </td>
                                <td>5</td>
                                <td>Maçã</td>
                                <td>700,00 kg</td>
                                <td>R$ 9.030,00</td>
                                <td><span class="badge bg-warning text-dark">Esgotando</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary" title="Ver Detalhes">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>2025NE00005</strong><br>
                                    <span class="text-muted small">02/08/2025</span>
                                </td>
                                <td>7</td>
                                <td>Pão de Batata</td>
                                <td>1000,00 un</td>
                                <td>R$ 6.900,00</td>
                                <td><span class="badge bg-danger">Finalizado</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
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


    {{-- Modais de Cadastro (Os modais permanecem iguais) --}}

    {{-- Modal Cadastrar Empenho (Etapa 2) --}}
    <div class="modal fade" id="modalCadastrarEmpenho" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" method="POST" action="#">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title h5">Inserir Nova Nota de Empenho (NE)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Selecione o Item do Contrato a Empenhar</label>
                            <select class="form-select form-select-lg border-primary" name="item_contrato_id" required>
                                <option value="" selected disabled>Escolha o item...</option>
                                <option value="1">Arroz Branco Tipo 1 (kg) - Saldo a Empenhar: 2.000 kg</option>
                                <option value="2">Feijão Preto Tipo 1 (kg) - Saldo a Empenhar: 1.500 kg</option>
                                <option value="3">Óleo de Soja (L) - Saldo a Empenhar: 500 L</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Número da NE (ex: 2026NE00...)</label>
                            <input type="text" class="form-control" name="numero_ne" required placeholder="Digite o número">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Data de Emissão</label>
                            <input type="date" class="form-control" name="data_emissao" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Quantidade a Empenhar (na unidade do item)</label>
                            <input type="number" step="0.01" class="form-control" name="quantidade" required placeholder="Ex: 500.50">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valor Total Disponibilizado</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" step="0.01" class="form-control" name="valor_total" required placeholder="0,00">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Cadastrar Empenho</button>
                </div>
            </form>
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
                            <option value="1" data-saldo="1800" data-unidade="kg">Arroz Branco Tipo 1 (kg)</option>
                            <option value="2" data-saldo="100" data-unidade="kg">Feijão Preto Tipo 1 (kg)</option>
                            <option value="3" data-saldo="500" data-unidade="L">Óleo de Soja (L)</option>
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
