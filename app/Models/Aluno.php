<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    protected $fillable = [
        'matricula',
        'nome',
        'login',
        'curso_id'
    ];

    // Um aluno pertence a um curso local
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}
