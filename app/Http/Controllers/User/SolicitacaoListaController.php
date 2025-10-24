<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\SolicitacaoLista;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SolicitacaoListaController extends Controller
{

    public function index(Request $request)
    {
        $solicitacao_lista = SolicitacaoLista::whereCorretorId(Auth::user()->id)->get();

        return view('user.solicitacao_lista', [
            "solicitacao_lista" => $solicitacao_lista
        ]);
    }

    public function store(Request $request)
    {

        $lista = SolicitacaoLista::where("status", "=", "AGUARDANDO")->whereCorretorId(Auth::user()->id)->exists();

        if ($lista) {
            return response()->json("Você já possui uma solicitação em aberto!", Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {

            $solicitacao = SolicitacaoLista::create([
                'status' => "AGUARDANDO",
                'corretor_id' => Auth::user()->id
            ]);

            $ultima_lista = SolicitacaoLista::where("id", "!=", $solicitacao->id)->orderBy('id', 'DESC')->first();

            if ($ultima_lista) {
                $lista = SolicitacaoLista::find($ultima_lista->id);
                $lista->qtd_ultima_lista = $ultima_lista->tamanho;
                $lista->data_ultima_lista = $ultima_lista->created_at;
                $lista->save();
            }
        }

        return response()->json([
            "success" => true,
            "msg" => 'Solicitação criada!',
        ], Response::HTTP_OK);
    }
}
