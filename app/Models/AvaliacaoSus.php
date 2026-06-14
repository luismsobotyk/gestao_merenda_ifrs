<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvaliacaoSus extends Model
{
    protected $table = 'avaliacao_sus';

    protected $fillable = [
        'user_id',
        'ldap_username',
        'payload',
        'sus_score',
        'last_saved_at',
        'submitted_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'last_saved_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function getSubmittedAttribute(): bool
    {
        return ! is_null($this->submitted_at);
    }
}
