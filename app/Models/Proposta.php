<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class Proposta extends Model
{
    protected $fillable = [
        'nome_titular',
        'whatsapp',
        'valor_proposta',
        'pdf_proposta',
        'comprovante_residencia',
        'comprovante_vinculo',
        'plano_id',
        'data_vencimento',
        'contato_id',
        'status',
        'proposta_id',
        'descricao_pendencia',
        'tipo_proposta',
        'documento_responsavel',
        'contrato_social',
        'cnpj',
        'identidade_titular',
        'cpf_titular',
        'cpf',
        'identidade',
        'tipo_empresa',
        'certidao_casamento',
        'tutela',
        'sobrinhos',
        'cpf_titular_sobrinhos',
        'dependentes',
        'descricao_pendencia',
        'empregados',
        'esocial',
        'parcelas_recebidas',
        'vidas'
    ];

    public function parcelas()
    {
        return $this->hasMany('App\Models\Parcela');
    }
}
