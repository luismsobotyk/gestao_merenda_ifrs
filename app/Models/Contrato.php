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
}
