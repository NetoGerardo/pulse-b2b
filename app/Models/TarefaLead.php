<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TarefaLead extends Model
{

    use SoftDeletes;

    protected $table = 'tarefas_leads';

    protected $fillable = [
        'tarefa_id',
        'contato_id',
        'expira_em',
        'anotacoes',
        'concluida',
        'concluida_em',
        'motivo_deletar',
    ];

    public function tarefa()
    {
        return $this->belongsTo('App\Models\Tarefa', 'tarefa_id');
    }

    public function contato()
    {
        return $this->belongsTo('App\Models\Contato', 'contato_id');
    }
}
