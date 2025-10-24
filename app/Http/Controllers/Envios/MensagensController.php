<?php

namespace App\Http\Controllers\Envios;

use App\Http\Controllers\Controller;
use App\Models\Mensagem;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class MensagensController extends Controller
{
    public function createMessage(Request $request)
    {
        //NECESSÁRIO ENCERRAR MENSAGENS ANTERIORES PARA NA MESMA SESSÃO

        $envio = Mensagem::create([
            'numero' => $request->numero,
            'mensagem_enviada' =>  $request->mensagem,
            'data_envio' => Carbon::now(),
            'container_id' => $request->container_id,
            'envio_id' => $request->envio_id,
            'status_da_resposta' => "AGUARDANDO"
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Mensagem criada!',
        ], Response::HTTP_OK);
    }
}
