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

class NumerosEnviosController extends Controller
{
    public function searchNotSent(Request $request)
    {

        $numeros_envios = NumerosEnvio::whereEnvioId($request->id_envio)
        ->where("status_envio", "=", "AGUARDANDO")
        ->get();

        return response()->json([
            "success" => true,
            "msg" => 'Números listados',
            "numeros" => $numeros_envios
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {

        $numero_envio = NumerosEnvio::where("numero", "=", $request->numero)
            ->where("envio_id", "=", $request->envio_id)
            ->first();

        $numero_envio->numero = $request->numero_validado;
        $numero_envio->status_envio = $request->status_envio;
        $numero_envio->save();

        return response()->json([
            "success" => true,
            "msg" => 'Status do número no envio atualizado!',
        ], Response::HTTP_OK);
    }
}
