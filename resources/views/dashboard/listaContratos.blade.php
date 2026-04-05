@extends('dashboard.layout')

{{-- Usamos a seção custom_css se precisarmos de ajustes específicos para a tabela --}}
@section('custom_css')
    <style>
        .table-contratos td, .table-contratos th {
            vertical-align: middle;
        }
        .search-card {
            background-color: var(--bs-body-tertiary);
            border: none;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Contratos de Fornecimento</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            {{-- Botão para iniciar o fluxo (Etapa 1: Cadastro do Pregão/Contrato) --}}
            <a href="#" class="btn btn-sm btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-plus" viewBox="0 0 16 16">
                    <path d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5"/>
                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 1-1 1v12a1 1 0 0 1 1 1h8a1 1 0 0 1 1-1V4.5z"/>
                </svg>
                Cadastrar Novo Contrato
            </a>
        </div>
    </div>

    {{-- Área de Filtros e Pesquisa (Essencial para gestão) --}}
    <div class="card search-card mb-4 shadow-sm">
        <div class="card-body">
            <form class="row g-3" method="GET" action="#">
                <div class="col-md-4">
                    <label class="form-label small text-uppercase fw-bold text-body-secondary">Fornecedor</label>
                    <input type="text" class="form-control form-control-sm" name="fornecedor" placeholder="Ex: Cooperativa...">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-uppercase fw-bold text-body-secondary">Ano do Pregão</label>
                    <select class="form-select form-select-sm" name="ano">
                        <option value="">Todos</option>
                        <option value="2026">2026</option>
                        <option value="2025">2025</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-uppercase fw-bold text-body-secondary">Status</label>
                    <select class="form-select form-select-sm" name="status">
                        <option value="">Todos</option>
                        <option value="vigente">Vigente</option>
                        <option value="encerrado">Encerrado</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabela de Listagem de Contratos --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom-0 pt-3">
            <h5 class="card-title mb-0 text-uppercase fw-bold text-body-secondary small">Relação de Contratos de Merenda</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-contratos mb-0">
                    <thead class="table-light text-uppercase small">
                    <tr>
                        <th>#</th>
                        <th>Fornecedor</th>
                        <th>Processo / SIPAC</th>
                        <th>Vigência</th>
                        <th>Valor Global</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{-- Exemplo estático que se conecta visualmente com a página de detalhes --}}
                    <tr>
                        <td>1</td>
                        <td>
                            <strong>Cooperativa Agrícola PoA</strong><br>
                            <span class="text-muted small">Pregão: 05/2026</span>
                        </td>
                        <td>23344.001234/2026-10</td>
                        <td>
                            01/01/2026<br>
                            <span class="text-muted small">até 31/12/2026</span>
                        </td>
                        <td class="fw-bold text-primary">R$ 150.000,00</td>
                        <td><span class="badge bg-success">Vigente</span></td>
                        <td>
                            <div class="btn-group">
                                {{-- REQUISITO: O Link para abrir deve apontar para /contrato --}}
                                {{-- Em produção, use href="{{ route('contratos.show', 1) }}" ou href="/contrato/1" --}}
                                <a href="/contrato" class="btn btn-sm btn-outline-primary" title="Visualizar Detalhes e Gerenciar Empenhos">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                    </svg>
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-secondary" title="Editar Dados Básicos">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path d="M12.854.146a.5.5 0 0 1 .707 0l9.172 9.172a.5.5 0 0 1 0 .707l-6 6a.5.5 0 0 1-.707 0l-9.172-9.172a.5.5 0 0 1 0-.707zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5z"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    {{-- Exemplo de contrato encerrado --}}
                    <tr>
                        <td>2</td>
                        <td>
                            <strong>Distribuidora Alimentos Sul</strong><br>
                            <span class="text-muted small">Pregão: 01/2025</span>
                        </td>
                        <td>23344.000999/2025-05</td>
                        <td>
                            01/01/2025<br>
                            <span class="text-muted small">até 31/12/2025</span>
                        </td>
                        <td class="fw-bold text-primary">R$ 90.000,00</td>
                        <td><span class="badge bg-secondary">Encerrado</span></td>
                        <td>
                            <div class="btn-group">
                                <a href="/contrato" class="btn btn-sm btn-outline-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                    </svg>
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path d="M12.854.146a.5.5 0 0 1 .707 0l9.172 9.172a.5.5 0 0 1 0 .707l-6 6a.5.5 0 0 1-.707 0l-9.172-9.172a.5.5 0 0 1 0-.707zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5z"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Paginação padrão do Laravel (Estilizada pelo Bootstrap) --}}
        <div class="card-footer bg-white border-top-0 d-flex justify-content-center">
            <nav aria-label="Navegação de páginas">
                <ul class="pagination pagination-sm mb-0">
                    {{-- A CORREÇÃO ESTÁ AQUI NA LINHA ABAIXO --}}
                    <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a></li>
                    <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">Próximo</a></li>
                </ul>
            </nav>
        </div>
    </div>
@endsection
