<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitacaoLista extends Model
{

    protected $table = 'solicitacao_lista';

    protected $fillable = [
        'qtd_contatos',
        'data_envio',
        'justificativa_rejeicao',
        'corretor_id',
        'lista_id',
        'status',
        'data_ultima_lista',
        'qtd_ultima_lista'
    ];
}
