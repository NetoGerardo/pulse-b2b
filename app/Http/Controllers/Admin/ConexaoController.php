<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Historico;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConexaoController extends Controller
{

    protected function index(Request $request)
    {

        $usuario = User::find(Auth::user()->id);
        $container = Container::whereUserId(Auth::user()->id)->first();

        Log::info($container);

        return view('conexao.index', [
            "container" => $container,
            "container_sem_tags" => $container,
            "usuario" => $usuario
        ]);
    }
}
