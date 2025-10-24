<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    protected $fillable = [
        'nome',
        'comissionamento',
        'imagem',
        'qtd_parcelas',
        'comissao_longo_prazo',
        'tipo_comissao_longo_prazo',
        'categoria',
        'tipo_plano'
    ];
}
