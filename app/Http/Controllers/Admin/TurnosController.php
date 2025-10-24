<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TurnoUser;
use App\Models\Lista;
use App\Models\Turno;
use App\Models\TurnoLog;
use App\Models\User;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TurnosController extends Controller
{
    public function index()
    {
        $turnos = Turno::all();

        $formatedTurnos = $turnos->map(function ($turno) {
            return [
                "id" => $turno->id,
                "hora_inicio" => $turno->hora_inicio,
                "hora_fim" => $turno->hora_fim,
                "ordem_lista" => $turno->ordem_lista,
                "users" => $turno->users
            ];
        });

        $supervisores = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'supervisor');
            }
        )->get();

        /*
        $corretores = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'user');
            }
        )->get();
        */

        return view('admin.turnos.index', [
            "turnos" => $formatedTurnos,
            "supervisores" => $supervisores
        ]);
    }


    public function search(Request $request)
    {
        $turnos = Turno::all();

        return response()->json([
            "success" => true,
            "msg" => 'Turnos listados!',
            "turnos" => $turnos
        ], Response::HTTP_OK);
    }

    public function searchCorretoresTurno(Request $request)
    {
        $corretores = User::whereIn('id', $request->ids_corretores)->get();

        return response()->json([
            "success" => true,
            "msg" => 'Corretores listados!',
            "corretores" => $corretores
        ], Response::HTTP_OK);
    }

    protected function addTurno(Request $request)
    {

        //VERIFICA SE EXISTE A RELAÇÃO ENTRE USUÁRIO E TURNO
        $isAdded = TurnoUser::where("turno_id", $request->turno_id)
            ->where("user_id", $request->user_id)
            ->exists();

        $user = User::find($request->user_id);
        $user->turnos()->attach($request->turno_id);

        $turno = Turno::find($request->turno_id);

        $ordem_lista = json_decode($turno->ordem_lista, TRUE);

        if (!$ordem_lista) {
            $ordem_lista = array();
        }

        //Verifica se o ID já existe no array
        $existe = collect($ordem_lista)->contains('id', $request->user_id);

        if ($isAdded && !$existe) {

            $arr = array('id' => $user->id, 'nome' => $user->name, 'telefone'  => $user->telefone, 'ordem' => (sizeof($ordem_lista) + 1));

            array_push($ordem_lista, $arr);

            $turno->ordem_lista = $ordem_lista;
            $turno->save();

            return response()->json([
                "success" => false,
                "msg" => 'Corretor atualizado, verifique a lista de corretores!',
                "turno" => $turno,
            ], Response::HTTP_OK);
        } else if ($existe) {
            return response()->json([
                "success" => false,
                "msg" => 'Corretor já adicionado!',
                "turno" => $turno,
            ], Response::HTTP_OK);
        } else {

            /*
            TurnoLog::create([
                'user_id' => $request->user_id,
                'turno_id' => Auth::user()->id,
                'ip' => $this->get_client_ip(),
                'acao' => "Adicionando ao turno",
            ]);
            */

            $arr = array('id' => $user->id, 'nome' => $user->name, 'telefone'  => $user->telefone, 'ordem' => (sizeof($ordem_lista) + 1));

            array_push($ordem_lista, $arr);

            $turno->ordem_lista = $ordem_lista;
            $turno->save();

            return response()->json([
                "success" => true,
                "msg" => 'Corretor adicionado ao turno!',
                "turno" => $turno,
            ], Response::HTTP_OK);
        }
    }

    public function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }


    protected function store(Request $request)
    {

        Log::info("Chegou no create turnos!!");

        //$turno = Turno::where("hora_inicio", "=", $request->hora_inicio)->exists();

        $turno = Turno::create([
            'hora_inicio' => $request->hora_inicio,
            'hora_fim' => $request->hora_fim,
            'nome' => $request->nome,
        ]);

        Log::info($turno);

        return response()->json([
            "success" => true,
            "msg" => 'Turno criado!',
        ], Response::HTTP_OK);
    }

    protected function removerUsuario(Request $request)
    {

        $user = User::find($request->user_id);
        $user->turnos()->detach($request->turno_id);

        $turno = Turno::find($request->turno_id);

        //REMOVER DA ESPERA
        $corretoresAguardando = json_decode($turno->corretores_aguardando, true);

        if (is_array($corretoresAguardando)) {
            // 3. Filtre o array para remover o corretor com o ID específico
            // Usamos a função array_filter para criar um novo array apenas com os
            // corretores que NÃO têm o ID que queremos remover.
            $corretoresAtualizados = array_filter($corretoresAguardando, function ($corretor) use ($request) {
                // Retorna `true` para manter o elemento, `false` para remover.
                return isset($corretor['id']) && $corretor['id'] != $request->user_id;
            });

            // Reindexa o array para evitar que o PHP o transforme em um objeto
            // quando for codificado para JSON, caso as chaves fiquem fora de ordem.
            $corretoresAtualizados = array_values($corretoresAtualizados);

            // 4. Atualize o campo na model com o novo array codificado em JSON
            $turno->corretores_aguardando = json_encode($corretoresAtualizados);
        }
        
        /*
        TurnoLog::create([
            'admin_id' =>  Auth::user()->id,
            'user_id' => $request->user_id,
            'turno_id' => $turno->id,
            'ip' => $this->get_client_ip(),
            'acao' => "Removendo do turno",
        ]);
        */

        $turno->ordem_lista = json_encode($request->ordem_lista);
        $turno->save();

        return response()->json([
            "success" => true,
            "msg" => 'Usuário removido!',
            "turno" =>  $turno,
        ], Response::HTTP_OK);
    }

    protected function atualizarOrdem(Request $request)
    {

        $turno = Turno::find($request->turno_id);

        $turno->ordem_lista = json_encode($request->ordem_lista);
        $turno->save();

        return response()->json([
            "success" => true,
            "msg" => 'Ordem atualizada!',
        ], Response::HTTP_OK);
    }

    protected function delete(Request $request)
    {

        TurnoUser::whereTurnoId($request->turno_id)->delete();

        $turno = Turno::find($request->turno_id);
        $turno->delete();

        return response()->json([
            "success" => true,
            "msg" => 'Turno deletado!',
        ], Response::HTTP_OK);
    }

    protected function update(Request $request)
    {

        $turno = Turno::find($request->turno_id);

        $turno->nome = $request->nome;
        $turno->hora_inicio = $request->hora_inicio;
        $turno->hora_fim = $request->hora_fim;
        $turno->save();

        return response()->json([
            "success" => true,
            "msg" => 'Turno atualizado!',
        ], Response::HTTP_OK);
    }

    protected function updateStatus(Request $request)
    {

        Log::info("Ativo ? " .  $request->ativo);

        $turno = Turno::find($request->turno_id);

        $turno->ativo = $request->ativo;
        $turno->save();

        if ($turno->ativo == 1) {
            Turno::where('id', '!=', $request->turno_id)->update(['ativo' => 0]);
        }

        return response()->json([
            "success" => true,
            "msg" => 'Turno atualizado!',
        ], Response::HTTP_OK);
    }
}
