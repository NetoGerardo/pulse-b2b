<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Contato;
use App\Models\Lead;
use App\Models\Origem;
use App\Models\Plano;
use App\Models\Tarefa;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {

        $origens = Origem::all();

        $planos = Plano::all();

        return view('user.index', ["origens" => $origens, "planos" => $planos]);
    }

    protected function resetApiKey(Request $request)
    {

        $container = Container::where('id', '=', $request->id)->first();

        $container->chave_api =  $request->chave_api;

        $container->save();

        return response()->json([
            "success" => true,
            "msg" => 'Chave atualizada!',
        ], Response::HTTP_OK);
    }
}
