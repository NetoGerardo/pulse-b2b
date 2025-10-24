<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contato;
use App\Models\Lead;
use App\Models\Plano;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RelatorioKanbanController extends Controller
{

    public function index(Request $request)
    {
        $corretores = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'user');
            }
        )->get();

        $planos = Plano::all();

        return view('admin.relatorio.kanban', ["corretores" => $corretores, "planos" => $planos]);
    }

    public function search(Request $request)
    {
        $contatos = Contato::whereUserId($request->id)->get();

        $contatos_formatadas = $contatos->map(function ($contato) {
            return [
                "contato" => $contato,
                "proposta" => $contato->proposta,
            ];
        });

        return response()->json([
            "success" => true,
            "msg" => 'Plano cadastrado!',
            "contatos" => $contatos_formatadas,
        ], Response::HTTP_OK);
    }
}
