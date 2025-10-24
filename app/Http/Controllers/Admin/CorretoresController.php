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

class CorretoresController extends Controller
{

    public function search(Request $request)
    {

        $corretores = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'user');
            }
        )->where("supervisor_id", "=", $request->supervisor_id)
            ->get();

        return response()->json([
            "success" => true,
            "msg" => 'Corretores listados!',
            "corretores" => $corretores
        ], Response::HTTP_OK);
    }
}
