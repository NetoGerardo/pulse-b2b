<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administradora;
use App\Models\Contato;
use App\Models\ContatoUser;
use App\Models\Lead;
use App\Models\Operadora;
use App\Models\Proposta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OperadoraController extends Controller
{
    public function index(Request $request)
    {

        $operadoras = Operadora::all();

        return view('admin.operadoras', [
            "operadoras" => $operadoras
        ]);
    }


    public function store(Request $request)
    {

        Operadora::create([
            'nome' => $request->nome,
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Operadora cadastrada!',
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {

        $operadora = Operadora::find($request->id);
        $operadora->nome = $request->nome;
        $operadora->save();

        return response()->json([
            "success" => true,
            "msg" => 'Operadora atualizada!',
        ], Response::HTTP_OK);
    }

    protected function delete(Request $request)
    {

        $operadora = Operadora::find($request->id);
        $operadora->delete();

        return response()->json([
            "success" => true,
            "msg" => 'Administradora deletada!',
        ], Response::HTTP_OK);
    }
}
