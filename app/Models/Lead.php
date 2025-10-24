<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'container_id',
        'possui_cnpj',
        'telefone',
        'ocupacao',
        'bairro',
        'possui_plano',
        'qtd_vidas',
        'idades',
        'complemento',
        'data_envio_corretor',
        'origem_lead',
        'avaliacao',
        'data_avaliacao',
        'corretor_id',
        'status',
        'user_id',
        'status_negociacao',
        'email',
        'created_at',
        'origem_id',
        'avaliacao_sistema',
        'contactado',
        'encaminhamento_disponivel',
        'lista_id',        
    ];
}
