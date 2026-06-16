@extends('dashboard.layout')

@section('custom_css')
    <style>
        .totem-container {
            max-width: 700px;
            margin: 0 auto;
            padding-top: 5vh;
        }
        .input-totem {
            font-size: 2.5rem !important;
            text-align: center;
            letter-spacing: 2px;
            height: 80px;
            border-radius: 15px;
            font-weight: bold;
        }
        .foto-aluno-placeholder {
            width: 120px;
            height: 120px;
            background-color: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 4px solid white;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        }
        #resultado-card {
            display: none;
            animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        /* NOVA CLASSE: Garante o respiro para o texto não ser sobreposto pela foto */
        .header-resultado {
            padding-top: 2rem;
            padding-bottom: 5rem; /* ~80px de espaço na base, a foto só sobe 60px */
            padding-left: 1rem;
            padding-right: 1rem;
        }

        @keyframes popIn {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
            <h1 class="h2 mb-0">Modo Autoatendimento (Totem)</h1>
            {{-- Mostra o turno travado na tela --}}
            <small class="text-muted">
                Turno Operacional: <strong class="text-primary">{{ Str::title(Str::lower($horarioSelecionado->nome)) }}</strong>
                ({{ \Carbon\Carbon::parse($horarioSelecionado->hora_inicio)->format('H:i') }} às {{ \Carbon\Carbon::parse($horarioSelecionado->hora_fim)->format('H:i') }})
            </small>
        </div>
        <a href="{{ route('retirada.index') }}" class="btn btn-sm btn-outline-secondary">Voltar ao Painel</a>
    </div>

    <div class="totem-container">
        {{-- CAMPO DE BUSCA / LEITURA --}}
        <div class="card shadow-sm border-primary border-opacity-50 mb-4">
            <div class="card-body p-5">
                <form id="formTotem">
                    {{-- Campo Invisível com o ID do Turno --}}
                    <input type="hidden" id="horarioId" value="{{ $horarioSelecionado->id }}">

                    <label class="form-label fw-bold text-primary w-100 text-center mb-3">Informe sua Matrícula</label>
                    <input type="text" class="form-control input-totem" id="inputMatricula" name="matricula" autocomplete="off" autofocus required maxlength="10" minlength="10" pattern="[0-9]{10}" inputmode="numeric" placeholder="Ex: 2026123456">
                </form>
                <div class="text-center mt-3 text-muted small">
                    <span class="spinner-border spinner-border-sm d-none me-1" id="loading-spinner"></span>
                    Pressione <kbd>Enter</kbd> após digitar.
                </div>
            </div>
        </div>

        {{-- CARD DE RESULTADO (Escondido por padrão) --}}
        <div class="card shadow border-0" id="resultado-card">
            <div class="card-body p-0 text-center position-relative">

                {{-- Cabeçalho de Status usando a nova classe .header-resultado --}}
                <div class="header-resultado text-white rounded-top" id="resultado-header">
                    <h2 class="fw-bold mb-0" id="resultado-mensagem">Validando...</h2>
                </div>

                {{-- Espaço para a Foto do Aluno --}}
                <div class="foto-aluno-placeholder" style="margin-top: -60px; position: relative; z-index: 2;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="#adb5bd" class="bi bi-person-fill" viewBox="0 0 16 16">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                    </svg>
                </div>

                {{-- Dados do Aluno --}}
                <div class="p-4" id="dados-aluno" style="display: none;">
                    <h3 class="fw-bold text-dark mb-1" id="aluno-nome">Nome do Aluno</h3>
                    <p class="text-muted mb-2">Matrícula: <strong id="aluno-matricula">000000</strong></p>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle px-3 py-2 text-wrap" id="aluno-curso" style="font-size: 1rem;">Nome do Curso</span>
                </div>

                {{-- Div genérica para erros sem aluno (ex: matricula não encontrada) --}}
                <div class="p-5" id="erro-generico" style="display: none;">
                    <p class="text-muted fs-5 mb-0" id="erro-descricao">Verifique o número digitado.</p>
                </div>
            </div>

            {{-- Barra de progresso de tempo para fechar automático --}}
            <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-secondary" id="timer-bar" style="width: 100%; transition: width 4s linear;"></div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formTotem');
            const inputMatricula = document.getElementById('inputMatricula');
            const spinner = document.getElementById('loading-spinner');

            const cardResultado = document.getElementById('resultado-card');
            const headerResultado = document.getElementById('resultado-header');
            const msgResultado = document.getElementById('resultado-mensagem');
            const timerBar = document.getElementById('timer-bar');

            const areaAluno = document.getElementById('dados-aluno');
            const areaErroGenerico = document.getElementById('erro-generico');

            let resetTimeout;

            // Foca no input sempre que o usuário clicar em qualquer lugar da tela
            // Ideal para totems, assim o leitor de código de barras nunca falha
            document.body.addEventListener('click', function() {
                inputMatricula.focus();
            });

            // NOVO: Bloqueio absoluto de letras e símbolos no momento do clique da tecla
            inputMatricula.addEventListener('keydown', function(e) {
                // Lista de teclas de controlo que o utilizador precisa para navegar/apagar
                const teclasPermitidas = [
                    'Backspace', 'Delete', 'Tab', 'Enter', 'Escape',
                    'ArrowLeft', 'ArrowRight', 'Home', 'End'
                ];

                // Permite o uso de atalhos como Ctrl+V (Colar), Ctrl+C (Copiar) ou Ctrl+A (Selecionar Tudo)
                if (e.ctrlKey || e.metaKey) {
                    return;
                }

                // Se a tecla pressionada NÃO for uma das permitidas e NÃO for um número de 0 a 9...
                if (!teclasPermitidas.includes(e.key) && !/^[0-9]$/.test(e.key)) {
                    e.preventDefault(); // <-- BLOQUEIA A TECLA! A letra nem chega a aparecer no campo.
                }
            });

            // Fallback (Plano B): Caso o utilizador faça "Colar" (Ctrl+V) de um texto que contenha letras
            inputMatricula.addEventListener('input', function(e) {
                this.value = this.value.replace(/\D/g, ''); // Limpa qualquer letra colada
            });

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const matricula = inputMatricula.value.trim();
                // Pega o ID que está guardado silenciosamente no HTML
                const horarioId = document.getElementById('horarioId').value;

                if (!matricula) return;

                // 1. Limpa o campo NA HORA e mantém o foco para o próximo da fila!
                inputMatricula.value = '';
                inputMatricula.focus();

                // 2. Prepara a tela (Cancela o timer antigo se alguém bipar rápido demais)
                clearTimeout(resetTimeout);
                spinner.classList.remove('d-none');

                // Dá uma leve escondida no card para dar o efeito visual de que está carregando um novo
                cardResultado.style.display = 'none';

                try {
                    const response = await fetch(`{{ route('retirada.totem.registrar') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        // Envia os dois para a base de dados
                        body: JSON.stringify({ matricula: matricula, horario_id: horarioId })
                    });

                    const data = await response.json();

                    // Configura o visual com base na resposta
                    if (data.success) {
                        // VERDE - SUCESSO
                        headerResultado.className = 'header-resultado text-white rounded-top bg-success';
                        preencherDadosAluno(data.aluno);
                    } else {
                        // VERMELHO - BLOQUEIO / ERRO
                        headerResultado.className = 'header-resultado text-white rounded-top bg-danger';

                        if (data.tipo === 'nao_encontrado') {
                            areaAluno.style.display = 'none';
                            areaErroGenerico.style.display = 'block';
                            document.getElementById('erro-descricao').innerText = 'Matrícula não localizada.';
                        } else {
                            preencherDadosAluno(data.aluno);
                        }
                    }

                    // Exibe a mensagem do servidor ("Já comeu", "Autorizado", etc)
                    msgResultado.innerText = data.message;

                    // Mostra o card com animação
                    spinner.classList.add('d-none');
                    cardResultado.style.display = 'block';

                    // Inicia a animação da barra de tempo (4 segundos)
                    timerBar.style.transition = 'none';
                    timerBar.style.width = '100%';

                    // Um pequeno atraso pro navegador entender o reinício da animação do CSS
                    setTimeout(() => {
                        timerBar.style.transition = 'width 8s linear';
                        timerBar.style.width = '0%';
                    }, 50);

                    // Reseta o Totem após 4 segundos sumindo com o card (se ninguém bipar antes)
                    resetTimeout = setTimeout(resetTotem, 8000);

                } catch (error) {
                    console.error(error);
                    spinner.classList.add('d-none');
                    alert('Erro de conexão com o servidor.');
                    resetTotem();
                }
            });

            function preencherDadosAluno(aluno) {
                areaErroGenerico.style.display = 'none';
                areaAluno.style.display = 'block';
                document.getElementById('aluno-nome').innerText = aluno.nome;
                document.getElementById('aluno-matricula').innerText = aluno.matricula;
                document.getElementById('aluno-curso').innerText = aluno.curso;
            }

            function resetTotem() {
                cardResultado.style.display = 'none';
                inputMatricula.focus();
            }
        });
    </script>
@endsection
