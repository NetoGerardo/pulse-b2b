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

class PropostaRelatorioController extends Controller
{

    public function search(Request $request)
    {
        $propostas = PropostaRelatorio::whereBetween('proposta_relatorio.created_at', [$request->data_inicio, $request->data_fim])
            ->leftJoin('produtos', 'produtos.id', '=', 'proposta_relatorio.produto_id')
            ->leftJoin('administradoras', 'administradoras.id', '=', 'proposta_relatorio.administradora_id')
            ->leftJoin('operadoras', 'operadoras.id', '=', 'proposta_relatorio.operadora_id')
            ->leftJoin('status_proposta', 'status_proposta.id', '=', 'proposta_relatorio.status_id')
            ->leftJoin('users as corretor', 'corretor.id', '=', 'proposta_relatorio.corretor_id')
            ->selectRaw('proposta_relatorio.*, produtos.nome as produto, administradoras.nome as administradora, operadoras.nome as operadora, status_proposta.nome as status');

        if ($request->corretor_id != "") {
            $propostas = $propostas->where("proposta_relatorio.corretor_id", "=", $request->corretor_id);
        }

        if ($request->telefone != '') {
            $propostas = $propostas->whereRaw('UPPER(proposta_relatorio.telefone_titular) LIKE ? ', '%' . $request->telefone . '%');
        }

        if ($request->supervisor_id != "") {
            $propostas = $propostas->where("corretor.supervisor_id", "=", $request->supervisor_id);
        }

        if ($request->status_id != "") {
            $propostas = $propostas->where("proposta_relatorio.status_id", "=", $request->status_id);
        }

        if ($request->operadora_id != "") {
            $propostas = $propostas->where("proposta_relatorio.operadora_id", "=", $request->operadora_id);
        }

        if ($request->tipo_produto_id != "") {
            $propostas = $propostas->where("proposta_relatorio.tipo_produto_id", "=", $request->tipo_produto_id);
        }

        if ($request->produto_id != "") {
            $propostas = $propostas->where("proposta_relatorio.produto_id", "=", $request->produto_id);
        }

        $propostas = $propostas->get();

        return response()->json([
            "success" => true,
            "msg" => 'Propostas listadas!',
            "propostas" => $propostas,
        ], Response::HTTP_OK);
    }

    public function index(Request $request)
    {

        $propostas_relatorio =  PropostaRelatorio::whereDate('proposta_relatorio.created_at', Carbon::today())
            ->leftJoin('produtos', 'produtos.id', '=', 'proposta_relatorio.produto_id')
            ->leftJoin('administradoras', 'administradoras.id', '=', 'proposta_relatorio.administradora_id')
            ->leftJoin('operadoras', 'operadoras.id', '=', 'proposta_relatorio.operadora_id')
            ->leftJoin('status_proposta', 'status_proposta.id', '=', 'proposta_relatorio.status_id')
            ->selectRaw('proposta_relatorio.*, produtos.nome as produto, administradoras.nome as administradora, operadoras.nome as operadora, status_proposta.nome as status')
            ->get();

        $administradoras = Administradora::all();
        $operadoras = Operadora::all();
        $produtos = Produto::all();
        $status_proposta = StatusProposta::all();
        $tipo = TipoProduto::all();

        $supervisores = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'supervisor');
            }
        )->get();

        return view('admin.propostas', [
            "propostas_relatorio" => $propostas_relatorio,
            "administradoras" => $administradoras,
            "operadoras" => $operadoras,
            "produtos" => $produtos,
            "status" => $status_proposta,
            "supervisores" => $supervisores,
            "tipo" => $tipo,
        ]);
    }

    public function store(Request $request)
    {

        PropostaRelatorio::create([
            'nome_titular' => $request->nome_titular,
            'cpf_titular' => $request->cpf_titular,
            'telefone_titular' => $request->telefone_titular,
            'produto_id' => $request->produto,
            'operadora_id' => $request->operadora,
            'status_id' => $request->status,
            'administradora_id' => $request->administradora,
            'observacoes' => $request->observacoes,
            'data_envio_documentacao' => $request->data_envio_documentacao,
            'qtd_vidas' => $request->qtd_vidas,
            'tipo_produto_id' => $request->tipo_produto_id,
            'corretor_id' => $request->corretor_id,
            'supervisor_id' => $request->supervisor_id,
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Proposta cadastrada!',
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {

        $proposta = PropostaRelatorio::find($request->id);
        $proposta->nome_titular = $request->nome_titular;
        $proposta->cpf_titular = $request->cpf_titular;
        $proposta->telefone_titular = $request->telefone_titular;
        $proposta->status_id = $request->status_id;
        $proposta->produto_id = $request->produto_id;
        $proposta->operadora_id = $request->operadora_id;
        $proposta->administradora_id = $request->administradora_id;
        $proposta->observacoes = $request->observacoes;
        $proposta->data_envio_documentacao = $request->data_envio_documentacao;
        $proposta->qtd_vidas = $request->qtd_vidas;
        $proposta->tipo_produto_id = $request->tipo_produto_id;
        $proposta->supervisor_id = $request->supervisor_id;
        $proposta->corretor_id = $request->corretor_id;
        $proposta->parcela_1 = $request->parcela_1;
        $proposta->data_pagamento_1 = $request->data_pagamento_1;
        $proposta->data_repasse_1 = $request->data_repasse_1;
        $proposta->parcela_2 = $request->parcela_2;
        $proposta->data_pagamento_2 = $request->data_pagamento_2;
        $proposta->data_repasse_2 = $request->data_repasse_2;
        $proposta->parcela_3= $request->parcela_3;
        $proposta->data_pagamento_3 = $request->data_pagamento_3;
        $proposta->data_repasse_3 = $request->data_repasse_3;
        $proposta->save();

        return response()->json([
            "success" => true,
            "msg" => 'Proposta cadastrada!',
        ], Response::HTTP_OK);
    }

    protected function delete(Request $request)
    {

        $proposta = PropostaRelatorio::find($request->id);
        $proposta->delete();

        return response()->json([
            "success" => true,
            "msg" => 'Proposta deletada!',
        ], Response::HTTP_OK);
    }
}
