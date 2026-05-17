<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasUuids;

    protected $table = 'contrato';
    protected $guarded = [];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }

    // NOVA RELAÇÃO: Um contrato tem muitos Empenhos
    public function empenhos()
    {
        // Precisamos passar 'contrato_uuid' como 2º parâmetro porque fugimos do padrão 'contrato_id'
        return $this->hasMany(Empenho::class, 'contrato_uuid');
    }

    // NOVA RELAÇÃO: Um contrato tem muitos Itens
    public function itens()
    {
        return $this->hasMany(ItemContrato::class, 'contrato_uuid');
    }

    public function salvaEmpenho(Request $request, $id)
    {
        // 1. Validação de segurança no Backend
        $validated = $request->validate([
            'item_contrato_uuid' => 'required|uuid',
            'numero_empenho' => 'required|string|max:255',
            'quantidade_empenhada' => 'required|numeric|min:0.01',
            'valor_total_real' => 'required|numeric|min:0.01',
            'data_emissao' => 'nullable|date', // Se você adicionou a coluna no banco
        ]);

        $contrato = Contrato::findOrFail($id);

        // 2. Cria a Nota de Empenho (Pai)
        $empenho = \App\Models\Empenho::create([
            'contrato_uuid' => $contrato->id,
            'numero_empenho' => $request->numero_empenho,
            'valor_total' => $request->valor_total_real,
            'valor_utilizado' => 0, // Começa zerado, pois nenhum pedido foi feito ainda
            // 'created_at' => $request->data_emissao ?? now(), // Caso você use o created_at como data de emissão
        ]);

        // 3. Cria o Item do Empenho (Filho) e amarra a quantidade
        \App\Models\ItemEmpenho::create([
            'empenho_uuid' => $empenho->id,
            'item_contrato_uuid' => $request->item_contrato_uuid,
            'quantidade_empenhada' => $request->quantidade_empenhada,
        ]);

        return redirect()->route('contrato.visualizar', $contrato->id)->with('success', 'Nota de Empenho registrada com sucesso!');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'contrato_uuid');
    }
}
