<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\FilaLead;
use App\Models\Lead;
use App\Models\Origem;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LeadsWebhookController extends Controller
{

    protected function store(Request $request)
    {
        $prefix = "2804:14d:168a:b323";

        $data_atual = Carbon::now()->timezone('America/Sao_Paulo');

        Log::info($request);

        $hash = $this->generateRandomString(150);

        $lead = Lead::create([
            'nome' => $request->name,
            'telefone' =>  $request->phones,
            'origem_lead' => $request->cf_fonte,
            'possui_cnpj' => $request->cf_info,
            'complemento' => $request->outras_infos,
            'qtd_vidas' => $request->vidas,
            'created_at' => $data_atual,
            'comentario_avaliacao' => $request->ip_google ?? '',
            'lead_key' => $hash,
            'status' => 'AGUARDANDO ENCAMINHAMENTO',
        ]);

        $origem = Origem::whereNome("Google Oficial")->first();

        if ($origem) {
            $lead->origem_id = $origem->id;
            $lead->save();
        }

        $status = $this->checarStatusLead($lead);
        $lead->avaliacao_sistema = $status;

        if ($request->ip_google && substr($request->ip_google, 0, strlen($prefix)) === $prefix) {
            $lead->avaliacao_sistema = "Ataque";
        } /*else {
            if ($status == "Válida") {
                FilaLead::create([
                    'lead_id' => $lead->id,
                    'status' =>  'pendente',
                    'created_at' => $data_atual,
                ]);
            }
        }
            */

        $lead->save();
        Log::info("STATUS FINAL");
        Log::info($status);

        return response()->json([
            "success" => true,
            "msg" => 'Lead salvo!',
        ], Response::HTTP_OK);
    }

    function generateRandomString($length = 30)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    protected function storeContainer(Request $request)
    {
        Container::create([
            'nome' => $request->nome,
            'chave_api' => $request->nome,
            'url' => $request->nome,
            'user_id' => 1,
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Container salvo!',
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

        // Remove o prefixo '55' se existir
        if (substr($telefone_formatado, 0, 2) === '55') {
            $telefone_formatado = substr($telefone_formatado, 2);
        }

        // Verifica se o telefone começa com DDD 21
        $ddd = substr($telefone_formatado, 0, 2);

        if ($ddd != 21 && $ddd != 22 && $ddd != 24) {
            return "Interurbana";
        } else {
            return "Válida";
        }

        /*
        if (substr($telefone_formatado, 2) == "55") {
            $tam = strlen($telefone_formatado);
            $telefone_formatado =  substr($telefone_formatado, 2, $tam - 1);

            $ddd = $telefone_formatado = substr($telefone_formatado, 0, 2);
        } else {
            $ddd = $telefone_formatado = substr($telefone_formatado, 0, 2);

            if ($ddd != 21 && $ddd != 22 && $ddd != 24) {
                return "Interurbana";
            }
        }

        return "Válida";
        */
    }
}
