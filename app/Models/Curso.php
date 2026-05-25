<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'cursos';

    protected $fillable = [
        'id_curso',
        'codigo',
        'nome',
        'nivel',
        'turno',
        'direito_merenda',
    ];

    protected $casts = [
        'direito_merenda' => 'boolean',
    ];
}
