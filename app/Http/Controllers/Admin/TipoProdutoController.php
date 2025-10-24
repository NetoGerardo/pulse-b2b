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
use App\Models\PropostaRelatorio;
use App\Models\StatusProposta;
use App\Models\TipoProduto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TipoProdutoController extends Controller
{
    public function index(Request $request)
    {

        $tipo_produto = TipoProduto::all();

        return view('admin.tipo_produto', [
            "tipo" => $tipo_produto
        ]);
    }

    public function store(Request $request)
    {

        TipoProduto::create([
            'nome' => $request->nome,
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Tipo produto cadastrado!',
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {

        $tipo_produto = TipoProduto::find($request->id);
        $tipo_produto->nome = $request->nome;
        $tipo_produto->save();

        return response()->json([
            "success" => true,
            "msg" => 'Tipo produto atualizado!',
        ], Response::HTTP_OK);
    }

    protected function delete(Request $request)
    {

        $tipo_produto = TipoProduto::find($request->id);
        $tipo_produto->delete();

        return response()->json([
            "success" => true,
            "msg" => 'Tipo produto deletado!',
        ], Response::HTTP_OK);
    }
}
