@extends('dashboard.layout')

@section('custom_css')
    <style>
        .text-lilas { color: #9b59b6 !important; }
        .bg-lilas { background-color: rgba(155, 89, 182, 0.12) !important; }

        .bg-lilas-solid { background-color: #9b59b6 !important; }
        .btn-lilas {
            background-color: #9b59b6;
            color: #fff;
            border-color: #9b59b6;
        }
        .btn-lilas:hover {
            background-color: #8e44ad;
            color: #fff;
            border-color: #8e44ad;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
            <h1 class="h2 d-flex align-items-center gap-3 mb-0">
                <i class="bi bi-people text-lilas bg-lilas rounded-circle d-inline-flex justify-content-center align-items-center shadow-sm" style="width: 45px; height: 45px; font-size: 1.5rem;"></i>
                Discentes Autorizados
            </h1>
            <small class="text-muted">Última sincronização de alunos: <strong>{{ $ultimaSync }}</strong> | Total na base: <strong>{{ $alunos->total() }}</strong> discentes</small>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <form action="{{ route('cursos.sync.alunos') }}" method="POST" id="formSyncAlunos">
                @csrf
                <button type="submit" class="btn btn-sm btn-primary shadow-sm" id="btnSyncAlunos">
                    <i class="bi bi-arrow-repeat"></i>
                    Sincronizar Alunos
                </button>
            </form>
        </div>
    </div>

    @if(request('sync_sucesso'))
        <div class="alert alert-success alert-dismissible fade show small shadow-sm" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            Sincronização realizada com sucesso! <strong>{{ request('alunos', 0) }}</strong> alunos autorizados foram importados ou atualizados.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- BARRA DE PESQUISA E FILTROS --}}
    <div class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body p-3">
            <form action="{{ route('alunos.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="busca" class="form-control" placeholder="Buscar por Nome ou Matrícula..." value="{{ request('busca') }}">
                </div>
                <div class="col-md-5">
                    <select name="curso_id" class="form-select">
                        <option value="">Todos os Cursos Autorizados</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                                {{ Str::title(Str::lower($curso->nome)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    @if(request()->anyFilled(['busca', 'curso_id']))
                        <a href="{{ route('alunos.index') }}" class="btn btn-outline-secondary" title="Limpar Filtros">X</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- TABELA DE ALUNOS --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            @if($alunos->isEmpty())
                <div class="text-center p-5 text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-emoji-frown mb-3 opacity-50" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="M4.285 12.433a.5.5 0 0 0 .683-.183A3.5 3.5 0 0 1 8 10.5c1.295 0 2.426.703 3.032 1.75a.5.5 0 0 0 .866-.5A4.5 4.5 0 0 0 8 9.5a4.5 4.5 0 0 0-3.898 2.25.5.5 0 0 0 .183.683M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5"/>
                    </svg>
                    <h5>Nenhum discente encontrado.</h5>
                    <p>Verifique os filtros ou execute a sincronização no botão superior.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-secondary">
                        <tr>
                            <th class="ps-4">Matrícula</th>
                            <th>Nome do Discente</th>
                            <th>Curso Vinculado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($alunos as $aluno)
                            <tr>
                                <td class="ps-4 text-muted fw-bold">{{ $aluno->matricula }}</td>
                                <td><div class="ps-4 text-muted fw-bold">{{ Str::title(Str::lower($aluno->nome)) }}</div></td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle text-wrap text-start lh-sm" style="max-width: 250px;">
                                        {{ Str::title(Str::lower($aluno->curso->nome)) ?? 'Curso Desconhecido' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center p-3 border-top">
                    {{ $alunos->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- BOTÃO INVISÍVEL PARA DISPARAR O MODAL --}}
    <button type="button" id="btnOpenModalProgresso" data-bs-toggle="modal" data-bs-target="#modalProgressoSync" class="d-none"></button>

    {{-- Modal de Progresso --}}
    <div class="modal fade" id="modalProgressoSync" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Sincronizando Alunos...</h5>
                </div>
                <div class="modal-body text-center p-4">
                    <p id="textoProgresso" class="mb-3 fw-bold text-muted">Iniciando conexão...</p>
                    <div class="progress" style="height: 25px;">
                        <div id="barraProgresso" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    <p id="detalheProgresso" class="mt-3 small text-muted mb-0">Preparando lotes de dados.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sampleCursos = {!! json_encode($cursosAutorizados) !!};
            // Força a conversão em array puro caso o Laravel envie como objeto de coleção filtrada
            const cursosAutorizados = Array.isArray(sampleCursos) ? sampleCursos : Object.values(sampleCursos);

            const formSyncAlunos = document.getElementById('formSyncAlunos');
            const barraProgresso = document.getElementById('barraProgresso');
            const textoProgresso = document.getElementById('textoProgresso');
            const detalheProgresso = document.getElementById('detalheProgresso');

            if(formSyncAlunos) {
                formSyncAlunos.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    if (cursosAutorizados.length === 0) {
                        alert('Nenhum curso marcado com direito à merenda. Ative os cursos antes de sincronizar os alunos.');
                        return;
                    }

                    barraProgresso.style.width = '0%';
                    barraProgresso.innerText = '0%';
                    textoProgresso.innerText = 'Iniciando Sincronização...';
                    detalheProgresso.innerText = '';
                    document.getElementById('btnSyncAlunos').disabled = true;

                    document.getElementById('btnOpenModalProgresso').click();

                    let totalCursos = cursosAutorizados.length;
                    let poolsProcessados = 0;
                    let totalAlunosSalvosGeral = 0;

                    for (const curso of cursosAutorizados) {
                        textoProgresso.innerText = `Processando curso: ${curso.nome}`;
                        let pagina = 1;
                        let temMaisAlunos = true;

                        while (temMaisAlunos) {
                            detalheProgresso.innerText = `Buscando e gravando página ${pagina} do curso ${curso.codigo || ''}...`;

                            try {
                                const responseLaravel = await fetch('{{ route('cursos.sync.alunos') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        curso_id: curso.id,
                                        curso_id_api: curso.id_curso,
                                        pagina: pagina
                                    })
                                });

                                const resultLaravel = await responseLaravel.json();

                                if (!responseLaravel.ok || !resultLaravel.success) {
                                    throw new Error(resultLaravel.error || 'Erro no servidor.');
                                }

                                totalAlunosSalvosGeral += resultLaravel.salvos;
                                temMaisAlunos = resultLaravel.tem_mais;
                                pagina++;

                            } catch (error) {
                                console.error(`Erro no curso ${curso.nome}:`, error);
                                detalheProgresso.innerHTML = `<span class="text-danger">Erro na página ${pagina}. Pulando lote.</span>`;
                                temMaisAlunos = false;
                            }
                        }

                        poolsProcessados++;
                        let porcentagem = Math.round((poolsProcessados / totalCursos) * 100);
                        barraProgresso.style.width = porcentagem + '%';
                        barraProgresso.innerText = porcentagem + '%';
                    }

                    textoProgresso.innerText = 'Sincronização Concluída!';
                    barraProgresso.classList.remove('progress-bar-animated');
                    detalheProgresso.innerHTML = `Discentes atualizados: <strong>${totalAlunosSalvosGeral}</strong>. Atualizando painel...`;

                    setTimeout(() => {
                        window.location.href = `{{ route('alunos.index') }}?sync_sucesso=1&alunos=${totalAlunosSalvosGeral}`;
                    }, 1500);
                });
            }
        });
    </script>
@endsection
