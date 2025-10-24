<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RelatorioOrigensController extends Controller
{

    public function index(Request $request)
    {

        $leads = Lead::selectRaw('leads.*, count(id) as quantidade')
            ->whereDate('leads.created_at', Carbon::today())
            ->selectRaw("leads.*, SUM(if(leads.status_negociacao = 'Sem contato', 1, 0)) AS sem_contato, SUM(if(leads.status_negociacao = 'Sem interesse', 1, 0)) AS sem_interesse, SUM(if(leads.status_negociacao = 'Já está sendo atendido', 1, 0)) AS ja_esta_sendo_atendido, SUM(if(leads.status_negociacao = 'Cotação enviada', 1, 0)) AS cotacao_enviada, SUM(if(leads.status_negociacao = 'Em negociação', 1, 0)) AS em_negociacao, SUM(if(leads.status_negociacao = 'Em negociação', 1, 0)) AS em_negociacao, SUM(if(leads.avaliacao IS NULL, 1, 0)) AS aguardando, SUM(if(leads.avaliacao = 'POSITIVA', 1, 0)) AS positivas, SUM(if(leads.status = 'NEGATIVA', 1, 0)) AS negativas")
            ->groupBy('leads.origem_lead')
            ->orderBy('quantidade', 'DESC')
            ->get();

        $supervisores = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'supervisor');
            }
        )->get();

        return view('admin.relatorio-origens', [
            "leads" => $leads,
            "supervisores" => $supervisores
        ]);
    }

    public function search(Request $request)
    {

        $leads = Lead::selectRaw('leads.*, count(id) as quantidade')
            ->whereBetween('leads.created_at', [$request->data_inicio, $request->data_fim])
            ->selectRaw("leads.*, SUM(if(leads.status_negociacao = 'Sem contato', 1, 0)) AS sem_contato, SUM(if(leads.status_negociacao = 'Sem interesse', 1, 0)) AS sem_interesse, SUM(if(leads.status_negociacao = 'Já está sendo atendido', 1, 0)) AS ja_esta_sendo_atendido, SUM(if(leads.status_negociacao = 'Cotação enviada', 1, 0)) AS cotacao_enviada, SUM(if(leads.status_negociacao = 'Em negociação', 1, 0)) AS em_negociacao, SUM(if(leads.status_negociacao = 'Em negociação', 1, 0)) AS em_negociacao, SUM(if(leads.avaliacao IS NULL, 1, 0)) AS aguardando, SUM(if(leads.avaliacao = 'POSITIVA', 1, 0)) AS positivas, SUM(if(leads.status = 'NEGATIVA', 1, 0)) AS negativas")
            ->groupBy('leads.origem_lead')
            ->orderBy('quantidade', 'DESC');

        if ($request->corretor_id != "") {
            $leads = $leads->whereUserId($request->corretor_id);
        }

        if ($request->origem_lead != "") {
            $leads = $leads->where("origem_lead", "=", $request->origem_lead);
        }

        $leads = $leads->get();

        return response()->json([
            "success" => true,
            "msg" => 'Leads listados!',
            "leads" => $leads,
        ], Response::HTTP_OK);
    }
}
