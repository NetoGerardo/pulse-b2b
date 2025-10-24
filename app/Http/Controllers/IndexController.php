<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Container;
use App\Models\Lista;
use App\Models\Envio;
use App\Models\Mensagem;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    public function index(Request $request)
    {

        if (Auth::user()) {

            $user = User::find(Auth::user()->id);

            Log::info($user);
            Log::info($user->hasRole('admin'));

            if ($user) {
                if ($user->altera_senha == 1) {
                    return redirect()->route('senha');
                } else if ($user->hasRole('admin')) {
                    return redirect()->route('admin.index');
                } else if ($user->hasRole('user')) {
                    return redirect()->route('user.index');
                } else if ($user->hasRole('gerente')) {
                    return redirect()->route('gerente.index');
                } else if ($user->hasRole('usuario_consulta')) {
                    return redirect()->route('usuario_consulta.index');
                }
            }
        } else {
            return view('auth.login');
        }
    }
}
