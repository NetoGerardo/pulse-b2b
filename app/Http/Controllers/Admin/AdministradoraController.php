<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administradora;
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

class AdministradoraController extends Controller
{
    public function index(Request $request)
    {

        $administradoras = Administradora::all();

        return view('admin.administradoras', [
            "administradoras" => $administradoras
        ]);
    }


    public function store(Request $request)
    {

        Administradora::create([
            'nome' => $request->nome,
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Administradora cadastrada!',
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {

        $administradora = Administradora::find($request->id);
        $administradora->nome = $request->nome;
        $administradora->save();

        return response()->json([
            "success" => true,
            "msg" => 'Administradora atualizada!',
        ], Response::HTTP_OK);
    }

    protected function delete(Request $request)
    {

        $administradora = Administradora::find($request->id);
        $administradora->delete();

        return response()->json([
            "success" => true,
            "msg" => 'Administradora deletada!',
        ], Response::HTTP_OK);
    }
}
