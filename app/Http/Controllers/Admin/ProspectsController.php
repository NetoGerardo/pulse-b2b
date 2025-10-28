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

        $userId = Auth::id();

        $query = Prospect::select();

        // 2. Inicia a consulta no Prospect
        $query = Prospect::query();

        // 3. Usa 'whereHas' para filtrar os prospects COM BASE
        //    em condições da sua campanha relacionada.
        $query->whereHas('campanha', function ($q) use ($request, $userId) {

            // CONDIÇÃO OBRIGATÓRIA:
            // A campanha DEVE pertencer ao usuário logado.
            $q->where('user_id', $userId);

            // FILTROS OPCIONAIS (agora aplicados com AND)
            if ($request->nome) {
                $q->where('nome', 'LIKE', '%' . $request->nome . '%');
            }

            if ($request->todas_as_etapas) {
                // Corrigido de orWhere para where.
                // Agora ele filtra: usuário X E nome Y E todas_as_etapas = 1
                $q->where('todas_as_etapas', 1);
            }
        });

        $query = $query->whereNotNull('prospects.dados');

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
