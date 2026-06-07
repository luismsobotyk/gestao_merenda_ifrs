@extends('dashboard.layout')

@section('content')
    <div class="pt-4 pb-3 mb-4 border-bottom">
        <h1 class="h2 fw-bold text-dark mb-1">Abrir Totem</h1>
        <p class="text-muted mb-0">Selecione o turno operacional para iniciar o modo de autoatendimento.</p>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-lg-5">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm card-dash">
                <div class="card-body p-4 text-center">
                    <i class="bi bi-clock-history text-success mb-3 d-inline-block bg-success bg-opacity-10 rounded-4" style="font-size: 3rem; padding: 1rem; line-height: 1;"></i>

                    <h4 class="fw-bold mb-4">Escolha o Turno</h4>

                    {{-- O form faz um GET para a própria rota do Totem --}}
                    <form action="{{ route('retirada.totem') }}" method="GET">
                        <div class="mb-4 text-start">
                            <label for="horario_id" class="form-label fw-bold text-secondary">Turno Operacional</label>
                            <select class="form-select form-select-lg" name="horario_id" id="horario_id" required>
                                <option value="" selected disabled>Selecione um turno...</option>
                                @foreach($horarios as $horario)
                                    <option value="{{ $horario->id }}">
                                        {{ $horario->nome }}
                                        ({{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} às {{ \Carbon\Carbon::parse($horario->hora_fim)->format('H:i') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold">
                            Iniciar Totem <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
