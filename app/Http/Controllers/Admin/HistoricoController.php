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

class HistoricoController extends Controller
{

    protected function index(Request $request)
    {
        $historicos = Historico::leftJoin('users', 'historicos.user_id', '=', 'users.id')
            ->selectRaw('historicos.*, users.name as validador')
            ->orderBy('id', 'DESC')->get();;

        $validadores = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'user');
            }
        )->get();

        return view('admin.historico.index', [
            "historicos" => $historicos,
            "validadores" => $validadores
        ]);
    }

    protected function search(Request $request)
    {

        Log::info("HISTORICO ACESSADO");

        $historicos = Historico::leftJoin('users', 'historicos.user_id', '=', 'users.id')
            ->whereBetween('historicos.created_at', [$request->data_inicio, $request->data_fim]);

        if ($request->nome != "") {
            $historicos = $historicos->where('users.name', 'LIKE', "%" . $request->nome . "%");
        }

        if ($request->validador_id != "") {
            $historicos = $historicos->where('users.id', '=', $request->validador_id);
        }

        $historicos = $historicos->selectRaw('historicos.*, users.name as validador')
            ->orderBy('id', 'DESC')->get();

        return response()->json([
            "success" => true,
            "msg" => 'Historicos listados!',
            "historicos" => $historicos,
        ], Response::HTTP_OK);
    }
}
