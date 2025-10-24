<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TarefasController extends Controller
{
    public function search(Request $request)
    {

        $query = Tarefa::select();

        if ($request->nome) {
            $query = $query->where('tarefas.nome', 'LIKE', '%' . $request->nome . '%');
        }

        if ($request->etapa) {
            $query = $query->where('tarefas.etapa', '=', $request->etapa);
        }

        if ($request->todas_as_etapas) {
            $query = $query->orWhere('tarefas.todas_as_etapas', '=', 1);
        }

        Log::info('Etapa = ' . $request->etapa);

        Log::info($query->toSql());

        $total = $query->count();

        $tarefas = $query->orderBy('nome')
            ->skip($request->inicio)
            ->take($request->tamanho)
            ->get();

        return response()->json([
            "success" => true,
            "msg" => 'Tarefas listados!',
            "tarefas" => $tarefas,
            "total" => $total,
        ], Response::HTTP_OK);
    }
}
