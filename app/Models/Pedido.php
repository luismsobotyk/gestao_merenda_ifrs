<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasUuids;

    protected $table = 'pedido';
    protected $guarded = [];

    public function itensPedido()
    {
        return $this->hasMany(ItemPedido::class, 'pedido_uuid');
    }
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_uuid');
    }
}
