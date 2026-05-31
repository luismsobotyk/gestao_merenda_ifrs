<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retirada extends Model
{
    protected $fillable = [
        'aluno_id',
        'data_retirada',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
}
