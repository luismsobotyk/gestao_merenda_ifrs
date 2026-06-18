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

    public function empenhos()
    {
        return $this->hasMany(Empenho::class, 'contrato_uuid');
    }

    public function itens()
    {
        return $this->hasMany(ItemContrato::class, 'contrato_uuid');
    }

    public function salvaEmpenho(Request $request, $id)
    {
        $validated = $request->validate([
            'item_contrato_uuid' => 'required|uuid',
            'numero_empenho' => 'required|string|max:255',
            'quantidade_empenhada' => 'required|numeric|min:0.01',
            'valor_total_real' => 'required|numeric|min:0.01',
            'data_emissao' => 'nullable|date',
        ]);

        $contrato = Contrato::findOrFail($id);

        $empenho = \App\Models\Empenho::create([
            'contrato_uuid' => $contrato->id,
            'numero_empenho' => $request->numero_empenho,
            'valor_total' => $request->valor_total_real,
            'valor_utilizado' => 0,
        ]);

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
