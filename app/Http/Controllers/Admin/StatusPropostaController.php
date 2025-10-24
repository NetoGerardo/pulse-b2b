<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administradora;
use App\Models\Contato;
use App\Models\ContatoUser;
use App\Models\Lead;
use App\Models\Proposta;
use App\Models\StatusProposta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StatusPropostaController extends Controller
{
    public function index(Request $request)
    {

        $status_proposta = StatusProposta::all();

        return view('admin.status_proposta', [
            "status" => $status_proposta
        ]);
    }


    public function store(Request $request)
    {

        StatusProposta::create([
            'nome' => $request->nome,
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Status cadastrado!',
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {

        $status_proposta = StatusProposta::find($request->id);
        $status_proposta->nome = $request->nome;
        $status_proposta->save();

        return response()->json([
            "success" => true,
            "msg" => 'Status atualizado!',
        ], Response::HTTP_OK);
    }

    protected function delete(Request $request)
    {

        $status_proposta = StatusProposta::find($request->id);
        $status_proposta->delete();

        return response()->json([
            "success" => true,
            "msg" => 'Status deletado!',
        ], Response::HTTP_OK);
    }
}
