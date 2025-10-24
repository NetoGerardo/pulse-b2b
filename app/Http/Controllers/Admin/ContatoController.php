<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contato;
use App\Models\ContatoUser;
use App\Models\Lead;
use App\Models\Proposta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContatoController extends Controller
{
    public function search(Request $request)
    {

        $query = Contato::select();

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

    public function updateStatus(Request $request)
    {
        $contato = Contato::find($request->id);
        $contato->status = $request->status;
        $contato->save();

        $proposta = Proposta::find($request->proposta_id);
        $proposta->status = $request->status;
        $proposta->save();

        return response()->json([
            "success" => true,
            "msg" => 'Status atualizado!',
        ], Response::HTTP_OK);
    }

    public function adicionarObservacao(Request $request)
    {

        $contato = Contato::find($request->id);
        $contato->observacoes_admin = $request->observacoes_admin;
        $contato->save();

        return response()->json([
            "success" => true,
            "msg" => 'Observações adicionadas!',
        ], Response::HTTP_OK);
    }
}
