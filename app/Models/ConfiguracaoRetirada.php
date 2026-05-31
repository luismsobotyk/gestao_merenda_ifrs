<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoRetirada extends Model
{
    // Aponta explicitamente para o novo nome da tabela
    protected $table = 'configuracoes_retirada';

    // Libera os 3 campos para inserção em massa
    protected $fillable = [
        'chave',
        'descricao',
        'valor'
    ];
}
