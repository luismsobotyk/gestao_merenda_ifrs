<?php

namespace App\Http\Controllers;

use App\Models\AvaliacaoSus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AvaliacaoSusController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ldapUsername = $this->ldapUsername($user);

        $avaliacao = AvaliacaoSus::firstOrCreate(
            ['ldap_username' => $ldapUsername],
            [
                'user_id' => $user->id ?? null,
                'payload' => $this->payloadInicial(),
                'last_saved_at' => now(),
            ]
        );

        $avaliacao->payload = $this->normalizarPayload($avaliacao->payload);

        return view('avaliacao.avaliacao', [
            'avaliacao' => $avaliacao,
            'isAdmin' => $this->isAdmin($user),
        ]);
    }

    public function salvar(Request $request)
    {
        $user = Auth::user();
        $avaliacao = $this->avaliacaoDoUsuario($user);

        if ($avaliacao->submitted_at) {
            return response()->json([
                'message' => 'Esta avaliação já foi submetida e não pode mais ser alterada.',
            ], 423);
        }

        $data = $request->validate([
            'payload' => ['required', 'array'],
        ]);

        $payloadAtual = $this->normalizarPayload($avaliacao->payload);
        $payloadRecebido = $data['payload'];

        // Salvar pela tela do participante não pode apagar identificação/tarefas preenchidas pelo moderador.
        $payloadAtual['sus'] = $payloadRecebido['sus'] ?? $payloadAtual['sus'];
        $payloadAtual['qualitativo'] = $payloadRecebido['qualitativo'] ?? $payloadAtual['qualitativo'];
        $payloadAtual['sus']['score'] = $this->calcularSusScore($payloadAtual['sus']['respostas'] ?? []);

        $avaliacao->update([
            'payload' => $payloadAtual,
            'sus_score' => $payloadAtual['sus']['score'],
            'last_saved_at' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'last_saved_at' => $avaliacao->fresh()->last_saved_at?->format('d/m/Y H:i:s'),
            'sus_score' => $payloadAtual['sus']['score'],
        ]);
    }

    public function submeter(Request $request)
    {
        $user = Auth::user();
        $avaliacao = $this->avaliacaoDoUsuario($user);

        if ($avaliacao->submitted_at) {
            return response()->json([
                'message' => 'Esta avaliação já foi submetida.',
            ], 423);
        }

        $data = $request->validate([
            'payload' => ['required', 'array'],
        ]);

        $payloadAtual = $this->normalizarPayload($avaliacao->payload);
        $payloadRecebido = $data['payload'];

        $payloadAtual['sus'] = $payloadRecebido['sus'] ?? $payloadAtual['sus'];
        $payloadAtual['qualitativo'] = $payloadRecebido['qualitativo'] ?? $payloadAtual['qualitativo'];

        $this->validarFormularioCompleto($payloadAtual);

        $payloadAtual['sus']['score'] = $this->calcularSusScore($payloadAtual['sus']['respostas'] ?? []);

        $avaliacao->update([
            'payload' => $payloadAtual,
            'sus_score' => $payloadAtual['sus']['score'],
            'last_saved_at' => now(),
            'submitted_at' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Avaliação submetida com sucesso.',
            'submitted_at' => $avaliacao->fresh()->submitted_at?->format('d/m/Y H:i:s'),
            'sus_score' => $payloadAtual['sus']['score'],
        ]);
    }

    public function respostas()
    {
        abort_unless($this->isAdmin(Auth::user()), 403);

        $avaliacoes = AvaliacaoSus::query()
            ->orderByRaw('submitted_at IS NULL')
            ->orderByDesc('submitted_at')
            ->orderByDesc('last_saved_at')
            ->get();

        $sessions = $avaliacoes->map(function (AvaliacaoSus $avaliacao) {
            $payload = $this->normalizarPayload($avaliacao->payload);

            return [
                'id' => $avaliacao->id,
                'ldap_username' => $avaliacao->ldap_username,
                'sus_score' => $avaliacao->sus_score ?? ($payload['sus']['score'] ?? null),
                'submitted_at' => optional($avaliacao->submitted_at)->format('d/m/Y H:i'),
                'last_saved_at' => optional($avaliacao->last_saved_at)->format('d/m/Y H:i'),
                'status' => $avaliacao->submitted_at ? 'Submetida' : 'Rascunho',
                'payload' => $payload,
                'moderacao_url' => route('avaliacao.moderacao', $avaliacao),
            ];
        })->values();

        return view('avaliacao.respostas', [
            'avaliacoes' => $avaliacoes,
            'sessions' => $sessions,
        ]);
    }

    public function moderacao(AvaliacaoSus $avaliacao)
    {
        abort_unless($this->isAdmin(Auth::user()), 403);

        $avaliacao->payload = $this->normalizarPayload($avaliacao->payload);

        return view('avaliacao.moderacao', [
            'avaliacao' => $avaliacao,
            'payload' => $avaliacao->payload,
        ]);
    }

    public function salvarModeracao(Request $request, AvaliacaoSus $avaliacao)
    {
        abort_unless($this->isAdmin(Auth::user()), 403);

        $data = $request->validate([
            'payload' => ['required', 'array'],
            'payload.participante' => ['nullable', 'array'],
            'payload.tarefas' => ['nullable', 'array'],
        ]);

        $payloadAtual = $this->normalizarPayload($avaliacao->payload);
        $payloadRecebido = $data['payload'];

        // Salvar pela tela do moderador não pode apagar SUS/qualitativo preenchidos pelo participante.
        $payloadAtual['participante'] = $payloadRecebido['participante'] ?? $payloadAtual['participante'];
        $payloadAtual['tarefas'] = $payloadRecebido['tarefas'] ?? $payloadAtual['tarefas'];

        $avaliacao->update([
            'payload' => $payloadAtual,
            'last_saved_at' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'last_saved_at' => $avaliacao->fresh()->last_saved_at?->format('d/m/Y H:i:s'),
        ]);
    }

    private function avaliacaoDoUsuario($user): AvaliacaoSus
    {
        return AvaliacaoSus::firstOrCreate(
            ['ldap_username' => $this->ldapUsername($user)],
            [
                'user_id' => $user->id ?? null,
                'payload' => $this->payloadInicial(),
                'last_saved_at' => now(),
            ]
        );
    }

    private function ldapUsername($user): string
    {
        $valor = $this->primeiroValorValido([
            $user->username ?? null,
            $user->login ?? null,
            $user->samaccountname ?? null,
            $user->samAccountName ?? null,
            $user->uid ?? null,
            $user->email ?? null,
        ]);

        if (! $valor) {
            abort(500, 'Não foi possível identificar o usuário autenticado para vincular a avaliação.');
        }

        $valor = strtolower(trim((string) $valor));

        if (str_contains($valor, '@')) {
            $valor = Str::before($valor, '@');
        }

        return $valor;
    }

    private function primeiroValorValido(array $valores): ?string
    {
        foreach ($valores as $valor) {
            if (is_array($valor)) {
                $valor = $valor[0] ?? null;
            }

            if (is_object($valor) && method_exists($valor, '__toString')) {
                $valor = (string) $valor;
            }

            if (is_string($valor) && trim($valor) !== '') {
                return $valor;
            }
        }

        return null;
    }

    private function isAdmin($user): bool
    {
        return \App\Models\User::isSuperAdmin($user);
    }

    private function calcularSusScore(array $respostas): ?int
    {
        if (count($respostas) !== 10) {
            return null;
        }

        $sum = 0;

        foreach ($respostas as $i => $valor) {
            $valor = (int) $valor;

            if ($valor < 1 || $valor > 5) {
                return null;
            }

            $sum += $i % 2 === 0
                ? $valor - 1
                : 5 - $valor;
        }

        return (int) round($sum * 2.5);
    }

    private function validarFormularioCompleto(array $payload): void
    {
        $rules = [
            'sus.respostas' => ['required', 'array', 'size:10'],
            'sus.respostas.*' => ['required', 'integer', 'between:1,5'],

            'qualitativo.q1' => ['required', 'string'],
            'qualitativo.q2' => ['required', 'string'],
            'qualitativo.q3' => ['required', 'string'],
            'qualitativo.q4' => ['required', 'string'],
            'qualitativo.q5' => ['required', 'string'],
            'qualitativo.q6' => ['required', 'string'],
            'qualitativo.q7' => ['required', 'string'],
            'qualitativo.q8' => ['required', 'string'],
            'qualitativo.q9' => ['required', 'string'],
            'qualitativo.q10' => ['required', 'string'],
            'qualitativo.q11' => ['required', 'string'],
        ];

        Validator::make($payload, $rules, [
            'required' => 'O campo :attribute é obrigatório.',
            'sus.respostas.size' => 'Todas as respostas SUS devem ser preenchidas.',
            'sus.respostas.*.between' => 'A resposta SUS deve estar entre 1 e 5.',
        ])->validate();
    }

    private function normalizarPayload(?array $payload): array
    {
        return array_replace_recursive($this->payloadInicial(), $payload ?? []);
    }

    private function payloadInicial(): array
    {
        return [
            'participante' => [
                'codigo' => '',
                'data' => now()->format('Y-m-d'),
                'hora' => now()->format('H:i'),
                'moderador' => '',
                'perfil' => '',
                'idade' => '',
                'experiencia' => '',
                'uso_prev' => '',
                'obs' => '',
            ],
            'tarefas' => [
                'resultados' => [],
                'obs' => '',
            ],
            'sus' => [
                'respostas' => array_fill(0, 10, 0),
                'score' => null,
            ],
            'qualitativo' => collect(range(1, 11))
                ->mapWithKeys(fn ($i) => ["q{$i}" => ''])
                ->toArray(),
        ];
    }
}
