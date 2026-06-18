<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CardapioExcecaoItem extends Model
{
    use HasUuids;

    protected $table = 'cardapio_excecao_itens';
    protected $guarded = [];

    public function itemContrato()
    {
        return $this->belongsTo(ItemContrato::class, 'item_contrato_uuid');
    }

    public function excecao()
    {
        return $this->belongsTo(CardapioExcecao::class, 'cardapio_excecao_id');
    }
}
