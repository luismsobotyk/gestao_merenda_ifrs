<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CardapioItemPadrao extends Model
{
    use HasUuids;

    protected $table = 'cardapio_itens_padrao';
    protected $guarded = [];
    public function horario()
    {
        return $this->belongsTo(CardapioHorario::class, 'cardapio_horario_id');
    }
    public function itemContrato()
    {
        return $this->belongsTo(ItemContrato::class, 'item_contrato_uuid');
    }
}
