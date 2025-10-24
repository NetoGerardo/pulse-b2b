<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Origem;
use App\Models\Parcela;
use App\Models\Plano;
use App\Models\Proposta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FinanceiroController extends Controller
{

    public function index(Request $request)
    {

        $parcelas = Parcela::leftJoin('propostas', 'propostas.id', '=', 'parcelas.proposta_id');

        if ($request->status != "") {
            $parcelas = $parcelas->where("parcelas.status", "=", $request->status);
        }

        $parcelas = $parcelas->selectRaw('parcelas.*, propostas.nome_titular as nome, propostas.valor_proposta as valor, propostas.whatsapp as whatsapp')->get();

        return view('admin.financeiro.index', ["status" => $request->status, "parcelas" => $parcelas]);
    }

    public function search(Request $request)
    {
        $parcelas = Parcela::whereBetween('parcelas.data_vencimento', [$request->data_inicio, $request->data_fim])
            ->leftJoin('propostas', 'propostas.id', '=', 'parcelas.proposta_id');

        if ($request->status != "") {
            $parcelas = $parcelas->where("parcelas.status", "=", $request->status);
        }

        $parcelas = $parcelas->selectRaw('parcelas.*, propostas.nome_titular as nome, propostas.valor_proposta as valor, propostas.whatsapp as whatsapp')->get();

        return response()->json([
            "success" => true,
            "msg" => 'Parcelas listadas!',
            "parcelas" => $parcelas,
        ], Response::HTTP_OK);
    }

    public function receber()
    {

        $parcelas = Parcela::where("parcelas.status", "=", 'A receber')->get();

        $parcelas_formatadas = $parcelas->map(function ($parcela) {
            return [
                "parcela" => $parcela,
                "proposta" => $parcela->proposta
            ];
        });

        $planos = Plano::all();

        return view('admin.financeiro.receber', ["planos" => $planos, "parcelas" => $parcelas_formatadas]);
    }

    public function recebido()
    {

        $planos = Plano::all();

        return view('admin.financeiro.recebido', ["planos" => $planos]);
    }

    public function searchStatus(Request $request)
    {
        $parcelas = Parcela::where("parcelas.status", "=", $request->status);

        if ($request->tipo_plano != "") {
            $parcelas = $parcelas->leftJoin('propostas', 'propostas.id', '=', 'parcelas.proposta_id')
                ->where('propostas.tipo_proposta', '=', $request->tipo_plano);
        }

        $parcelas = $parcelas->get();

        $parcelas_formatadas = $parcelas->map(function ($parcela) {
            return [
                "parcela" => $parcela,
                "proposta" => $parcela->proposta
            ];
        });

        return response()->json([
            "success" => true,
            "msg" => 'Parcelas listadas!',
            "parcelas" => $parcelas_formatadas,
        ], Response::HTTP_OK);
    }
}
