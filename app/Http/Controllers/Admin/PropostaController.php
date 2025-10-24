<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contato;
use App\Models\ContatoUser;
use App\Models\Lead;
use App\Models\Plano;
use App\Models\Proposta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PropostaController extends Controller
{

    public function index()
    {
        $propostas = Proposta::whereStatus('A cadastrar')
            ->leftJoin('planos', 'propostas.plano_id', '=', 'planos.id')
            ->selectRaw('propostas.*, planos.nome as plano')
            ->get();

        $planos = Plano::all();

        return view('admin.propostas.index', [
            "propostas" => $propostas,
            "planos" => $planos
        ]);
    }

    public function cadastradas()
    {
        $contatos = Contato::where('status', '=', 'Aguardando aprovação')
            ->orWhere('status', '=', 'Aguardando parcelas')
            ->orWhere('status', '=', 'Pendência')
            ->orWhere('status', '=', 'Pendências corrigidas')
            ->orWhere('status', '=', 'Pendências aguardando')
            ->orWhere('status', '=', 'Aguardando vigência')
            ->orWhere('status', '=', 'Implantada')
            ->orWhere('status', '=', 'Cancelada')
            ->get();

        $contatos_formatadas = $contatos->map(function ($contato) {
            return [
                "contato" => $contato,
                "proposta" => $contato->proposta,
            ];
        });


        $planos = Plano::all();

        return view('admin.propostas.cadastradas', ["contatos" => $contatos_formatadas, "planos" => $planos]);
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

    public function cadastrar(Request $request)
    {
        $proposta = Proposta::find($request->id);

        if ($request->pdf_proposta) {
            $proposta->pdf_proposta = $request->pdf_proposta;
        }

        $proposta->status = "Aguardando aprovação";
        $proposta->save();

        $contato = Contato::find($request->contato_id);
        $contato->status = "Aguardando aprovação";
        $contato->save();

        return response()->json([
            "success" => true,
            "msg" => 'Proposta cadastrada!',
        ], Response::HTTP_OK);
    }

    public function adicionarPendencias(Request $request)
    {

        $proposta = Proposta::find($request->proposta_id);
        $proposta->descricao_pendencia = $request->descricao_pendencia;
        $proposta->status = "Pendências aguardando";
        $proposta->save();

        $contato = Contato::find($request->contato_id);
        $contato->status = "Pendências aguardando";
        $contato->save();

        return response()->json([
            "success" => true,
            "msg" => 'Pendências adicionadas!',
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {

        $proposta = Proposta::find($request->id);

        $proposta->cpf = $request->cpf;
        $proposta->cnpj = $request->cnpj;
        $proposta->identidade = $request->identidade;
        $proposta->qtd_empregados = $request->qtd_empregados;
        $proposta->tipo_empresa = $request->tipo_empresa;
        $proposta->qtd_vidas = $request->qtd_vidas;
        $proposta->nome_titular = $request->nome_titular;
        $proposta->whatsapp = $request->whatsapp;
        $proposta->tipo_proposta = $request->tipo_proposta;
        $proposta->valor_proposta = $request->valor_proposta;
        $proposta->plano_id = $request->plano_id;
        $proposta->data_vencimento = $request->data_vencimento;
        $proposta->status = "A cadastrar";

        //CASO VENHA COMPROVANTE DE RESIDÊNCIA
        if ($request->comprovante_residencia) {
            $proposta->comprovante_residencia = $request->comprovante_residencia;
        }

        //CASO VENHA CPF DO TITULAR
        if ($request->cpf_titular) {
            $proposta->cpf_titular = $request->cpf_titular;
        }

        //CASO VENHA IDENTIDADE DO TITULAR
        if ($request->identidade_titular) {
            $proposta->identidade_titular = $request->identidade_titular;
        }

        //CASO VENHA COMPROVANTE DE VÍNCULO
        if ($request->comprovante_vinculo) {
            $proposta->comprovante_vinculo = $request->comprovante_vinculo;
        }

        $proposta->save();

        return response()->json([
            "success" => true,
            "msg" => 'Proposta atualizada!',
            "id" => $proposta->id
        ], Response::HTTP_OK);
    }
}
