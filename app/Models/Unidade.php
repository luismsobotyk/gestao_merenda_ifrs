<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    use HasUuids;

    protected $table = 'unidade';

    protected $guarded = [];

    public function itensContrato()
    {
        return $this->hasMany(ItemContrato::class, 'unidade_uuid');
    }
}
