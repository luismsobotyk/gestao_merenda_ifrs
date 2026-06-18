<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cardapio extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'cardapios';

    protected $fillable = [
        'nome',
        'data_inicio',
        'data_fim',
    ];

    public function horarios()
    {
        return $this->hasMany(CardapioHorario::class, 'cardapio_id');
    }

    public function excecoes()
    {
        return $this->hasMany(CardapioExcecao::class, 'cardapio_id');
    }
}
