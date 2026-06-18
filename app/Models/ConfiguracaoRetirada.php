<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoRetirada extends Model
{
    protected $table = 'configuracoes_retirada';

    protected $fillable = [
        'chave',
        'descricao',
        'valor'
    ];
}
