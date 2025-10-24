<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Origem;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{

    protected function store(Request $request)
    {

        $lead = Lead::create([
            'nome' => $request->nome,
            'telefone' => $request->telefone,
            'email' => $request->email,
            'origem_lead' => 'Google Oficial'
        ]);

        $origem = Origem::whereNome("Google Oficial")->first();

        if ($origem) {
            $lead->origem_id = $origem->id;
            $lead->save();
        }

        $status = $this->checarStatusLead($lead);
        $lead->avaliacao_sistema = $status;
        $lead->save();
        Log::info("STATUS FINAL");
        Log::info($status);

        return response()->json([
            "success" => true,
            "msg" => 'Lead salvo!',
        ], Response::HTTP_OK);
    }

    function checarStatusLead($lead)
    {

        Log::info("Telefone = " . $lead->telefone);

        $lead_telefone = Lead::whereTelefone($lead->telefone)->whereDate('created_at', Carbon::today())->where("id", "!=", $lead->id)->exists();

        Log::info("Existe? = " . $lead_telefone);

        if ($lead_telefone) {
            return "Duplicada";
        }

        $telefone_formatado = preg_replace("/[^0-9]/", '', $lead->telefone);

        Log::info("Formatado = " . $telefone_formatado);

        if (strlen($telefone_formatado) < 10) {
            return "Inválida";
        }

        if (substr($telefone_formatado, 2) == "55") {
            $tam = strlen($telefone_formatado);
            $telefone_formatado =  substr($telefone_formatado, 2, $tam - 1);

            $ddd = $telefone_formatado = substr($telefone_formatado, 0, 2);

            if ($ddd != 21 && $ddd != 22 && $ddd != 24) {
                return "Interurbana";
            }
        }

        return "Válida";
    }

    protected function storePlanilhas(Request $request)
    {

        Log::info("STORE PLANILHAS");
        Log::info($request);

        //$time = strtotime($request->data);

        //$newformat = date('Y/m/d H:i:s', $time);

        //NOVO MÉTODO
        //$data = str_replace("/", "-", $request->data);
        //$newformat = date('Y-m-d H:i:s', strtotime($data));

        $newformat = DateTime::createFromFormat('m/d/Y H:i:s', $request->data);

        $lead = Lead::create([
            'nome' => $request->nome,
            'telefone' => $request->telefone,
            'email' => $request->email,
            'origem_lead' => $request->campanha,
            'created_at' => date_format($newformat, "Y-m-d H:i:s"),
        ]);

        $origem = Origem::whereNome("Face e Insta")->first();

        if ($origem) {
            $lead->origem_id = $origem->id;
            $lead->save();
        }

        return response()->json([
            "success" => true,
            "msg" => 'Lead salvo!',
        ], Response::HTTP_OK);
    }
}
