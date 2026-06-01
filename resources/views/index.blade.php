<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME', 'IFRS') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

<nav class="navbar navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ url('/') }}">
            Cardápio de Merenda
        </a>

        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
            Login
        </a>
    </div>
</nav>

<main class="container py-5">

    <div class="text-center mb-4">
        <h1 class="fw-bold mb-2">Cardápio de Merenda</h1>
        <p class="text-muted mb-0">
            Instituto Federal do Rio Grande do Sul
        </p>
    </div>

    @if($dias->isEmpty())
        <div class="alert alert-warning text-center">
            Nenhum cardápio encontrado.
        </div>
    @else

        <div id="cardapioCarousel" class="carousel slide" data-bs-ride="false">

            <div class="carousel-inner">

                @foreach($dias as $index => $dia)
                    <div class="carousel-item {{ $index === $indiceAtivo ? 'active' : '' }}">
                        <div class="row justify-content-center">
                            <div class="col-12 col-lg-8">

                                <div class="card border-0 shadow-sm rounded-4">
                                    <div class="card-body p-4 p-md-5">

                                        <div class="text-center mb-4">
                                            @if($dia['eh_hoje'])
                                                <span class="badge text-bg-success mb-2">
                                                    Hoje
                                                </span>
                                            @elseif($index === $indiceAtivo)
                                                <span class="badge text-bg-primary mb-2">
                                                    Próximo cardápio disponível
                                                </span>
                                            @endif

                                            <h2 class="fw-bold mb-1">
                                                {{ $dia['nome_dia'] }}
                                            </h2>

                                            <p class="text-muted mb-0">
                                                {{ $dia['data']->format('d/m/Y') }}
                                            </p>
                                        </div>

                                        @if(!$dia['possui_cardapio'])
                                            <div class="alert alert-secondary text-center mb-0">
                                                Não há cardápio cadastrado para esta data.
                                            </div>
                                        @else

                                            @foreach($dia['horarios'] as $horario)
                                                <div class="mb-4">
                                                    <div class="d-flex justify-content-between align-items-start border-bottom pb-2 mb-3">
                                                        <div>
                                                            <h5 class="fw-semibold mb-1">
                                                                {{ $horario['nome'] }}
                                                            </h5>

                                                            @if($horario['descricao_publico'])
                                                                <div class="text-muted small">
                                                                    {{ $horario['descricao_publico'] }}
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="text-end text-muted small">
                                                            {{ substr($horario['hora_inicio'], 0, 5) }}
                                                            às
                                                            {{ substr($horario['hora_fim'], 0, 5) }}
                                                        </div>
                                                    </div>

                                                    <div class="row g-2">
                                                        @foreach($horario['itens'] as $item)
                                                            <div class="col-12 col-md-6">
                                                                <div class="p-3 bg-light rounded-3 h-100">
                                                                    <div class="fw-semibold">
                                                                        {{ $item['nome'] }}
                                                                    </div>

                                                                    @if($item['quantidade_estimada_porcao'])
                                                                        <div class="text-muted small">
                                                                            Porção estimada:
                                                                            {{ number_format($item['quantidade_estimada_porcao'], 2, ',', '.') }}
                                                                        </div>
                                                                    @endif

                                                                    @if($item['origem'] !== 'padrao')
                                                                        <span class="badge text-bg-warning mt-2">
                                                                            Exceção: {{ ucfirst($item['origem']) }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach

                                        @endif

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#cardapioCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#cardapioCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                <span class="visually-hidden">Próximo</span>
            </button>

        </div>

        <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
            @foreach($dias as $index => $dia)
                <button
                    type="button"
                    data-bs-target="#cardapioCarousel"
                    data-bs-slide-to="{{ $index }}"
                    class="btn btn-sm {{ $index === $indiceAtivo ? 'btn-success' : 'btn-outline-secondary' }}"
                    aria-current="{{ $index === $indiceAtivo ? 'true' : 'false' }}"
                    aria-label="Ver cardápio de {{ $dia['data']->format('d/m/Y') }}"
                >
                    {{ $dia['data']->format('d/m') }}

                    @if($dia['eh_hoje'])
                        · Hoje
                    @endif
                </button>
            @endforeach
        </div>

    @endif

</main>

<footer class="py-4 text-center text-muted small">
    IFRS · Cardápio de Merenda
</footer>

</body>
</html>
