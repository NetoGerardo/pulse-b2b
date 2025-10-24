<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TarefaLead;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TarefaLeadController extends Controller
{
    protected function index(Request $request)
    {
        return view('user.tarefa-lead.index');
    }

    public function store(Request $request)
    {

        $parametro = TarefaLead::where('tarefa_id', $request->tarefa_id)
            ->where('contato_id', $request->contato_id)
            ->whereNull('concluida')
            ->exists();

        if ($parametro) {
            return response()->json([
                "success" => false,
                "msg" => 'Tarefa já cadastrada para este contato!',
            ], Response::HTTP_OK);
        }

        TarefaLead::create([
            'tarefa_id' => $request->tarefa_id,
            'contato_id' => $request->contato_id,
            'expira_em' => $request->expira_em,
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Tarefa cadastrada!',
        ], Response::HTTP_OK);
    }

    public function salvar_anotacoes(Request $request)
    {
        $parametro = TarefaLead::find($request->id);
        $parametro->anotacoes = $request->anotacoes;
        $parametro->save();

        return response()->json([
            "success" => true,
            "msg" => 'Tarefa atualizada!',
        ], Response::HTTP_OK);
    }

    public function concluir(Request $request)
    {
        $parametro = TarefaLead::find($request->id);

        if ($parametro->concluida) {
            return response()->json([
                "success" => false,
                "msg" => 'Essa tarefa já foi marcada como concluída!',
            ], Response::HTTP_OK);
        }

        $parametro->concluida = $request->concluida;
        $parametro->concluida_em = Carbon::now()->timezone('America/Sao_Paulo');;
        $parametro->save();

        return response()->json([
            "success" => true,
            "msg" => 'Tarefa atualizada!',
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $parametro = TarefaLead::find($request->id);
        $parametro->valor = $request->valor;
        $parametro->grupo = $request->grupo;
        $parametro->tipo = $request->tipo;
        $parametro->lista = $request->lista;
        $parametro->save();

        return response()->json([
            "success" => true,
            "msg" => 'Tarefa atualizada!',
        ], Response::HTTP_OK);
    }

    protected function delete(Request $request)
    {

        $tarefa_lead = TarefaLead::find($request->id);
        $tarefa_lead->motivo_deletar = $request->motivo_deletar;
        $tarefa_lead->save();

        $tarefa_lead->delete();

        return response()->json([
            "success" => true,
            "msg" => 'TarefaLead deletada!',
        ], Response::HTTP_OK);
    }


    public function search(Request $request)
    {

        $query = TarefaLead::whereNull('concluida')->with('tarefa')->with('contato');

        // **AQUI ESTÁ A SOLUÇÃO**
        // Adiciona uma condição para filtrar apenas as tarefas cujo 'contato'
        // relacionado pertence ao usuário autenticado.
        $query->whereHas('contato', function ($q) {
            // $q é uma instância do Query Builder para o relacionamento 'contato'
            $q->where('user_id', Auth::user()->id);
            // Ou, de forma mais curta e moderna:
            // $q->where('user_id', auth()->id());
        });


        Log::info("Teste");
        Log::info($query->toSql());

        $total = $query->count();

        $tarefas = $query->skip($request->inicio)
            ->take($request->tamanho)
            ->orderBy('expira_em')
            ->get();

        return response()->json([
            "success" => true,
            "msg" => 'Tarefas listadas!',
            "tarefas" => $tarefas,
            "total" => $total,
        ], Response::HTTP_OK);
    }
}
