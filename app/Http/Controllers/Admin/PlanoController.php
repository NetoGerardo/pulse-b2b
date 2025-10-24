<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Origem;
use App\Models\Plano;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PlanoController extends Controller
{

    public function index()
    {
        $planos = Plano::all();

        return view('admin.planos.index', [
            "planos" => $planos
        ]);
    }

    public function store(Request $request)
    {

        Plano::create([
            'nome' => $request->nome,
            'qtd_parcelas' => $request->qtd_parcelas,
            'comissao_longo_prazo' => $request->comissao_longo_prazo,
            'tipo_comissao_longo_prazo' => $request->tipo_comissao_longo_prazo,
            'categoria' => $request->categoria,
            'imagem' => $request->imagem,
            'tipo_plano' => $request->tipo_plano,
            'comissionamento' => json_encode($request->comissionamento),
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Plano cadastrado!',
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {

        $plano = Plano::find($request->id);

        $plano->nome =  $request->nome;
        $plano->qtd_parcelas =  $request->qtd_parcelas;
        $plano->comissao_longo_prazo =  $request->comissao_longo_prazo;
        $plano->tipo_comissao_longo_prazo =  $request->tipo_comissao_longo_prazo;
        $plano->categoria =  $request->categoria;
        $plano->imagem =  $request->imagem;
        $plano->comissionamento =  $request->comissionamento;
        $plano->tipo_plano =  $request->tipo_plano;
        $plano->save();

        return response()->json([
            "success" => true,
            "msg" => 'Plano cadastrado!',
        ], Response::HTTP_OK);
    }

    protected function delete(Request $request)
    {

        $plano = Plano::find($request->id);
        $plano->delete();

        return response()->json([
            "success" => true,
            "msg" => 'Plano deletado!',
        ], Response::HTTP_OK);
    }
}
