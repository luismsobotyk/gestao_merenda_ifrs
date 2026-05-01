<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class FornecedorResponsavelContato extends Model
{
    use HasUuids;

    protected $table = 'fornecedor_responsavel_contato';
    protected $guarded = [];

    public function responsavel()
    {
        return $this->belongsTo(FornecedorResponsavel::class, 'fornecedor_responsavel_id');
    }
}
