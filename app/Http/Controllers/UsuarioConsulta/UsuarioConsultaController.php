<?php

namespace App\Http\Controllers\UsuarioConsulta;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioConsultaController extends Controller
{
    public function index()
    {

        $leads = Lead::whereDate('leads.created_at', Carbon::today())->get();

        return view('usuario_consulta.index', ["leads" => $leads]);
    }
}
