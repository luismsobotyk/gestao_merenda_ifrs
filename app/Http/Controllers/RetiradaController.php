<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracaoRetirada;
use App\Models\Aluno;
use App\Models\Retirada;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RetiradaController extends Controller
{
    public function index()
    {
        $totemAtivo = ConfiguracaoRetirada::firstOrCreate(['chave' => 'modo_totem_ativo'], ['valor' => '1', 'descricao' => 'Habilita o uso do modo Autoatendimento'])->valor === '1';
        $manualAtivo = ConfiguracaoRetirada::firstOrCreate(['chave' => 'modo_manual_ativo'], ['valor' => '1', 'descricao' => 'Habilita o uso do modo Lançamento Manual'])->valor === '1';

        $hoje = Carbon::today()->toDateString();
        $diaSemana = Carbon::today()->dayOfWeekIso;

        $cardapioAtivo = \DB::table('cardapios')
            ->where('data_inicio', '<=', $hoje)
            ->where('data_fim', '>=', $hoje)
            ->first();

        $temCardapioHoje = false;
        $horarios = collect();

        if ($cardapioAtivo) {
            $horarios = \DB::table('cardapio_horarios')
                ->where('cardapio_id', $cardapioAtivo->id)
                ->orderBy('hora_inicio')
                ->get();

            $temPadrao = \DB::table('cardapio_itens_padrao')
                ->join('cardapio_horarios', 'cardapio_itens_padrao.cardapio_horario_id', '=', 'cardapio_horarios.id')
                ->where('cardapio_horarios.cardapio_id', $cardapioAtivo->id)
                ->where('cardapio_itens_padrao.dia_semana', $diaSemana)
                ->exists();

            $temExcecao = \DB::table('cardapio_excecoes')
                ->where('cardapio_id', $cardapioAtivo->id)
                ->where('data_exata', $hoje)
                ->exists();

            if ($temPadrao || $temExcecao) {
                $temCardapioHoje = true;
            }
        }

        if ($horarios->isEmpty()) {
            $ultimoCardapio = \DB::table('cardapios')->orderBy('data_fim', 'desc')->first();
            if ($ultimoCardapio) {
                $horarios = \DB::table('cardapio_horarios')->where('cardapio_id', $ultimoCardapio->id)->orderBy('hora_inicio')->get();
            } else {
                $horarios = \DB::table('cardapio_horarios')->orderBy('hora_inicio')->get();
            }
        }

        return view('dashboard.retirada.index', compact('totemAtivo', 'manualAtivo', 'horarios', 'temCardapioHoje'));
    }

    public function modoTotem(Request $request)
    {
        if (!$request->has('horario_id')) {
            $horarios = DB::table('cardapio_horarios')->orderBy('hora_inicio')->get();
            return view('dashboard.retirada.selecionar_turno', compact('horarios'));
        }

        $horarioSelecionado = DB::table('cardapio_horarios')
            ->where('id', $request->horario_id)
            ->first();

        if (!$horarioSelecionado) {
            return redirect()->route('retirada.totem')->withErrors(['Turno não encontrado.']);
        }

        return view('dashboard.retirada.totem', compact('horarioSelecionado'));
    }
    public function registrarTotem(Request $request)
    {
        $request->validate([
            'matricula' => 'required|string',
            'horario_id' => 'required|uuid' // <-- VALIDAÇÃO REINSERIDA AQUI
        ]);

        $aluno = Aluno::with('curso')->where('matricula', $request->matricula)->first();

        if (!$aluno) {
            return response()->json(['success' => false, 'tipo' => 'nao_encontrado', 'message' => 'Matrícula não encontrada na base de dados.'], 404);
        }

        $dadosAluno = [
            'nome' => $aluno->nome,
            'matricula' => $aluno->matricula,
            'curso' => $aluno->curso->nome ?? 'Curso Desconhecido'
        ];

        if (!$aluno->curso || !$aluno->curso->direito_merenda) {
            return response()->json(['success' => false, 'tipo' => 'sem_direito', 'message' => 'O seu curso não possui direito à merenda.', 'aluno' => $dadosAluno], 403);
        }

        $hoje = Carbon::today()->toDateString();

        $retiradaAnterior = Retirada::where('aluno_id', $aluno->id)
            ->where('data_retirada', $hoje)
            ->first();

        if ($retiradaAnterior) {
            $horaRegistro = $retiradaAnterior->created_at->format('H:i');
            return response()->json(['success' => false, 'tipo' => 'duplicado', 'message' => "Merenda já retirada hoje às {$horaRegistro}.", 'aluno' => $dadosAluno], 422);
        }

        Retirada::create([
            'aluno_id' => $aluno->id,
            'data_retirada' => $hoje,
            'cardapio_horario_id' => $request->horario_id
        ]);

        return response()->json(['success' => true, 'tipo' => 'sucesso', 'message' => 'Retirada autorizada e registrada!', 'aluno' => $dadosAluno]);
    }

    public function modoManual()
    {
        if (ConfiguracaoRetirada::where('chave', 'modo_manual_ativo')->value('valor') !== '1') {
            return redirect()->route('retirada.index')->withErrors('O modo Manual está desativado no momento.');
        }
        return view('dashboard.retirada.manual');
    }

    public function toggleModo(Request $request)
    {
        $chave = $request->modo === 'totem' ? 'modo_totem_ativo' : 'modo_manual_ativo';

        $descricao = $request->modo === 'totem'
            ? 'Habilita o uso do modo Autoatendimento (Totem) pelos discentes'
            : 'Habilita o uso do modo Lançamento Manual pelo servidor/bolsista';

        ConfiguracaoRetirada::updateOrCreate(
            ['chave' => $chave],
            [
                'valor' => $request->ativo ? '1' : '0',
                'descricao' => $descricao
            ]
        );

        return response()->json(['success' => true]);
    }
}
