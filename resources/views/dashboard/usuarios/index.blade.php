@extends('dashboard.layout')

@section('custom_css')
<style>
    span.border-primary,
    .input-group-text.border-primary {
        border-color: #1a8654 !important;
    }
    .input-group-text {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .input-group-text .bi {
        line-height: 0 !important;
    }
</style>
@endsection

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
        <div>
            <h1 class="h2 d-flex align-items-center gap-3 mb-0">
                <i class="bi bi-gear text-secondary bg-secondary bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center shadow-sm" style="width: 45px; height: 45px; font-size: 1.5rem;"></i>
                Gestão de Acessos
            </h1>
            <small class="text-muted">Controle quais os servidores e bolsistas que podem acessar o sistema via LDAP.</small>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalAdicionar" id="btnAbrirModal">
                <i class="bi bi-person-plus-fill me-1"></i> Autorizar Novo Acesso
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary">
                    <tr>
                        <th class="ps-4">Nome</th>
                        <th>Usuário</th>
                        <th>E-mail</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($usuarios as $user)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">
                                <i class="bi bi-person-circle text-muted me-2 fs-5"></i>
                                {{ $user->name }}
                                {{-- Selo visual para o Super Admin --}}
                                @if(\App\Models\User::isSuperAdmin($user))
                                    <small class="text-muted fw-normal ms-1">(Admin)</small>
                                @endif
                            </td>
                            <td>{{ $user->username }}</td>
                            <td><a href="mailto:{{ $user->email }}" class="text-decoration-none text-dark">{{ $user->email }}</a></td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('usuarios.historico', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Histórico de Acesso">
                                        <i class="bi bi-clock-history"></i>
                                    </a>

                                    {{-- 2ª Camada de Proteção: Controla a exibição do botão de excluir --}}
                                    @if(\App\Models\User::isSuperAdmin($user))
                                        <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Acesso protegido pelo sistema">
                                            <i class="bi bi-shield-lock-fill"></i>
                                        </button>
                                    @else
                                        {{-- Botão de excluir normal para os restantes utilizadores --}}
                                        <form action="{{ route('usuarios.excluir', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja revogar o acesso deste utilizador?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" {{ auth()->id() === $user->id ? 'disabled' : '' }} title="Revogar Acesso">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Nenhum utilizador encontrado.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAdicionar" tabindex="-1" aria-labelledby="modalAdicionarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold" id="modalAdicionarLabel">Procurar Usuário no Domínio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body bg-light">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white border-primary"><i class="bi bi-search"></i></span>
                        <input type="text" id="ldap_search_input" class="form-control border-primary" placeholder="Escreva o nome ou login (mínimo 3 letras)..." autocomplete="off">
                    </div>

                    <div id="ldap_loading" class="text-center my-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Procurando...</span>
                        </div>
                        <p class="text-muted mt-2 small">Consultando o servidor LDAP...</p>
                    </div>

                    <div id="ldap_results" class="list-group mb-3 shadow-sm" style="display: none; max-height: 250px; overflow-y: auto;">
                    </div>

                    <form method="POST" action="{{ route('usuarios.salvar') }}" id="form_autorizar_usuario" style="display: none;">
                        @csrf
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="bi bi-person-check-fill fs-3 me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1 fw-bold">Utilizador Selecionado:</h6>
                                <div class="small">
                                    <strong>Nome:</strong> <span id="lbl_name"></span><br>
                                    <strong>Login:</strong> <span id="lbl_username"></span><br>
                                    <strong>E-mail:</strong> <span id="lbl_email"></span>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="name" id="hidden_name">
                        <input type="hidden" name="username" id="hidden_username">
                        <input type="hidden" name="email" id="hidden_email">
                    </form>
                </div>

                <div class="modal-footer border-top-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="form_autorizar_usuario" class="btn btn-primary fw-bold" id="btn_salvar_usuario" disabled>
                        <i class="bi bi-shield-lock"></i> Autorizar Acesso
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('ldap_search_input');
            const resultsContainer = document.getElementById('ldap_results');
            const loadingIndicator = document.getElementById('ldap_loading');
            const formAutorizar = document.getElementById('form_autorizar_usuario');
            const btnSalvar = document.getElementById('btn_salvar_usuario');

            let searchTimeout;

            // Ao abrir o modal, limpa a pesquisa anterior
            document.getElementById('modalAdicionar').addEventListener('show.bs.modal', function () {
                searchInput.value = '';
                resultsContainer.style.display = 'none';
                resultsContainer.innerHTML = '';
                formAutorizar.style.display = 'none';
                btnSalvar.disabled = true;
                searchInput.focus();
            });

            // Evento de Digitação com Debounce (evita fazer 100 requisições por segundo)
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const term = this.value.trim();

                if (term.length < 3) {
                    resultsContainer.style.display = 'none';
                    loadingIndicator.style.display = 'none';
                    return;
                }

                loadingIndicator.style.display = 'block';
                resultsContainer.style.display = 'none';
                formAutorizar.style.display = 'none';
                btnSalvar.disabled = true;

                // Aguarda 500ms após o utilizador parar de escrever para ir ao servidor
                searchTimeout = setTimeout(() => {
                    fetch(`{{ route('usuarios.busca_ldap') }}?term=${encodeURIComponent(term)}`)
                        .then(response => response.json())
                        .then(data => {
                            loadingIndicator.style.display = 'none';
                            resultsContainer.innerHTML = '';

                            if (data.error) {
                                resultsContainer.innerHTML = `<div class="list-group-item text-danger"><i class="bi bi-exclamation-triangle"></i> ${data.error}</div>`;
                                resultsContainer.style.display = 'block';
                                return;
                            }

                            if (data.length === 0) {
                                resultsContainer.innerHTML = '<div class="list-group-item text-muted text-center py-3">Nenhum utilizador encontrado no LDAP.</div>';
                                resultsContainer.style.display = 'block';
                                return;
                            }

                            // Monta a lista de resultados
                            data.forEach(user => {
                                const btn = document.createElement('button');
                                btn.type = 'button';
                                btn.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                                btn.innerHTML = `
                                <div>
                                    <strong class="d-block text-dark">${user.name}</strong>
                                    <small class="text-muted"><i class="bi bi-envelope"></i> ${user.email}</small>
                                </div>
                                <span class="badge bg-secondary rounded-pill">${user.username}</span>
                            `;

                                // Quando clica no resultado
                                btn.addEventListener('click', function() {
                                    // Esconde a lista de pesquisa e mostra a caixa verde de confirmação
                                    resultsContainer.style.display = 'none';
                                    formAutorizar.style.display = 'block';
                                    searchInput.value = user.name;

                                    // Preenche a View Visual
                                    document.getElementById('lbl_name').innerText = user.name;
                                    document.getElementById('lbl_username').innerText = user.username;
                                    document.getElementById('lbl_email').innerText = user.email;

                                    // Preenche os inputs escondidos para salvar no Laravel
                                    document.getElementById('hidden_name').value = user.name;
                                    document.getElementById('hidden_username').value = user.username;
                                    document.getElementById('hidden_email').value = user.email;

                                    // Libera o botão de Salvar
                                    btnSalvar.disabled = false;
                                });

                                resultsContainer.appendChild(btn);
                            });

                            resultsContainer.style.display = 'block';
                        })
                        .catch(error => {
                            loadingIndicator.style.display = 'none';
                            resultsContainer.innerHTML = '<div class="list-group-item text-danger"><i class="bi bi-x-circle"></i> Ocorreu um erro de rede ao tentar consultar.</div>';
                            resultsContainer.style.display = 'block';
                        });
                }, 500); // 500ms de delay (Debounce)
            });
        });
    </script>
@endsection
