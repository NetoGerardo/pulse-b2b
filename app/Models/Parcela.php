<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
    protected $fillable = [
        'valor',
        'proposta_id',
        'data_vencimento',
        'status',
        'numero_parcela',
        'data_pagamento',
        'data_pagamento_corretora'
    ];

    public function proposta()
    {
        return $this->belongsTo('App\Models\Proposta');
    }
}
