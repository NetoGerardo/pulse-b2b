<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Contato;
use App\Models\Lead;
use App\Models\Lista;
use App\Models\Origem;
use App\Models\SolicitacaoLista;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ListasController extends Controller
{

    public function solicitacoes()
    {
        $solicitacoes = SolicitacaoLista::whereStatus('AGUARDANDO')
            ->leftJoin('users as corretor', 'solicitacao_lista.corretor_id', '=', 'corretor.id')
            ->selectRaw('solicitacao_lista.*, corretor.name as corretor')
            ->get();

        $listas = Lista::all();

        $origens = Origem::all();

        return view('admin.solicitacoes-listas', [
            "listas" => $listas,
            "solicitacoes" => $solicitacoes,
            "origens" => $origens
        ]);
    }

    public function index()
    {
        $listas = Lista::all();

        $origens = Origem::all();
        /*return view('admin.listas.create', [
            "listas" => $listas
        ]);*/

        return view('admin.listas.index', [
            "listas" => $listas,
            "origens" => $origens
        ]);
    }

    protected function store(Request $request)
    {

        $lista = Lista::whereNome($request->nome)->exists();

        if ($lista) {
            return response()->json("Lista já cadastrada!", Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $lista = Lista::create([
                'nome' => $request->nome,
            ]);

            return response()->json([
                "success" => true,
                "msg" => 'Lista criada!',
            ], Response::HTTP_OK);
        }
    }

    protected function update(Request $request)
    {

        $lista = Lista::where('id', '=', $request->id)->first();

        $lista->nome = $request->nome;

        $lista->save();

        return response()->json([
            "success" => true,
            "msg" => 'Lista atualizada!',
        ], Response::HTTP_OK);
    }

    protected function delete(Request $request)
    {

        $lista = Lista::find($request->id);
        $lista->delete();

        return response()->json([
            "success" => true,
            "msg" => 'Lista deletada!',
        ], Response::HTTP_OK);
    }
}
