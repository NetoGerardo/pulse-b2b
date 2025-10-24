<?php

namespace App\Http\Controllers\Envios;

use App\Http\Controllers\Controller;
use App\Models\Funil;
use App\Models\MensagemFunil;
use App\Models\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class FunisController extends Controller
{
    public function store(Request $request)
    {

        $container = Container::whereUserId(Auth::user()->id)->first();

        $funil = Funil::create([
            'nome' => $request->nome,
            'container_id' =>  $container->id,
        ]);

        $mensagensFormatadas = [];

        Log::info("\n\nMensagens\n\n\n");
        Log::info($request->mensagens);
        Log::info("\n\n\n___________\n\n\n");

        foreach ($request->mensagens as $mensagem) {
            $new = array(
                'tag' =>  $mensagem['tag'],
                'tipo_media' =>  $mensagem['tipo_envio'],
                'dif_minutos' =>  $mensagem['tempo'],
                'resposta_esperada' =>  json_encode($mensagem['resposta_esperada']),
                'funil_id' => $funil->id,
            );

            if (array_key_exists('mensagem', $mensagem)) {
                $new['texto'] = $mensagem['mensagem'];
            }

            if (array_key_exists('url', $mensagem)) {
                $new['url_media'] = $mensagem['url'];
            }

            MensagemFunil::insert($new);
        }

        return response()->json([
            "success" => true,
            "msg" => 'Funil criado!',
            "funil_id" => $funil->id
        ], Response::HTTP_OK);
    }

    public function search(Request $request)
    {

        Log::info($request->funil_id);

        $funil = Funil::find($request->funil_id);

        Log::info($funil);

        return response()->json([
            "success" => true,
            "funil" => $funil
        ], Response::HTTP_OK);
    }
}
