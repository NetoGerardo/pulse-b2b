<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Contato;
use App\Models\ContatoUser;
use App\Models\Lead;
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

        if ($request->user_id) {
            $query = $query->whereUserId($request->user_id)->with('tarefas.tarefa')->with('origemLead');
        } else {
            $query = $query->whereUserId(Auth::user()->id)->with('tarefas.tarefa')->with('origemLead');
        }

        if ($request->nome) {
            $query = $query->where('tarefas.nome', 'LIKE', '%' . $request->nome . '%');
        }

        $total = $query->count();

        $contatos = $query->orderBy('nome')
            ->skip($request->inicio)
            ->take($request->tamanho)
            ->get();

        return response()->json([
            "success" => true,
            "msg" => 'Contatos listados!',
            "contatos" => $contatos,
            "total" => $total,
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $contato = Contato::find($request->id);
        $contato->possui_cnpj = $request->possui_cnpj;
        $contato->possui_plano = $request->possui_plano;
        $contato->nome = $request->nome;
        $contato->ocupacao = $request->ocupacao;
        $contato->complemento = $request->complemento;
        $contato->qtd_vidas = $request->qtd_vidas;
        $contato->observacoes_corretor = $request->observacoes_corretor;
        $contato->status = $request->status;
        $contato->bairro = $request->bairro;
        $contato->telefone = $request->telefone;
        $contato->save();

        return response()->json([
            "success" => true,
            "msg" => 'Observações adicionadas!',
        ], Response::HTTP_OK);
    }

    public function naoInteressados(Request $request)
    {
        $leads = Lead::whereUserId(Auth::user()->id)->where('avaliacao', "=", 'NEGATIVA')->get();

        return view('user.rejeitados', [
            "leads" => $leads
        ]);
    }

    public function recuperarRejeitado(Request $request)
    {
        $lead = Lead::find($request->lead_id);
        $lead->contactado = 1;
        $lead->avaliacao = "POSITIVA";
        $lead->data_avaliacao  = Carbon::now();
        $lead->status_negociacao = $request->status_negociacao;
        $lead->save();

        Contato::create([
            'nome' => $lead->nome,
            'telefone' => $lead->telefone,
            'status' => "Indicação Cadastrada",
            'possui_cnpj' => $lead->possui_cnpj,
            'ocupacao' => $lead->ocupacao,
            'bairro' => $lead->bairro,
            'possui_plano' => $lead->possui_plano,
            'qtd_vidas' => $lead->qtd_vidas,
            'complemento' => $lead->complemento,
            'origem' => $lead->origem_lead,
            'origem_id' => $lead->origem_id,
            'user_id' => $lead->user_id
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Status atualizado!',
        ], Response::HTTP_OK);
    }

    public function updateStatus(Request $request)
    {

        $contato = Contato::find($request->id);
        $contato->status = $request->status;
        $contato->save();

        return response()->json([
            "success" => true,
            "msg" => 'Status atualizado!',
        ], Response::HTTP_OK);
    }

    public function adicionarObservacao(Request $request)
    {

        $contato = Contato::find($request->id);
        $contato->observacoes_corretor = $request->observacoes_corretor;
        $contato->save();

        return response()->json([
            "success" => true,
            "msg" => 'Observações adicionadas!',
        ], Response::HTTP_OK);
    }

    public function adicionarJustificativa(Request $request)
    {

        $contato = Contato::find($request->id);
        $contato->justificativa = $request->justificativa;
        $contato->save();

        return response()->json([
            "success" => true,
            "msg" => 'Justificativa adicionada!',
        ], Response::HTTP_OK);
    }



    public function primeira_abertura(Request $request)
    {
        $lead = Contato::find($request->contato_id);
        if (!$lead->aberto_em) {
            $lead->aberto_em = Carbon::now()->timezone('America/Sao_Paulo');;;
            $lead->save();
        }

        return response()->json([
            "success" => true,
            "msg" => 'Lead atualizado!',
        ], Response::HTTP_OK);
    }
}
