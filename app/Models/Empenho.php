<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Empenho extends Model
{
    use HasUuids;

    protected $table = 'empenho';
    protected $guarded = [];

    public function itensEmpenho()
    {
        return $this->hasMany(ItemEmpenho::class, 'empenho_uuid');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'empenho_uuid');
    }
}
