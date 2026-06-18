<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CardapioExcecao extends Model
{
    use HasUuids;

    protected $table = 'cardapio_excecoes';

    protected $guarded = [];

    public function cardapio()
    {
        return $this->belongsTo(Cardapio::class, 'cardapio_id');
    }

    public function horario()
    {
        return $this->belongsTo(CardapioHorario::class, 'cardapio_horario_id');
    }
    
    public function itens()
    {
        return $this->hasMany(CardapioExcecaoItem::class, 'cardapio_excecao_id');
    }

    protected static function booted()
    {
        static::deleting(function ($excecao) {
            $excecao->itens()->delete();
        });
    }

}
