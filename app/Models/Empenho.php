<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Empenho extends Model
{
    use HasUuids;

    protected $table = 'empenho';
    protected $guarded = [];
}
