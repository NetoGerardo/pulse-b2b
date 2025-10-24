<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Contato;
use App\Models\Lead;
use App\Models\Origem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LeadsController extends Controller
{

    public function atualizarContactado(Request $request)
    {

        $lead = Lead::find($request->lead_id);
        $lead->contactado = 1;
        $lead->save();

        return response()->json([
            "success" => true,
            "msg" => 'Status atualizado!',
        ], Response::HTTP_OK);
    }

    public function atualizarStatus(Request $request)
    {

        $lead = Lead::find($request->lead_id);
        $lead->status_negociacao = $request->status;
        $lead->save();

        return response()->json([
            "success" => true,
            "msg" => 'Status atualizado!',
        ], Response::HTTP_OK);
    }

    public function avaliar(Request $request)
    {

        $lead = Lead::find($request->lead_id);
        $lead->avaliacao = $request->avaliacao;
        $lead->data_avaliacao  = Carbon::now();
        $lead->status_negociacao = $request->status_negociacao;
        $lead->save();

        if ($request->avaliacao == "POSITIVA") {
            Contato::create([
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
        }

        return response()->json([
            "success" => true,
            "msg" => 'Lead avaliado!',
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {

        $origem = Origem::find($request->origem_id);

        if (!$origem) {
            $origem =  Origem::find(1);
        }

        $lead = Lead::create([
            'nome' => $request->nome,
            'possui_cnpj' => $request->possui_cnpj,
            'telefone' => $request->telefone,
            'status' => "VALIDADO",
            'ocupacao' => $request->ocupacao,
            'bairro' => $request->bairro,
            'possui_plano' => $request->possui_plano,
            'qtd_vidas' => $request->qtd_vidas,
            'complemento' => $request->complemento,
            'user_id' => Auth::user()->id,
            'origem_lead' => $origem->nome,
            'origem_id' => $origem->id,
            'avaliacao' => "POSITIVA"
        ]);

        Contato::create([
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
            'user_id' => Auth::user()->id
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Lead cadastrado!',
        ], Response::HTTP_OK);
    }



    public function search_lista(Request $request)
    {

        $query = Lead::whereUserId(Auth::user()->id)->whereNull('avaliacao');

        if ($request->search) {
            $query->where(function ($query) use ($request) {
                $query->where('leads.nome', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('leads.telefone', 'LIKE', '%' . $request->search . '%');
            });
        }

        $total = $query->count();

        $leads = $query->skip($request->inicio)
            ->take($request->tamanho)
            ->get();

        return response()->json([
            "success" => true,
            "msg" => 'Lead slistados!',
            "leads" => $leads,
            "total" => $total,
        ], Response::HTTP_OK);
    }
}
