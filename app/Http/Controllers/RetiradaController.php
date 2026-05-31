<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracaoRetirada;

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
