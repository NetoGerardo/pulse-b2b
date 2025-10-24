<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contato extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'possui_cnpj',
        'telefone',
        'ocupacao',
        'bairro',
        'possui_plano',
        'qtd_vidas',
        'idades',
        'complemento',
        'data_envio_corretor',
        'origem',
        'observacao',
        'avaliacao',
        'data_avaliacao',
        'corretor_id',
        'status',
        'user_id',
        'email',
        'created_at',
        'origem_id',
        'avaliacao_sistema',
        'observacoes_admin',
        'observacoes_corretor',
        'justificativa_negociacao_encerrada',
        'aberto_em'
    ];

    public function proposta()
    {
        return $this->hasOne('App\Models\Proposta');
    }

    public function tarefas()
    {
        return $this->hasMany('App\Models\TarefaLead', 'contato_id');
    }

      public function origemLead()
    {
        return $this->belongsTo('App\Models\Origem', 'origem_id');
    }
}
