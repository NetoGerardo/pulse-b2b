<?php

namespace App\Http\Controllers\Numeros;

use App\Http\Controllers\Controller;
use App\Models\NumeroRepetido;
use App\Models\Numero;
use DateTime;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NumerosRepetidosController extends Controller
{

    public function listar(Request $request)
    {

        $numeros = NumeroRepetido::select('numeros_repetidos.*', 'listas.nome', 'rep.nome AS nome_referencia')
            ->leftJoin('listas', 'listas.id', '=', 'numeros_repetidos.lista_id')
            ->leftJoin('listas AS rep', 'rep.id', '=', 'numeros_repetidos.lista_referencia')
            ->paginate(1000);

        return view('admin.repetidos.list', [
            "numeros" => $numeros
        ]);
    }

    public function filter(Request $request)
    {
        Log::info("LISTANDO ");

        $numeros = Numero::select('numeros.*', 'listas.nome')
            ->leftJoin('listas', 'listas.id', '=', 'numeros.lista_id');

        if ($request->lista_id) {
            $numeros = $numeros->where('numeros.lista_id', '=', $request->lista_id);
        }

        if ($request->data_inicio) {
            $numeros = $numeros->whereBetween('numeros.created_at', [$request->data_inicio, $request->data_fim]);
        }

        if ($request->limite > 0) {

            Log::info("LISTANDO COM INDICE");

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

        foreach ($request->numeros as $numero) {

            if (Numero::where("numero", $numero)->exists()) {
                Log::info("Número já cadastrado - " . $numero);
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

        return response()->json([
            "success" => true,
            "msg" => 'Números cadastrados!',
        ], Response::HTTP_OK);
    }
}
