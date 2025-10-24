<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RelatorioController extends Controller
{

    public function ranking(Request $request)
    {

        $corretores = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'user');
            }
        )->leftjoin('leads', 'users.id', '=', 'leads.user_id')
            ->selectRaw('users.*, count(leads.id) as LeadsCount')
            ->whereNull('leads.data_avaliacao')
            ->whereDate('leads.created_at', Carbon::today())
            ->orderBy('LeadsCount', 'DESC')
            ->groupBy('users.id')
            ->get();

        return view('admin.ranking', [
            "corretores" => $corretores
        ]);
    }

    public function searchRanking(Request $request)
    {

        $corretores = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'user');
            }
        )->leftjoin('leads', 'users.id', '=', 'leads.user_id')
            ->selectRaw('users.*, count(leads.id) as LeadsCount')
            ->whereNull('leads.data_avaliacao')
            ->whereBetween('leads.created_at', [$request->data_inicio, $request->data_fim])
            ->orderBy('LeadsCount', 'DESC')
            ->groupBy('users.id')
            ->get();

        return response()->json([
            "success" => true,
            "msg" => 'Leads listados!',
            "corretores" => $corretores,
        ], Response::HTTP_OK);
    }

    public function index(Request $request)
    {
        $corretores = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'user');
            }
        )->get();

        $leads = Lead::whereDate('created_at', Carbon::today())->orderBy('id', 'DESC')->get();

        return view('admin.relatorio', [
            "leads" => $leads,
            "corretores" => $corretores
        ]);
    }

    public function search(Request $request)
    {
        $leads = Lead::whereBetween('leads.created_at', [$request->data_inicio, $request->data_fim]);

        if ($request->corretor_id != "") {
            $leads = $leads->whereUserId($request->corretor_id);
        }

        if ($request->telefone != '') {
            $leads = $leads->whereRaw('UPPER(leads.telefone) LIKE ? ', '%' . $request->telefone . '%');
        }

        if ($request->status_indicacao != "") {
            $leads = $leads->where("leads.avaliacao", "=", $request->status_indicacao);
        }

        $leads = $leads->get();

        return response()->json([
            "success" => true,
            "msg" => 'Leads listados!',
            "leads" => $leads,
        ], Response::HTTP_OK);
    }

    public function relatorioEncaminhadasSearch(Request $request)
    {
        $leads = Lead::whereBetween('leads.data_envio_corretor', [$request->data_inicio, $request->data_fim]);

        if ($request->corretor_id != "") {
            $leads = $leads->whereUserId($request->corretor_id);
        }

        $leads = $leads->get();

        return response()->json([
            "success" => true,
            "msg" => 'Leads listados!',
            "leads" => $leads,
        ], Response::HTTP_OK);
    }

    public function relatorioEncaminhadas(Request $request)
    {
        $corretores = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'user');
            }
        )->get();


        $leads = Lead::whereDate('data_envio_corretor', Carbon::today())->get();

        return view('admin.relatorioEncaminhadas', ["leads" => $leads, "corretores" => $corretores]);
    }
}
