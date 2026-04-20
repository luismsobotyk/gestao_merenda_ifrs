@extends('dashboard.layout')

{{-- Usamos a seção custom_css se precisarmos de ajustes específicos para a tabela --}}
@section('custom_css')
    <style>
        .table-contratos td, .table-contratos th {
            vertical-align: middle;
        }
        .search-card {
            /*background-color: var(--bs-body-tertiary);*/
            border: none;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Contratos de Fornecimento</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="#" class="btn btn-sm btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-plus" viewBox="0 0 16 16">
                    <path d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5"/>
                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 1-1 1v12a1 1 0 0 1 1 1h8a1 1 0 0 1 1-1V4.5z"/>
                </svg>
                Cadastrar Novo Contrato
            </a>
        </div>
    </div>

    {{-- Área de Filtros e Pesquisa --}}
    <div class="card search-card mb-4 shadow-sm">
        <div class="card-body">
            <form class="row g-3" method="GET" action="{{ route('contratos') }}">

                <div class="col-md-4">
                    <label class="form-label small text-uppercase fw-bold text-body-secondary">Fornecedor</label>
                    <input type="text" class="form-control form-control-sm" name="fornecedor" placeholder="Ex: Cooperativa..." value="{{ request('fornecedor') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-uppercase fw-bold text-body-secondary">Ano do Pregão</label>
                    <select class="form-select form-select-sm" name="ano">
                        <option value="">Todos</option>
                        <option value="2026" {{ request('ano') == '2026' ? 'selected' : '' }}>2026</option>
                        <option value="2025" {{ request('ano') == '2025' ? 'selected' : '' }}>2025</option>
                        <option value="2024" {{ request('ano') == '2024' ? 'selected' : '' }}>2024</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-uppercase fw-bold text-body-secondary">Status</label>
                    <select class="form-select form-select-sm" name="status">
                        <option value="">Todos</option>
                        <option value="Vigente" {{ request('status') == 'Vigente' ? 'selected' : '' }}>Vigente</option>
                        <option value="Encerrado" {{ request('status') == 'Encerrado' ? 'selected' : '' }}>Encerrado</option>
                        <option value="Pausado" {{ request('status') == 'Pausado' ? 'selected' : '' }}>Pausado</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                        Filtrar
                    </button>

                    @if(request()->anyFilled(['fornecedor', 'ano', 'status']))
                        <a href="{{ route('contratos') }}" class="btn btn-sm btn-light text-danger" title="Limpar Filtros">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                            </svg>
                        </a>
                    @endif
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
                    @forelse($contratos as $contrato)
                        <tr>
                            <td>{{ $contratos->firstItem() + $loop->index }}</td>
                            <td>
                                <strong>{{ $contrato->fornecedor->nome ?? "Fornecedor não encontrado" }}</strong><br>
                                <span class="text-muted small">Pregão: {{ $contrato->pregao }}</span>
                            </td>
                            <td>{{ $contrato->processo }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($contrato->inicio_vigencia)->format('d/m/Y') }}<br>
                                <span class="text-muted small">até {{ \Carbon\Carbon::parse($contrato->fim_vigencia)->format('d/m/Y') }}</span>
                            </td>
                            <td class="fw-bold text-primary">R$ {{ number_format($contrato->valor_global, 2, ',', '.') }}</td>
                            <td>
                                @if(strtolower($contrato->status)==='vigente')
                                    <span class="badge bg-success">Vigente</span></td>
                                @else
                                    <span class="badge bg-secondary">Encerrado</span></td>
                                @endif
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('contrato.visualizar', $contrato->id) }}" class="btn btn-sm btn-outline-primary" title="Visualizar Detalhes e Gerenciar Empenhos">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('contrato.editar', $contrato->id) }}" class="btn btn-sm btn-outline-secondary" title="Editar Dados Básicos">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.854.146a.5.5 0 0 1 .707 0l9.172 9.172a.5.5 0 0 1 0 .707l-6 6a.5.5 0 0 1-.707 0l-9.172-9.172a.5.5 0 0 1 0-.707zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Nenhum contrato encontrado.</td>
                            </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top-0 d-flex justify-content-center pt-3">
            {{ $contratos->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
