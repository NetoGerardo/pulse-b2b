<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administradora;
use App\Models\Contato;
use App\Models\ContatoUser;
use App\Models\Lead;
use App\Models\Operadora;
use App\Models\Produto;
use App\Models\Proposta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProdutoController extends Controller
{

    public function search(Request $request)
    {
        $produtos = Produto::where("produtos.operadora_id", "=", $request->operadora_id)->get();

        return response()->json([
            "success" => true,
            "msg" => 'Produtos listadas!',
            "produtos" => $produtos,
        ], Response::HTTP_OK);
    }

    public function index(Request $request)
    {

        $produtos = Produto::leftJoin('operadoras', 'operadoras.id', '=', 'produtos.operadora_id')
            ->selectRaw('produtos.*, operadoras.nome as operadora')
            ->get();

        $operadoras = Operadora::all();

        return view('admin.produtos', [
            "produtos" => $produtos,
            "operadoras" => $operadoras,
        ]);
    }


    public function store(Request $request)
    {

        Produto::create([
            'nome' => $request->nome,
            'operadora_id' => $request->operadora_id,
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Produto cadastrado!',
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {

        $produto = Produto::find($request->id);
        $produto->nome = $request->nome;
        $produto->operadora_id = $request->operadora_id;
        $produto->save();

        return response()->json([
            "success" => true,
            "msg" => 'Produto atualizado!',
        ], Response::HTTP_OK);
    }

    protected function delete(Request $request)
    {

        $produto = Produto::find($request->id);
        $produto->delete();

        return response()->json([
            "success" => true,
            "msg" => 'Produto deletado!',
        ], Response::HTTP_OK);
    }
}
