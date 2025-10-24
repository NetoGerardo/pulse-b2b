<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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

    public function update(Request $request)
    {

        $parcela = Parcela::find($request->id);

        $parcela->status = $request->status;
        $parcela->save();

        return response()->json([
            "success" => true,
            "msg" => 'Parcela confirmada!',
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
                "status" => "Pendente"
            );

            array_push($parcelasFormatadas, $new);
        }

        Log::info($parcelasFormatadas);

        Parcela::insert($parcelasFormatadas);

        return response()->json([
            "success" => true,
            "msg" => 'Parcelas criadas!',
        ], Response::HTTP_OK);
    }
}
