<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ItemPedido extends Model
{
    use HasUuids;

    protected $table = 'item_pedido';
    protected $guarded = [];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_uuid');
    }
    public function itemEmpenho()
    {
        return $this->belongsTo(ItemEmpenho::class, 'item_empenho_uuid');
    }
}
