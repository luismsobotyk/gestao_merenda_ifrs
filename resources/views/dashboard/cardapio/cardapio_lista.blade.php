@extends('dashboard.layout')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Gestão de Cardápios</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('cardapio.novo') }}" class="btn btn-sm btn-primary d-flex align-items-center gap-1 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2z"/>
                </svg>
                Criar Novo Cardápio
            </a>
        </div>
    </div>

    {{-- Alertas de Sucesso/Erro --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small shadow-sm" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle me-1" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/></svg>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase small text-secondary">
                    <tr>
                        <th class="ps-4">Nome do Cardápio</th>
                        <th>Início da Vigência</th>
                        <th>Fim da Vigência</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($cardapios as $cardapio)
                        @php
                            $hoje = \Carbon\Carbon::now()->startOfDay();
                            $inicio = \Carbon\Carbon::parse($cardapio->data_inicio)->startOfDay();
                            $fim = \Carbon\Carbon::parse($cardapio->data_fim)->startOfDay();

                            // Lógica de Status Automático
                            if ($fim->isBefore($hoje)) {
                                $badge = '<span class="badge bg-secondary">Encerrado</span>';
                                $linhaClass = 'opacity-75';
                            } elseif ($inicio->isAfter($hoje)) {
                                $badge = '<span class="badge bg-info text-dark">Programado</span>';
                                $linhaClass = '';
                            } else {
                                $badge = '<span class="badge bg-success">Vigente</span>';
                                $linhaClass = 'fw-medium';
                            }
                        @endphp

                        <tr class="{{ $linhaClass }}">
                            <td class="ps-4 text-primary">
                                {{ $cardapio->nome }}
                            </td>
                            <td>{{ $inicio->format('d/m/Y') }}</td>
                            <td>{{ $fim->format('d/m/Y') }}</td>
                            <td>{!! $badge !!}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('cardapio.editar', $cardapio->id) }}" class="btn btn-sm btn-outline-primary" title="Editar Cardápio">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/></svg>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Excluir" onclick="confirmarExclusao('{{ $cardapio->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted p-5">
                                Nenhum cardápio cadastrado. Clique no botão acima para iniciar seu planejamento.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    @section('custom_js')
        <script>
            function confirmarExclusao(id) {
                if(confirm('Tem certeza que deseja excluir este cardápio? Todo o histórico de planejamento será apagado permanentemente.')) {

                    // Cria um formulário dinâmico
                    let form = document.createElement('form');
                    form.action = '/cardapio/' + id;
                    form.method = 'POST';

                    // Adiciona o Token CSRF de segurança
                    let csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    // Adiciona a falsificação do método DELETE exigida pelo Laravel
                    let methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    // Adiciona o formulário ao corpo da página e envia
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>
    @endsection
@endsection
