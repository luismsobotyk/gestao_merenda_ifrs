<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ItemEmpenho extends Model
{
    use HasUuids;

    protected $table = 'item_empenho';

    protected $guarded = [];

    public function itemContrato()
    {
        return $this->belongsTo(ItemContrato::class, 'item_contrato_uuid');
    }

    public function itensPedido()
    {
        return $this->hasMany(ItemPedido::class, 'item_empenho_uuid');
    }
}
