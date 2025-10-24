<?php

namespace App\Http\Controllers\Gerente;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RelatorioController extends Controller
{

    public function index(Request $request)
    {
        $leads = Lead::whereUserId(Auth::user()->id)->get();

        return view('user.relatorio', [
            "leads" => $leads
        ]);
    }
}
