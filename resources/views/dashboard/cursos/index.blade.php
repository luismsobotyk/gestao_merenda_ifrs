@extends('dashboard.layout')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
            <h1 class="h2 d-flex align-items-center gap-3 mb-0">
                <i class="bi bi-mortarboard text-info bg-info bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center shadow-sm" style="width: 45px; height: 45px; font-size: 1.5rem;"></i>
                Lista de Cursos
            </h1>
            <small class="text-muted">Última sincronização da grade: <strong>{{ $ultimaSync }}</strong></small>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <form action="{{ route('cursos.sync') }}" method="POST" id="formSyncCursos">
                @csrf
                <button type="submit" class="btn btn-sm btn-primary shadow-sm" id="btnSyncCursos">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat me-1" viewBox="0 0 16 16">
                        <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                        <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                    </svg>
                    Sincronizar com API IFRS
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger small shadow-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            @if($cursos->isEmpty())
                <div class="text-center p-5 text-muted">
                    <h5>Nenhum curso cadastrado no banco local.</h5>
                    <p>Clique no botão "Sincronizar com API IFRS" acima para importar os dados.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-secondary">
                        <tr>
                            <th class="ps-4">Código</th>
                            <th>Curso</th>
                            <th>Nível</th>
                            <th>Turno</th>
                            <th class="text-center pe-4">Direito à Merenda</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cursos as $curso)
                            <tr>
                                <td class="ps-4 text-muted small">{{ $curso->codigo ?? '-' }}</td>
                                <td>{{ Str::title(Str::lower($curso->nome)) }}</td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle">{{ $curso->nivel }}</span></td>
                                <td class="text-muted small">{{ $curso->turno ?? '-' }}</td>
                                {{-- Adicionado position-relative ao td para controlar a posição da mensagem --}}
                                <td class="text-center pe-4 position-relative">
                                    <div class="form-check form-switch d-flex justify-content-center align-items-center mb-0">
                                        <input class="form-check-input flex-shrink-0 toggle-merenda" type="checkbox" role="switch" style="width: 2.5em; height: 1.25em; cursor: pointer;"
                                               data-id="{{ $curso->id }}"
                                            {{ $curso->direito_merenda ? 'checked' : '' }}>

                                        {{-- Span invisível (opacity 0) preparado para receber a mensagem --}}
                                        <span id="feedback-{{ $curso->id }}" class="small fw-bold position-absolute" style="opacity: 0; transition: opacity 0.3s ease; right: 10px;"></span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formSyncCursos = document.getElementById('formSyncCursos');
            if(formSyncCursos) {
                formSyncCursos.addEventListener('submit', function() {
                    const btn = document.getElementById('btnSyncCursos');
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sincronizando...';
                    btn.disabled = true;
                });
            }

            document.querySelectorAll('.toggle-merenda').forEach(toggle => {
                toggle.addEventListener('change', async function() {
                    const cursoId = this.getAttribute('data-id');
                    const isChecked = this.checked;

                    // Seleciona o span específico desta linha
                    const feedbackEl = document.getElementById(`feedback-${cursoId}`);

                    this.disabled = true;

                    // 1. Dá o feedback imediato de "processando"
                    feedbackEl.className = 'small fw-bold text-secondary position-absolute';
                    feedbackEl.innerText = 'Salvando...';
                    feedbackEl.style.opacity = '1';

                    try {
                        const response = await fetch(`/cursos-retirada/${cursoId}/toggle`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ direito_merenda: isChecked })
                        });

                        if (!response.ok) throw new Error('Erro na requisição');

                        // 2. Transforma o texto em verde e confirma o sucesso
                        feedbackEl.className = 'small fw-bold text-success position-absolute';
                        feedbackEl.innerText = 'Salvo ✓';

                        // 3. Esconde a mensagem de forma suave após 2 segundos
                        setTimeout(() => {
                            feedbackEl.style.opacity = '0';
                        }, 2000);

                        this.disabled = false;

                    } catch (error) {
                        console.error(error);

                        // Caso a API falhe ou a internet caia, avisa de forma vermelha
                        feedbackEl.className = 'small fw-bold text-danger position-absolute';
                        feedbackEl.innerText = 'Erro!';

                        setTimeout(() => {
                            feedbackEl.style.opacity = '0';
                        }, 2000);

                        // Reverte o toggle para o estado original
                        this.checked = !isChecked;
                        this.disabled = false;
                    }
                });
            });
        });
    </script>
@endsection
