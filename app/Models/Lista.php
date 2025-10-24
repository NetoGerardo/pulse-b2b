<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    protected $fillable = [
        'nome',
        'numeros_disponiveis'
    ];

    public function contatos()
    {
        return $this->belongsToMany('App\Models\Contato');
    }
}
