<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contato;
use App\Models\Lead;
use App\Models\Origem;
use App\Models\Parcela;
use App\Models\Plano;
use App\Models\Proposta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ParcelaController extends Controller
{

    public function findAll(Request $request)
    {

        $parcelas = Parcela::wherePropostaId($request->proposta_id)->get();

        return response()->json([
            "success" => true,
            "msg" => 'Parcelas listadas!',
            "parcelas" => $parcelas
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {

        $parcela = Parcela::find($request->id);
        $parcela->status = $request->status;

        if ($parcela->status == "A receber") {
            $parcela->data_pagamento = Carbon::now();

            $proposta = Proposta::find($parcela->proposta_id);
            $proposta->parcelas_recebidas =  $proposta->parcelas_recebidas + 1;
            $proposta->save();
        }

        if ($parcela->status == "Confirmado") {
            $parcela->data_pagamento_corretora = $request->data_recebimento;
            $parcela->save();
        }

        $parcela->save();

        return response()->json([
            "success" => true,
            "msg" => 'Parcela atualizada!',
        ], Response::HTTP_OK);
    }

    public function storeLista(Request $request)
    {

        $parcelas = $request->parcelas;

        $parcelasFormatadas = [];

        foreach ($parcelas as $parcela) {
            $new = array(
                "valor" => $parcela['valor'],
                "data_vencimento" => $parcela['data_vencimento'],
                "proposta_id" => $parcela['proposta_id'],
                "status" => "Pendente",
                "created_at" => Carbon::now(),
                "numero_parcela" => $parcela['numero_parcela']
            );

            array_push($parcelasFormatadas, $new);
        }

        Log::info($parcelasFormatadas);

        Parcela::insert($parcelasFormatadas);

        $contato = Contato::find($request->contato_id);
        $contato->status = "Implantada";
        $contato->save();

        $proposta = Proposta::find($request->proposta_id);
        $proposta->status = "Implantada";
        $proposta->save();

        return response()->json([
            "success" => true,
            "msg" => 'Parcelas criadas!',
        ], Response::HTTP_OK);
    }
}
