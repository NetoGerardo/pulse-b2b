<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contato;
use App\Models\Lead;
use App\Models\Lista;
use App\Models\Origem;
use App\Models\SolicitacaoLista;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SolicitacoesController extends Controller
{
    public function index()
    {
        $solicitacoes = SolicitacaoLista::whereStatus('AGUARDANDO')
            ->leftJoin('users as corretor', 'solicitacao_lista.corretor_id', '=', 'corretor.id')
            ->selectRaw('solicitacao_lista.*, corretor.name as corretor')
            ->get();

        $listas = Lista::all();

        return view('admin.listas.solicitacoes', [
            "listas" => $listas,
            "solicitacoes" => $solicitacoes
        ]);
    }

    public function send(Request $request)
    {

        DB::beginTransaction();

        Log::info("Lista id -" . $request->lista_id);
        Log::info("Qtd -" . $request->qtd_contatos);

        $leads = Lead::whereListaId($request->lista_id)
            ->whereNull('status')
            ->limit($request->qtd_contatos)->get();

        Log::info("Contatos encontrados");
        Log::info($leads);

        foreach ($leads as $lead) {
            $lead->status = 'ENCAMINHADO';
            $lead->user_id = $request->corretor_id;
            $lead->data_envio_corretor = Carbon::now();
            $lead->save();
        }

        $solicitacao = SolicitacaoLista::find($request->solicitacao_id);
        $solicitacao->status = 'ENVIADA';
        $solicitacao->qtd_contatos = $request->qtd_contatos;
        $solicitacao->save();

        $lista = Lista::find($request->lista_id);
        $lista->numeros_disponiveis =   $lista->numeros_disponiveis - $request->qtd_contatos;
        $lista->save();

        DB::commit();

        return response()->json([
            "success" => true,
            "msg" => 'Lista atualizada!',
        ], Response::HTTP_OK);
    }

    public function reject(Request $request)
    {

        $lista = SolicitacaoLista::find($request->lista_id);
        $lista->status = 'RECUSADA';
        $lista->justificativa_rejeicao = $request->observacao;
        $lista->save();

        return response()->json([
            "success" => true,
            "msg" => 'Lista atualizada!',
        ], Response::HTTP_OK);
    }
}
