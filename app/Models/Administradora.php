<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administradora extends Model
{
    protected $table = 'administradoras';

    protected $fillable = [
        'nome',
    ];
}
