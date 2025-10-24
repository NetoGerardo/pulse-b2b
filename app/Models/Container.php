<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Container extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'numero_conectado',
        'mensagem_izap',
        'chave_api',
        'url',
        'user_id',
        'status_usuario'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
