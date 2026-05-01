<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class FornecedorResponsavel extends Model
{
    use HasUuids;

    protected $table = 'fornecedor_responsavel';
    protected $guarded = [];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_uuid');
    }

    public function contatos()
    {
        return $this->hasMany(FornecedorResponsavelContato::class, 'fornecedor_responsavel_id');
    }
}
