<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campanha;
use App\Models\Estado;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProspectsController extends Controller
{

    public function search(Request $request)
    {

        $query = Prospect::select();

        if ($request->nome) {
            $query = $query->where('campanhas.nome', 'LIKE', '%' . $request->nome . '%');
        }

        if ($request->user_id) {
            $query = $query->where('campanhas.user_id', '=', $request->user_id);
        }

        if ($request->todas_as_etapas) {
            $query = $query->orWhere('campanhas.todas_as_etapas', '=', 1);
        }

        $total = $query->count();

        $prospects = $query->orderBy('id', 'DESC')
            ->skip($request->inicio)
            ->take($request->tamanho)
            ->get();

        return response()->json([
            "success" => true,
            "msg" => 'Campanhas listadas!',
            "prospects" => $prospects,
            "total" => $total,
        ], Response::HTTP_OK);
    }
}
