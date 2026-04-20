<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    use HasUuids;

    protected $table = 'fornecedor';
    protected $guarded = [];
}
