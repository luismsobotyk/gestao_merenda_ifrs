<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ItemContrato extends Model
{
    use HasUuids;

    protected $table = 'item_contrato';
    protected $guarded = [];
}
