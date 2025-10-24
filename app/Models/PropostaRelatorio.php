<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropostaRelatorio extends Model
{
    protected $table = 'proposta_relatorio';

    protected $fillable = [
        'data_envio_documentacao',
        'nome_titular',
        'cpf_titular',
        'telefone_titular',
        'qtd_vidas',
        'administradora_id',
        'operadora_id',
        'produto_id',
        'status_id',
        'corretor_id',
        'supervisor_id',
        'observacoes',
        'tipo_produto_id',
        'parcela_1',
        'parcela_2',
        'parcela_3',
        'data_pagamento_1',
        'data_pagamento_2',
        'data_pagamento_3',
        'data_repasse_1',
        'data_repasse_2',
        'data_repasse_3',
    ];
}
