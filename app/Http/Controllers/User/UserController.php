<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Campanha;
use App\Models\Container;
use App\Models\Contato;
use App\Models\Estado;
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
        $ultima_campanha = Campanha::whereUserId(Auth::user()->id)->orderBy('id', 'desc')->first();
        $estados = Estado::all();

        return view('user.index', ["estados" => $estados, "ultima_campanha" => $ultima_campanha]);
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
