<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Envio;
use App\Models\Lead;
use App\Models\Lista;
use App\Models\Mensagem;
use App\Models\Origem;
use App\Models\SolicitacaoLista;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
    {

        $leads = Lead::whereNull('user_id')
            ->where('encaminhamento_disponivel', '=', 1)
            ->leftJoin('origens', 'leads.origem_id', '=', 'origens.id')
            ->selectRaw('leads.*, origens.categoria as categoria, origens.nome as origem')
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get();

        $container = Container::whereUserId(Auth::user()->id)->first();

        $leads_recebidos = Lead::whereDate('created_at', Carbon::today())->where('origem_lead', '!=', 'CADASTRO MANUAL')->count();
        $leads_hoje = Lead::whereDate('created_at', Carbon::today())->count();
        $positivos = Lead::whereDate('created_at', Carbon::today())->whereAvaliacao('POSITIVA')->count();
        $negativos = Lead::whereDate('created_at', Carbon::today())->whereAvaliacao('NEGATIVA')->count();

        $total_usuarios = Container::all()->count();

        $origens = Origem::all();

        $solicitacoes = SolicitacaoLista::where('solicitacao_lista.status', '=', 'AGUARDANDO')
            ->leftJoin('users as corretor', 'corretor.id', '=', 'solicitacao_lista.corretor_id')

            ->leftjoin('leads', function ($join) {
                $join->on('corretor.id', '=', 'leads.user_id')
                    ->whereNull('leads.avaliacao');
            })

            ->selectRaw('solicitacao_lista.*, corretor.name as corretor, count(leads.id) as pendente_avaliacao')
            ->groupBy('solicitacao_lista.corretor_id')
            ->get();

        $listas = Lista::all();

        $corretores = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'user');
            }
        )->get();

        return view('admin.index', [
            "my_container" => $container,
            "total_usuarios" => $total_usuarios,
            "leads_recebidos" => $leads_recebidos,
            "leads_hoje" => $leads_hoje,
            "leads" => $leads,
            "positivos" => $positivos,
            "negativos" => $negativos,
            "corretores" => $corretores,
            "origens" => $origens,
            "listas" => $listas,
            "solicitacoes" => $solicitacoes,
        ]);
    }
}
