<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Contato;
use App\Models\ContatoUser;
use App\Models\Lead;
use App\Models\Proposta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PropostaController extends Controller
{
    public function new(Request $request)
    {

        $proposta = Proposta::create([
            'status' => "New",
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Status atualizado!',
            "id" => $proposta->id
        ], Response::HTTP_OK);
    }


    public function updateDependentes(Request $request)
    {
        $proposta = Proposta::find($request->id);
        $proposta->vidas = json_encode($request->vidas);
        $proposta->save();

        return response()->json([
            "success" => true,
            "msg" => 'Proposta atualizada!',
        ], Response::HTTP_OK);
    }

    public function updateEmpregados(Request $request)
    {
        $proposta = Proposta::find($request->id);
        $proposta->empregados = json_encode($request->empregados);
        $proposta->save();

        return response()->json([
            "success" => true,
            "msg" => 'Proposta atualizada!',
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {

        $proposta = Proposta::find($request->id);

        //CASO HAJA EMPREGADOS
        if ($request->qtd_empregados > 0) {
            $proposta->empregados = json_encode($request->empregados);
        }

        //CASO HAJA VIDAS
        if ($request->qtd_vidas > 0) {
            $proposta->vidas = json_encode($request->vidas);
        }

        $proposta->cpf = $request->cpf;
        $proposta->cnpj = $request->cnpj;
        $proposta->identidade = $request->identidade;
        $proposta->qtd_empregados = $request->qtd_empregados;
        $proposta->tipo_empresa = $request->tipo_empresa;
        $proposta->qtd_vidas = $request->qtd_vidas;
        $proposta->nome_titular = $request->nome_titular;
        $proposta->whatsapp = $request->whatsapp;
        $proposta->cpf_titular = $request->cpf_titular;
        $proposta->tipo_proposta = $request->tipo_proposta;
        $proposta->contrato_social = $request->contrato_social;
        $proposta->identidade_titular = $request->identidade_titular;
        $proposta->valor_proposta = $request->valor_proposta;
        $proposta->comprovante_residencia = $request->comprovante_residencia;
        $proposta->comprovante_vinculo = $request->comprovante_vinculo;
        $proposta->plano_id = $request->plano_id;
        $proposta->data_vencimento = $request->data_vencimento;
        $proposta->contato_id = $request->contato_id;
        $proposta->status = "A cadastrar";
        $proposta->save();

        $contato = Contato::find($request->contato_id);
        $contato->proposta_id = $proposta->id;
        $contato->status = "A cadastrar";
        $contato->save();

        return response()->json([
            "success" => true,
            "msg" => 'Proposta cadastrada!',
            "id" => $proposta->id
        ], Response::HTTP_OK);
    }

    public function reenviar(Request $request)
    {

        $proposta = Proposta::find($request->id);

        $proposta->cpf = $request->cpf;
        $proposta->cnpj = $request->cnpj;
        $proposta->identidade = $request->identidade;
        $proposta->qtd_empregados = $request->qtd_empregados;
        $proposta->tipo_empresa = $request->tipo_empresa;
        $proposta->qtd_vidas = $request->qtd_vidas;
        $proposta->nome_titular = $request->nome_titular;
        $proposta->whatsapp = $request->whatsapp;
        $proposta->tipo_proposta = $request->tipo_proposta;
        $proposta->valor_proposta = $request->valor_proposta;
        $proposta->plano_id = $request->plano_id;
        $proposta->data_vencimento = $request->data_vencimento;
        $proposta->status = "A cadastrar";

        //CASO VENHA COMPROVANTE DE RESIDÊNCIA
        if ($request->comprovante_residencia) {
            $proposta->comprovante_residencia = $request->comprovante_residencia;
        }

        //CASO VENHA CPF DO TITULAR
        if ($request->cpf_titular) {
            $proposta->cpf_titular = $request->cpf_titular;
        }

        //CASO VENHA IDENTIDADE DO TITULAR
        if ($request->identidade_titular) {
            $proposta->identidade_titular = $request->identidade_titular;
        }

        //CASO VENHA COMPROVANTE DE VÍNCULO
        if ($request->comprovante_vinculo) {
            $proposta->comprovante_vinculo = $request->comprovante_vinculo;
        }

        $proposta->save();

        $contato = Contato::find($request->contato_id);
        $contato->status = "A cadastrar";
        $contato->save();

        return response()->json([
            "success" => true,
            "msg" => 'Proposta cadastrada!',
            "id" => $proposta->id
        ], Response::HTTP_OK);
    }
}
