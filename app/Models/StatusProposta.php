<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusProposta extends Model
{
    protected $table = 'status_proposta';

    protected $fillable = [
        'nome',
    ];
}

