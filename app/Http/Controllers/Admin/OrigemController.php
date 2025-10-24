<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Envio;
use App\Models\Lead;
use App\Models\Mensagem;
use App\Models\Origem;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class OrigemController extends Controller
{
    public function index()
    {
        $origens = Origem::all();

        return view('admin.origens', [
            "origens" => $origens
        ]);
    }

    protected function store(Request $request)
    {

        $origem = Origem::create([
            'nome' => $request->nome,
            'categoria' => $request->categoria,
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Origem criada!',
        ], Response::HTTP_OK);
    }

    protected function update(Request $request)
    {

        $origem = Origem::where('id', '=', $request->id)->first();

        $origem->nome = $request->nome;
        $origem->categoria = $request->categoria;
        $origem->save();

        return response()->json([
            "success" => true,
            "msg" => 'Lista atualizada!',
        ], Response::HTTP_OK);
    }

    protected function delete(Request $request)
    {

        $lista = Origem::find($request->id);
        $lista->delete();

        return response()->json([
            "success" => true,
            "msg" => 'Lista deletada!',
        ], Response::HTTP_OK);
    }

    protected function search(Request $request)
    {

        $query = Origem::select();

        if ($request->nome != '') {
            $query = $query->where('nome', 'LIKE', "%" . $request->nome . "%");
        }

        $total = $query->count();

        $origens = $query->orderby('nome')
            ->skip($request->inicio)
            ->take($request->tamanho)
            ->get();

        return response()->json([
            "success" => true,
            "msg" => 'Origens listadas!',
            "origens" => $origens,
            "total" => $total,
        ], Response::HTTP_OK);
    }
}
