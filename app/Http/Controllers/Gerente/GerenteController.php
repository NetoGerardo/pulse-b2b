<?php

namespace App\Http\Controllers\Gerente;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GerenteController extends Controller
{
    public function index()
    {

        $leads = Lead::leftJoin('users as corretor', 'leads.user_id', '=', 'corretor.id')
            ->leftJoin('users as supervisor', 'corretor.supervisor_id', '=', 'supervisor.id')
            ->whereDate('leads.created_at', Carbon::today())
            ->where('supervisor.gerente_id', Auth::user()->id)
            ->selectRaw('leads.*')
            ->get();

        return view('gerente.index', ['leads' => $leads]);
    }

    protected function search(Request $request)
    {

        $leads = Lead::leftJoin('users as supervisor', 'leads.user_id', '=', 'supervisor.id')
            ->where('supervisor.gerente_id', Auth::user()->id)
            ->whereBetween('leads.created_at', [$request->data_inicio, $request->data_fim])
            ->get();

        return response()->json([
            "success" => true,
            "msg" => 'Chave atualizada!',
            "leads" => $leads
        ], Response::HTTP_OK);
    }
}
