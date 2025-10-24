<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contato;
use App\Models\Lead;
use App\Models\Origem;
use App\Models\Proposta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LeadsController extends Controller
{

    public function encaminhar(Request $request)
    {

        $lead = Lead::find($request->lead_id);
        $lead->status = 'ENCAMINHADO';
        $lead->user_id = $request->corretor_id;
        $lead->data_envio_corretor = Carbon::now();

        //Caso o lead venha do site, vai direto para o kanban        
        //if ($lead->origem_lead == "Google Oficial") {
        $lead->avaliacao = "POSITIVA";
        $lead->data_avaliacao  = Carbon::now();
        $lead->data_envio_corretor  = Carbon::now();

        $contato = Contato::create([
            'nome' => $lead->nome,
            'telefone' => $lead->telefone,
            'status' => "Indicação Cadastrada",
            'possui_cnpj' => $lead->possui_cnpj,
            'ocupacao' => $lead->ocupacao,
            'bairro' => $lead->bairro,
            'possui_plano' => $lead->possui_plano,
            'qtd_vidas' => $lead->qtd_vidas,
            'complemento' => $lead->complemento,
            'origem' => $lead->origem_lead,
            'origem_id' => $lead->origem_id,
            'user_id' => $lead->user_id
        ]);
        //}

        if ($request->enviar_apenas_documentacao) {

            $proposta = Proposta::create([
                'nome_titular' => $contato->nome,
                'status' => "A cadastrar",
            ]);

            $contato = Contato::find($contato->id);
            $contato->proposta_id = $proposta->id;
            $contato->status = "A cadastrar";
            $contato->save();
        }

        $lead->save();

        return response()->json([
            "success" => true,
            "msg" => 'Lead encaminhado!',
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {

        $origem = Origem::find($request->origem_id);

        if (!$origem) {
            $origem =  Origem::find(1);
        }

        Lead::create([
            'nome' => $request->nome,
            'possui_cnpj' => $request->possui_cnpj,
            'telefone' => $request->telefone,
            'status' => "VALIDADO",
            'ocupacao' => $request->ocupacao,
            'bairro' => $request->bairro,
            'possui_plano' => $request->possui_plano,
            'qtd_vidas' => $request->qtd_vidas,
            'complemento' => $request->complemento,
            'origem_lead' => $origem->nome,
            'origem_id' => $origem->id
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Lead cadastrado!',
        ], Response::HTTP_OK);
    }
}
