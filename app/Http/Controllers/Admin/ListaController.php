<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contato;
use App\Models\Lead;
use App\Models\Lista;
use App\Models\Origem;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ListaController extends Controller
{
    public function importar()
    {
        $origens = Origem::all();

        $corretores = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'user');
            }
        )->get();

        return view('admin.listas.index', [
            'origens' => $origens,
            'corretores' => $corretores
        ]);
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        $contatos = $request->contatos;
        $contatosFormatados = [];

        foreach ($contatos as $contato) {
            $new = array(
                'nome' =>  $contato['nome'],
                'telefone' => $contato['telefone'],
                'possui_cnpj' => $contato['possui_cnpj'],
                'created_at' => Carbon::now(),
                'lista_id' => $request->lista_id,
                'encaminhamento_disponivel' => 0,
            );

            if ($request->user_id) {
                $new['user_id'] = $request->user_id;
                $new['data_envio_corretor'] = Carbon::now();
            }

            if ($request->origem_id) {
                $new['origem_id'] = $request->origem_id;
                $new['origem_lead'] = $request->origem;
            }

            array_push($contatosFormatados, $new);
        }

        $lista = Lista::find($request->lista_id);
        $lista->total_numeros = intval($lista->total_numeros) + sizeof($contatosFormatados);
        $lista->numeros_disponiveis = intval($lista->numeros_disponiveis) + sizeof($contatosFormatados);
        $lista->save();

        Lead::insert($contatosFormatados);

        DB::commit();

        return response()->json([
            'success' => true,
            'msg' => 'Lista importada!',
        ], Response::HTTP_OK);
    }


    //IMPORTAÇÃO COM ENVIO DIRETO PARA O CORRETOR
    //SEM PASSAR POR LISTAS
    public function oldstore(Request $request)
    {
        $contatos = $request->contatos;
        $leadsFormatados = [];
        $contatosFormatados = [];

        foreach ($contatos as $contato) {
            $new = array(
                'nome' =>  $contato['nome'],
                'telefone' => $contato['telefone'],
                'possui_cnpj' => $contato['possui_cnpj'],
                'created_at' => Carbon::now(),
            );

            if ($request->user_id) {
                $new['user_id'] = $request->user_id;
                $new['data_envio_corretor'] = Carbon::now();
            }

            if ($request->origem_id) {
                $new['origem_id'] = $request->origem_id;
                $new['origem_lead'] = $request->origem;
            }

            //Caso o lead venha do site, vai direto para o kanban
            if ($request->origem == 'Google Oficial') {

                $new['avaliacao'] = 'POSITIVA';
                $new['data_avaliacao'] = Carbon::now();

                $newContato = array(
                    'nome' => $contato['nome'],
                    'telefone' => $contato['telefone'],
                    'possui_cnpj' => $contato['possui_cnpj'],
                    'status' => 'Cadastrado',
                    'created_at' => Carbon::now(),
                );

                if ($request->user_id) {
                    $newContato['user_id'] = $request->user_id;
                }

                array_push($contatosFormatados, $newContato);
            }

            array_push($leadsFormatados, $new);
        }

        Lead::insert($leadsFormatados);

        if ($request->origem == 'Google Oficial') {
            Contato::insert($contatosFormatados);
        }

        return response()->json([
            'success' => true,
            'msg' => 'Lista importada!',
        ], Response::HTTP_OK);
    }
}
