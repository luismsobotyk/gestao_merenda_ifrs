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

        <h1 class="h2 d-flex align-items-center gap-3 mb-0">
            <i class="bi bi-file-earmark-plus text-warning bg-warning bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center shadow-sm" style="width: 45px; height: 45px; font-size: 1.5rem;"></i>
            Contratos de Fornecimento
        </h1>

        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('contrato.criar') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-file-earmark-plus"></i>
                Cadastrar Novo Contrato
            </a>
        </div>
    </div>

    {{-- Área de Filtros e Pesquisa --}}
    <div class="card search-card mb-4 shadow-sm">
        <div class="card-body">
            <form class="row g-3" method="GET" action="{{ route('contratos') }}">

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-body-secondary">Fornecedor</label>
                    <input type="text" class="form-control form-control-sm" name="fornecedor" placeholder="Ex: Cooperativa..." value="{{ request('fornecedor') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-body-secondary">Ano do Pregão</label>
                    <select class="form-select form-select-sm" name="ano">
                        <option value="">Todos</option>
                        <option value="2026" {{ request('ano') == '2026' ? 'selected' : '' }}>2026</option>
                        <option value="2025" {{ request('ano') == '2025' ? 'selected' : '' }}>2025</option>
                        <option value="2024" {{ request('ano') == '2024' ? 'selected' : '' }}>2024</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-body-secondary">Status</label>
                    <select class="form-select form-select-sm" name="status">
                        <option value="">Todos</option>
                        <option value="Vigente" {{ request('status') == 'Vigente' ? 'selected' : '' }}>Vigente</option>
                        <option value="Encerrado" {{ request('status') == 'Encerrado' ? 'selected' : '' }}>Encerrado</option>
                        <option value="Pausado" {{ request('status') == 'Pausado' ? 'selected' : '' }}>Pausado</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
                        <i class="bi bi-search"></i>
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
                    <thead class="table-light small">
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

                            <td class="fw-bold text-dark">R$ {{ number_format($contrato->valor_global, 2, ',', '.') }}</td>

                            <td>
                                @if(strtolower($contrato->status) === 'vigente')
                                    <span class="badge bg-success">Vigente</span>
                                @else
                                    <span class="badge bg-secondary">Encerrado</span>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('contrato.visualizar', $contrato->id) }}" class="btn btn-sm btn-outline-primary" title="Visualizar Detalhes e Gerenciar Empenhos">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('contrato.editar', $contrato->id) }}" class="btn btn-sm btn-outline-secondary" title="Editar Dados Básicos">
                                        <i class="bi bi-pencil-square"></i>
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
