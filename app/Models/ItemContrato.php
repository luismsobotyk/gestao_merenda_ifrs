<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ItemContrato extends Model
{
    use HasUuids;

    protected $table = 'item_contrato';
    protected $guarded = [];

    public function itensEmpenho()
    {
        return $this->hasMany(ItemEmpenho::class, 'item_contrato_uuid');
    }
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_uuid');
    }
}
