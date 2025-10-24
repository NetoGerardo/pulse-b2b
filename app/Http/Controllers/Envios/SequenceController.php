<?php

namespace App\Http\Controllers\Envios;

use App\Http\Controllers\Controller;
use App\Models\Envio;
use App\Models\Container;
use App\Models\NumerosEnvio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class SequenceController extends Controller
{
    public function create(Request $request)
    {

        $container = Container::whereUserId(Auth::user()->id)->first();

        return view('envios.sequence.create', [
            "my_container" => $container
        ]);
    }

    public function list(Request $request)
    {

        $container = Container::whereUserId(Auth::user()->id)->first();
        $envios = Envio::whereContainerId($container->id)->get();

        $envios_formatados = $envios->map(function ($envio) {
            return [
                "envio" => $envio,
                "mensagens" => $envio->mensagens,
            ];
        });

        Log::info($envios);

        return view('envios.list', [
            "my_container" => $container,
            "envios" => $envios_formatados
        ]);
    }

    public function updateTotal(Request $request)
    {

        $envio = Envio::whereId($request->envio_id)->first();
        $envio->total_enviados =  $envio->total_enviados + 1;
        $envio->save();

        return response()->json([
            "success" => true,
            "msg" => 'Total enviado atualizado!',
        ], Response::HTTP_OK);
    }

    public function send(Request $request)
    {

        $envio = Envio::whereId($request->envio_id)->first();
        $container = Container::whereUserId(Auth::user()->id)->first();

        return view('envios.send', [
            "my_container" => $container,
            "envio" => $envio,
            "continue" => $request->continue
        ]);
    }

    public function finish(Request $request)
    {

        $envio = Envio::whereId($request->envio_id)->first();

        Log::info("Finalizando envio");
        Log::info($envio);

        $envio->status_do_envio = 'CONCLUIDO';
        $envio->save();

        return response()->json([
            "success" => true,
            "msg" => 'Envio finalizado!',
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {

        $container = Container::whereUserId(Auth::user()->id)->first();

        $envio = Envio::create([
            'nome' => $request->nome,
            'container_id' =>  $container->id,
            'status_do_envio' => 'ABERTO',
            'tipo_envio' =>  $request->tipo_envio,
            'url'  =>  $request->url,
            'mensagem'  =>  $request->mensagem,
            'total_contatos' => $request->total_contatos,
            'meta_dados' => $request->meta_dados,
            'data_envio' => Carbon::now()
        ]);

        $contatos = $request->numeros;
        Log::info($envio);
        Log::info("\n\n\n___________\n\n\n");
        Log::info($contatos);

        $contatosFormatados = [];

        foreach ($contatos as $contato) {
            $new = array(
                "numero" => $contato['phone'],
                "envio_id" => $envio->id,
            );

            array_push($contatosFormatados, $new);
        }

        Log::info($contatosFormatados);

        NumerosEnvio::insert($contatosFormatados);

        return response()->json([
            "success" => true,
            "msg" => 'Envio criado!',
            "envio_id" => $envio->id
        ], Response::HTTP_OK);
    }
}
