@extends('dashboard.layout')

@section('custom_css')
    <style>
        .hover-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            border-color: #0d6efd !important;
        }
        .icon-circle {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        /* Ajuste para o toggle ficar maior e mais fácil de clicar */
        .form-switch .form-check-input.toggle-modo {
            width: 3em;
            height: 1.5em;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-0">Controle de Retirada</h1>
            <small class="text-muted">Gerencie os modos de operação disponíveis no seu campus.</small>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger small shadow-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="row g-4 pt-2">
        {{-- CARD 1: MODO AUTOATENDIMENTO (TOTEM) --}}
        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-secondary border-opacity-25 hover-card">
                {{-- Cabeçalho com Badge e Toggle --}}
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-3 px-4">
                    <span id="badge-totem" class="badge {{ $totemAtivo ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                        {{ $totemAtivo ? 'Habilitado' : 'Desabilitado' }}
                    </span>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input toggle-modo" type="checkbox" role="switch" data-modo="totem" {{ $totemAtivo ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="card-body p-4 text-center">
                    <div class="icon-circle bg-primary bg-opacity-10 text-primary mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-display" viewBox="0 0 16 16">
                            <path d="M0 4s0-2 2-2h12s2 0 2 2v6s0 2-2 2h-4c0 .667.083 1.167.25 1.5H11a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1h.75c.167-.333.25-.833.25-1.5H2s-2 0-2-2zm1.398-.855a.758.758 0 0 0-.254.302A1.46 1.46 0 0 0 1 4.01V10c0 .325.078.502.145.602.07.105.17.188.302.254a1.464 1.464 0 0 0 .538.143L2.01 11H14c.325 0 .502-.078.602-.145a.758.758 0 0 0 .254-.302 1.464 1.464 0 0 0 .143-.538L15 9.99V4c0-.325-.078-.502-.145-.602a.757.757 0 0 0-.302-.254A1.46 1.46 0 0 0 13.99 3H2c-.325 0-.502.078-.602.145z"/>
                        </svg>
                    </div>
                    <h4 class="fw-bold mb-3">Modo Autoatendimento</h4>
                    <p class="text-muted">
                        Ideal para terminais ou tablets onde o próprio aluno digita a matrícula. O sistema valida automaticamente o curso e bloqueia retiradas duplicadas.
                    </p>

                    {{-- O botão agora abre um Modal em vez de ir direto para o link --}}
                    <button type="button" id="btn-totem" class="btn btn-primary mt-2 rounded-pill px-5 fw-bold {{ $totemAtivo ? '' : 'disabled' }}" data-bs-toggle="modal" data-bs-target="#modalConfiguraTotem">
                        Acessar Totem
                    </button>
                </div>
            </div>
        </div>

        {{-- CARD 2: MODO LANÇAMENTO MANUAL --}}
        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-secondary border-opacity-25 hover-card">
                {{-- Cabeçalho com Badge e Toggle --}}
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-3 px-4">
                    <span id="badge-manual" class="badge {{ $manualAtivo ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                        {{ $manualAtivo ? 'Habilitado' : 'Desabilitado' }}
                    </span>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input toggle-modo" type="checkbox" role="switch" data-modo="manual" {{ $manualAtivo ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="card-body p-4 text-center">
                    <div class="icon-circle bg-success bg-opacity-10 text-success mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                            <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                        </svg>
                    </div>
                    <h4 class="fw-bold mb-3">Modo Lançamento Manual</h4>
                    <p class="text-muted">
                        Acesso para o servidor ou bolsista responsável. Permite pesquisar alunos por nome ou matrícula e fazer o registro da entrega individualmente.
                    </p>

                    {{-- O botão agora é o link. --}}
                    <a href="{{ route('retirada.manual') }}" id="btn-manual" class="btn btn-success mt-2 rounded-pill px-5 fw-bold {{ $manualAtivo ? '' : 'disabled' }}">
                        Acessar Painel
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL DE CONFIGURAÇÃO DO TOTEM --}}
    <div class="modal fade" id="modalConfiguraTotem" tabindex="-1" aria-labelledby="modalConfiguraTotemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold" id="modalConfiguraTotemLabel">⚙️ Configurar Totem</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('retirada.totem') }}" method="GET">
                    <div class="modal-body p-4">
                        <label class="form-label fw-bold text-secondary mb-3">Qual turno será servido agora?</label>
                        <select class="form-select form-select-lg border-primary text-primary fw-bold shadow-sm" name="horario_id" required>
                            <option value="">-- Escolha o Turno --</option>
                            @foreach($horarios as $h)
                                <option value="{{ $h->id }}">
                                    {{ $h->nome }} ({{ \Carbon\Carbon::parse($h->hora_inicio)->format('H:i') }} às {{ \Carbon\Carbon::parse($h->hora_fim)->format('H:i') }})
                                </option>
                            @endforeach
                        </select>

                        {{-- ALERTA DE SEM CARDÁPIO (Aparece apenas se a variável for falsa) --}}
                        @if(!$temCardapioHoje)
                            <div class="alert alert-warning mt-4 mb-0 small border-warning border-opacity-50">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkCienteSemCardapio" required style="cursor: pointer;">
                                    <label class="form-check-label text-dark fw-bold" for="checkCienteSemCardapio" style="cursor: pointer;">
                                        Estou ciente de que não existe cardápio padrão ou exceção cadastrada para a data de hoje ({{ \Carbon\Carbon::today()->format('d/m/Y') }}).
                                    </label>
                                </div>
                            </div>
                        @endif

                        <div class="form-text mt-3 text-muted small">
                            <i class="bi bi-info-circle"></i> O Totem será bloqueado neste turno. Para alterar, você precisará sair do Totem e configurá-lo novamente.
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        {{-- O botão começa bloqueado se não tiver cardápio --}}
                        <button type="submit" class="btn btn-primary px-4 fw-bold" id="btnSubmitTotem" {{ !$temCardapioHoje ? 'disabled' : '' }}>Iniciar Totem</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-modo').forEach(toggle => {
                toggle.addEventListener('change', async function() {
                    const modo = this.getAttribute('data-modo'); // 'totem' ou 'manual'
                    const isChecked = this.checked;

                    const badge = document.getElementById(`badge-${modo}`);
                    const btn = document.getElementById(`btn-${modo}`);

                    // Bloqueia o botão para evitar múltiplos cliques
                    this.disabled = true;

                    // 1. Atualização Visual Imediata (Feedback instantâneo na tela)
                    if (isChecked) {
                        badge.classList.replace('bg-danger', 'bg-success');
                        badge.innerText = 'Habilitado';
                        btn.classList.remove('disabled');
                    } else {
                        badge.classList.replace('bg-success', 'bg-danger');
                        badge.innerText = 'Desabilitado';
                        btn.classList.add('disabled');
                    }

                    // 2. Requisição para salvar no banco
                    try {
                        const response = await fetch(`{{ route('retirada.toggle') }}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ modo: modo, ativo: isChecked })
                        });

                        if (!response.ok) throw new Error('Falha de rede');

                        // Libera o botão novamente
                        this.disabled = false;

                    } catch (error) {
                        console.error('Erro ao salvar configuração:', error);
                        alert('Erro ao alterar modo. Verifique a conexão com a internet.');

                        // 3. Reverte as mudanças visuais em caso de erro
                        this.checked = !isChecked;
                        this.disabled = false;

                        if (!isChecked) {
                            badge.classList.replace('bg-danger', 'bg-success');
                            badge.innerText = 'Habilitado';
                            btn.classList.remove('disabled');
                        } else {
                            badge.classList.replace('bg-success', 'bg-danger');
                            badge.innerText = 'Desabilitado';
                            btn.classList.add('disabled');
                        }
                    }
                });
            });
            const checkboxCiente = document.getElementById('checkCienteSemCardapio');
            const btnSubmitTotem = document.getElementById('btnSubmitTotem');

            if (checkboxCiente) {
                checkboxCiente.addEventListener('change', function() {
                    btnSubmitTotem.disabled = !this.checked;
                });
            }
        });
    </script>
@endsection
