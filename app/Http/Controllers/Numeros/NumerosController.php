<?php

namespace App\Http\Controllers\Numeros;

use App\Http\Controllers\Controller;
use App\Models\NumeroRepetido;
use App\Models\Numero;
use App\Models\Lista;
use DateTime;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NumerosController extends Controller
{

    public function listar(Request $request)
    {

        $numeros = Numero::select('numeros.*', 'listas.nome')
            ->leftJoin('listas', 'listas.id', '=', 'numeros.lista_id')
            ->where('numeros.lista_id', '=', $request->lista_id)
            ->paginate(1000);

        $lista = Lista::find($request->lista_id);

        return view('admin.list', [
            "lista" => $lista,
            "numeros" => $numeros
        ]);
    }

    public function filter(Request $request)
    {
        $numeros = Numero::select('numeros.*', 'listas.nome')
            ->leftJoin('listas', 'listas.id', '=', 'numeros.lista_id');

        if ($request->downloads && $request->downloads >= 0) {
            $numeros = $numeros->where('numeros.enviado', '=', $request->downloads);
        }

        if ($request->lista_id) {
            $numeros = $numeros->where('numeros.lista_id', '=', $request->lista_id);
        }

        if ($request->data_inicio) {
            $numeros = $numeros->whereBetween('numeros.created_at', [$request->data_inicio, $request->data_fim]);
        }

        if ($request->limite > 0) {
            $numeros = $numeros->offset($request->indice_inicial)
                ->limit($request->limite);
        }

        $numeros = $numeros->get();

        return response()->json([
            "success" => true,
            "msg" => "Números listados!",
            "numeros" => $numeros
        ], Response::HTTP_OK);
    }

    public function index()
    {
        // $numeros = Numero::paginate(100);

        $numeros = Numero::select('numeros.*', 'listas.nome')
            ->leftJoin('listas', 'listas.id', '=', 'numeros.lista_id')
            ->paginate(1000);


        return view('admin.numeros', [
            "numeros" => $numeros, "links" => $numeros->links()
        ]);
    }

    public function updateDownload(Request $request)
    {

        /*
        $models = Numero::findMany($request->numeros);

        $models->each(function ($item) {
            $item->update(['enviado' => $item->enviado + 1]);
        });
        */

        Numero::whereIn('id', $request->numeros)->increment('enviado', 1);

        return response()->json([
            "success" => true,
            "msg" => 'Quantidade de downloads atualizada!',
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {

        $numerosFormatados = [];
        $numerosRepetidos = [];

        foreach ($request->numeros as $numero) {

            $numeroCadastrado = Numero::where("numero", $numero)->first();

            if ($numeroCadastrado) {

                Log::info("Número já cadastrado - " . $numero);
                $datetime = new DateTime();

                $new = array(
                    "numero" => $numero,
                    "status_da_validacao" => "REPETIDO",
                    "lista_id" => $request->lista_id,
                    "created_at" => $datetime,
                    "lista_referencia" => $numeroCadastrado->lista_id
                );

                array_push($numerosRepetidos, $new);
            } else {

                $datetime = new DateTime();

                $new = array(
                    "numero" => $numero,
                    "status_da_validacao" => "VALIDADO",
                    "enviado" => 0,
                    "created_at" => $datetime,
                    "lista_id" => $request->lista_id
                );

                array_push($numerosFormatados, $new);
            }
        }

        Numero::insert($numerosFormatados);
        NumeroRepetido::insert($numerosRepetidos);

        return response()->json([
            "success" => true,
            "msg" => 'Números cadastrados!',
            "total_repetidos" => sizeof($numerosRepetidos)
        ], Response::HTTP_OK);
    }
}
