<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Campanha;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CampanhasController extends Controller
{

    public function index(Request $request)
    {
        return view('user.campanhas');
    }


    public function store(Request $request)
    {
        $ultima_campanha = Campanha::whereUserId(Auth::user()->id)->where('status', '!=', 'Finalizada')->exists();

        if ($ultima_campanha) {
            return response()->json([
                "success" => false,
                "msg" => 'Você já possui uma campanha em aberto, conclua a campanha antes de criar uma nova.',
            ], Response::HTTP_OK);
        }

        Campanha::create([
            'nome' => $request->nome,
            'user_id' =>  Auth::user()->id,
            'data_inicio' => $request->data_inicio,
            'opcao_mei' => $request->opcao_mei,
            'estado_id' => $request->estado_id,
            'cidade_id' => $request->cidade_id,
            'data_abertura_inicio' => $request->data_abertura_inicio,
            'data_abertura_fim' => $request->data_abertura_fim,
            'produto' => $request->produto,
            'status' => 'Agendada',
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Campanha cadastrada!',
        ], Response::HTTP_OK);
    }

    public function update_status(Request $request)
    {
        $campanha = Campanha::find($request->id);
        $campanha->status = $request->status;
        $campanha->save();

        return response()->json([
            "success" => true,
            "msg" => 'Campanha atualizada!',
        ], Response::HTTP_OK);
    }

    public function salvar_anotacoes(Request $request)
    {
        $campanha = Campanha::find($request->id);
        $campanha->anotacoes = $request->anotacoes;
        $campanha->save();

        return response()->json([
            "success" => true,
            "msg" => 'Campanha atualizada!',
        ], Response::HTTP_OK);
    }

    public function concluir(Request $request)
    {
        $campanha = Campanha::find($request->id);

        if ($campanha->concluida) {
            return response()->json([
                "success" => false,
                "msg" => 'Essa Campanha já foi marcada como concluída!',
            ], Response::HTTP_OK);
        }

        $campanha->concluida = $request->concluida;
        $campanha->concluida_em = Carbon::now()->timezone('America/Sao_Paulo');;
        $campanha->save();

        return response()->json([
            "success" => true,
            "msg" => 'Campanha atualizada!',
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $campanha = Campanha::find($request->id);
        $campanha->valor = $request->valor;
        $campanha->grupo = $request->grupo;
        $campanha->tipo = $request->tipo;
        $campanha->lista = $request->lista;
        $campanha->save();

        return response()->json([
            "success" => true,
            "msg" => 'Campanha atualizada!',
        ], Response::HTTP_OK);
    }

    protected function delete(Request $request)
    {

        $campanha = Campanha::find($request->id);
        $campanha->save();

        $campanha->delete();

        return response()->json([
            "success" => true,
            "msg" => 'Campanha deletada!',
        ], Response::HTTP_OK);
    }


    public function search(Request $request)
    {

        $query = Campanha::whereUserId(Auth::user()->id);

        if ($request->nome) {
            $query = $query->where('campanhas.nome', 'LIKE', '%' . $request->nome . '%');
        }

        if ($request->etapa) {
            $query = $query->where('campanhas.etapa', '=', $request->etapa);
        }

        if ($request->todas_as_etapas) {
            $query = $query->orWhere('campanhas.todas_as_etapas', '=', 1);
        }

        $total = $query->count();

        $campanhas = $query->orderBy('id', 'DESC')
            ->skip($request->inicio)
            ->take($request->tamanho)
            ->get();

        return response()->json([
            "success" => true,
            "msg" => 'Campanhas listadas!',
            "campanhas" => $campanhas,
            "total" => $total,
        ], Response::HTTP_OK);
    }
}
