<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracaoRetirada;
use App\Models\Aluno;
use App\Models\Retirada;
use Carbon\Carbon;

class RetiradaController extends Controller
{
    public function index()
    {
        // Agora passamos a 'descricao' para evitar erro de campo nulo no banco
        $totemAtivo = ConfiguracaoRetirada::firstOrCreate(
                ['chave' => 'modo_totem_ativo'],
                [
                    'valor' => '1',
                    'descricao' => 'Habilita o uso do modo Autoatendimento (Totem) pelos discentes'
                ]
            )->valor === '1';

        $manualAtivo = ConfiguracaoRetirada::firstOrCreate(
                ['chave' => 'modo_manual_ativo'],
                [
                    'valor' => '1',
                    'descricao' => 'Habilita o uso do modo Lançamento Manual pelo servidor/bolsista'
                ]
            )->valor === '1';

        return view('dashboard.retirada.index', compact('totemAtivo', 'manualAtivo'));
    }

    public function modoTotem()
    {
        // Trava de segurança no backend
        if (ConfiguracaoRetirada::where('chave', 'modo_totem_ativo')->value('valor') !== '1') {
            return redirect()->route('retirada.index')->withErrors('O modo Totem está desativado no momento.');
        }
        return view('dashboard.retirada.totem');
    }
    public function registrarTotem(Request $request)
    {
        $request->validate([
            'matricula' => 'required|string'
        ]);

        // 1. Busca o aluno e traz o curso junto
        $aluno = \App\Models\Aluno::with('curso')->where('matricula', $request->matricula)->first();

        // 2. Validação: Aluno existe?
        if (!$aluno) {
            return response()->json(['success' => false, 'tipo' => 'nao_encontrado', 'message' => 'Matrícula não encontrada na base de dados.'], 404);
        }

        // Dados do aluno para retornar para a tela (ID visual)
        $dadosAluno = [
            'nome' => $aluno->nome,
            'matricula' => $aluno->matricula,
            'curso' => $aluno->curso->nome ?? 'Curso Desconhecido'
        ];

        // 3. Validação: O curso tem direito à merenda?
        if (!$aluno->curso || !$aluno->curso->direito_merenda) {
            return response()->json([
                'success' => false,
                'tipo' => 'sem_direito',
                'message' => 'O seu curso não possui direito à merenda.',
                'aluno' => $dadosAluno
            ], 403);
        }

        // 4. Validação: Já retirou hoje?
        $hoje = \Carbon\Carbon::today()->toDateString();

        // MUDANÇA AQUI: Trocamos o exists() pelo first() para pegar os dados reais do registro
        $retiradaAnterior = \App\Models\Retirada::where('aluno_id', $aluno->id)
            ->where('data_retirada', $hoje)
            ->first();

        if ($retiradaAnterior) {
            // Pegamos o horário de criação do registro no banco e formatamos para Hora:Minuto
            $horaRegistro = $retiradaAnterior->created_at->format('H:i');

            return response()->json([
                'success' => false,
                'tipo' => 'duplicado',
                // Embutimos a hora exata diretamente na mensagem!
                'message' => "Merenda já retirada hoje às {$horaRegistro}.",
                'aluno' => $dadosAluno
            ], 422);
        }

        // 5. Sucesso! Grava a retirada no banco
        \App\Models\Retirada::create([
            'aluno_id' => $aluno->id,
            'data_retirada' => $hoje
        ]);

        return response()->json([
            'success' => true,
            'tipo' => 'sucesso',
            'message' => 'Retirada autorizada e registrada!',
            'aluno' => $dadosAluno
        ]);
    }

    public function modoManual()
    {
        // Trava de segurança no backend
        if (ConfiguracaoRetirada::where('chave', 'modo_manual_ativo')->value('valor') !== '1') {
            return redirect()->route('retirada.index')->withErrors('O modo Manual está desativado no momento.');
        }
        return view('dashboard.retirada.manual');
    }

    // Método AJAX para ligar/desligar
    public function toggleModo(Request $request)
    {
        $chave = $request->modo === 'totem' ? 'modo_totem_ativo' : 'modo_manual_ativo';

        // Define a descrição com base na chave que está sendo alterada
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
