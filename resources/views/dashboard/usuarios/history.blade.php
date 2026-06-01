@extends('dashboard.layout')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-0">Histórico de Acesso</h1>
            <small class="text-muted">A analisar registos de: <strong class="text-primary">{{ $user->name }}</strong> ({{ $user->username }})</small>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light text-secondary">
                    <tr>
                        <th class="ps-4">Data</th>
                        <th>Hora</th>
                        <th>Endereço IP</th>
                        <th class="pe-4">Dispositivo (User Agent)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($user->loginHistories as $historico)
                        <tr>
                            <td class="ps-4 fw-bold">{{ \Carbon\Carbon::parse($historico->login_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($historico->login_time)->format('H:i:s') }}</td>
                            <td><span class="badge bg-info text-dark">{{ $historico->ip_address }}</span></td>
                            <td class="pe-4 text-muted small" style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $historico->user_agent }}">
                                {{ $historico->user_agent ?? 'Desconhecido' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="bi bi-slash-circle me-1"></i> Nenhum acesso registado para este utilizador.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
