<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CardapioItemPadrao extends Model
{
    use HasUuids;

    protected $table = 'cardapio_itens_padrao';
    protected $guarded = [];

    // Sabe a qual horário pertence
    public function horario()
    {
        return $this->belongsTo(CardapioHorario::class, 'cardapio_horario_id');
    }

    // Traz o nome e dados do alimento do contrato
    public function itemContrato()
    {
        return $this->belongsTo(ItemContrato::class, 'item_contrato_uuid');
    }
}
