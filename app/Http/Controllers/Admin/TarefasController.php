<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TarefasController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.tarefas.index');
    }

    public function store(Request $request)
    {

        $tarefa = Tarefa::whereNome($request->nome)->exists();

        if ($tarefa) {
            return response()->json([
                "success" => false,
                "msg" => 'Tarefa já cadastrada!',
            ], Response::HTTP_OK);
        }

        Tarefa::create([
            'nome' => $request->nome,
            'etapa' => $request->etapa,
            'todas_as_etapas' => $request->todas_as_etapas,
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Tarefa cadastrada!',
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $tarefa = Tarefa::find($request->id);
        $tarefa->name = $request->name;
        $tarefa->save();

        return response()->json([
            "success" => true,
            "msg" => 'Tarefa atualizado!',
        ], Response::HTTP_OK);
    }

    protected function delete(Request $request)
    {

        $tarefa = Tarefa::find($request->id);
        $tarefa->delete();

        return response()->json([
            "success" => true,
            "msg" => 'Tarefa deletado!',
        ], Response::HTTP_OK);
    }

    public function search(Request $request)
    {

        $query = Tarefa::select();

        if ($request->nome) {
            $query = $query->where('tarefas.nome', 'LIKE', '%' . $request->nome . '%');
        }

        $total = $query->get()->count();

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
