<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CardapioHorario extends Model
{
    use HasUuids;

    protected $table = 'cardapio_horarios';
    protected $guarded = [];

    public function cardapio()
    {
        return $this->belongsTo(Cardapio::class, 'cardapio_id');
    }

    public function itensPadrao()
    {
        return $this->hasMany(CardapioItemPadrao::class, 'cardapio_horario_id');
    }

    public function excecoes()
    {
        return $this->hasMany(CardapioExcecao::class, 'cardapio_horario_id');
    }

    protected static function booted()
    {
        static::deleting(function ($horario) {
            $horario->itensPadrao()->delete();
        });
    }
}
