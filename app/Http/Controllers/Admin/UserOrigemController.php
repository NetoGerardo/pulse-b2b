<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Origem;
use App\Models\User;
use App\Models\UserOrigem;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class UserOrigemController extends Controller
{

    public function add(Request $request)
    {

        $user = User::find($request->user_id);
        $user->origens()->syncWithoutDetaching([$request->origem_id]);

        return response()->json([
            "success" => true,
            "msg" => 'Origem adicionada com sucesso!',
        ], Response::HTTP_OK);
    }


    public function delete(Request $request)
    {

        $user = User::find($request->user_id);
        $user->origens()->detach($request->origem_id);

        return response()->json([
            "success" => true,
            "msg" => 'Origem removida com sucesso!',
        ], Response::HTTP_OK);
    }


    protected function search(Request $request)
    {

        $query = UserOrigem::leftJoin('origens', 'origens.id', 'user_origens.origem_id');

        if ($request->user_id) {
            $query = $query->where('user_id', $request->user_id);
        }

        $total = $query->count();

        $user_origens = $query->skip(0)
            ->take(10000)
            ->get();

        return response()->json([
            "success" => true,
            "msg" => 'Origens listadas!',
            "user_origens" => $user_origens,
            "total" => $total,
        ], Response::HTTP_OK);
    }
}
