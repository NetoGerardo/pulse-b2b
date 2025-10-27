<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cidade;
use App\Models\Comitente;
use App\Models\DocumentoTipoUsuario;
use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnderecoController extends Controller
{
    public function buscarCidadesPorEstado(Request $request)
    {
        $cidades = Cidade::where('estado_id', '=', $request->estado_id)->orderBy("nome", "asc")->get();

        return response()->json([
            "success" => true,
            "msg" => 'Cidades listadas!',
            "cidades" => $cidades,
        ], Response::HTTP_OK);
    }

    public function buscarEstadoPorNome(Request $request)
    {
        // $estado = Estado::where('nome', '=', $request->nome)->first();

        $estado = Estado::whereRaw("UPPER('nome') = '" . strtoupper($request->nome) . "'");

        return response()->json([
            "success" => true,
            "msg" => 'Estado buscado!',
            "estado" => $estado,
        ], Response::HTTP_OK);
    }

    public function buscarCidadePorNome(Request $request)
    {
        $cidade = Cidade::where('nome', '=', $request->nome)->first();

        return response()->json([
            "success" => true,
            "msg" => 'Estado buscado!',
            "cidade" => $cidade,
        ], Response::HTTP_OK);
    }
}
