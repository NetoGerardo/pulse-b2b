<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Historico;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HistoricoController extends Controller
{

    protected function store(Request $request)
    {

        $user = User::whereEmail($request->email)->first();

        Historico::create([
            'user_id' => $user->id,
            'total_lista' => $request->total_lista,
            'total_validados' => $request->total_validados,
            'total_repetidos' => $request->total_repetidos,
        ]);

        return response()->json([
            "success" => true,
            "msg" => 'Histórico salvo!',
        ], Response::HTTP_OK);
    }
}
