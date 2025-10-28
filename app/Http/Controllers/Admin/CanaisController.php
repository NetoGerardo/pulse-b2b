<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campanha;
use App\Models\Canal;
use App\Models\CanalCampanha;
use App\Models\Estado;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CanaisController extends Controller
{
    public function prospects_canais(Request $request)
    {
        // Guarda o ID da campanha para usar na sub-query
        $campanha = Campanha::find($request->campanha_id);

        // 1. Busca os canais relacionados (belongsToMany) a esta campanha
        $canais = $campanha->canais()

            // 2. Seleciona apenas os campos que queremos do canal
            ->select('canais.id', 'canais.nome')

            // 3. Adiciona a contagem de 'prospects' para cada canal
            ->withCount([
                // Carrega a contagem da relação 'prospects' (do model Canal)
                // e dá o apelido de 'total_prospects'
                'prospects as total_prospects' => function ($query) use ($campanha) {

                    // 4. AQUI ESTÁ A MÁGICA:
                    // Filtra a contagem de prospects para incluir
                    // apenas aqueles que pertencem a ESTA campanha
                    $query->where('campanha_id', $campanha->id);
                }
            ])

            // 5. Executa a query
            ->get();

        return response()->json([
            "success" => true,
            "msg" => 'Canais listados!',
            "prospects_canais" => $canais,
        ], Response::HTTP_OK);
    }

    public function search(Request $request)
    {

        $query = Canal::select();

        $total = $query->count();

        $canais = $query->orderBy('nome', 'ASC')
            ->skip(0)
            ->take(1000)
            ->get();

        return response()->json([
            "success" => true,
            "msg" => 'Canais listados!',
            "canais" => $canais,
            "total" => $total,
        ], Response::HTTP_OK);
    }

    public function canais_campanha_search(Request $request)
    {
        // 3. Buscar a campanha (findOrFail já retorna 404 se não achar)
        $campanha = Campanha::findOrFail($request->campanha_id);

        // 4. Carregar e retornar os canais relacionados
        // Graças ao método 'canais()' no model, o Laravel faz todo o trabalho.
        $canais = $campanha->canais;

        // 5. Retornar os canais como JSON
        return response()->json([
            "success" => true,
            "msg" => 'Canais listados!',
            "canais" => $canais,
        ], Response::HTTP_OK);
    }
}
