<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campanha;
use App\Models\Canal;
use App\Models\Prospect;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Importante para debugar
use Exception;

class ProspectController extends Controller
{
    /**
     * Recebe um webhook e cria um novo Prospect.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Pega o objeto principal do webhook
            $data = $request->input('call-history-was-created');

            if (!$data) {
                return response()->json(['message' => 'Estrutura do JSON inválida.'], 400);
            }

            // 1. Encontrar a Campanha
            // Pega o nome do agente conforme a regra: json.callHistory.agent.name
            $agentName = $data['callHistory']['agent']['name'] ?? null;

            $campanha = null;
            $canal = null;

            if ($agentName) {
                // 1a. Busca o Canal pelo nome do agente
                $canal = Canal::where('nome', $agentName)
                    ->first(); // 'orderByDesc' é desnecessário se o nome for único

                if ($canal) {
                    // 1b. Se encontrou o canal, busca a última campanha ativa
                    //     *relacionada* a esse canal (via many-to-many)
                    //     Assumindo que a relação no Model Canal se chama 'campanhas()'
                    $campanha = $canal->campanhas()
                        ->where('status', 'Em andamento')
                        ->orderByDesc('id') // Pega a campanha mais recente
                        ->first();
                }
            }

            Log::info("Canal encontrado:");
            Log::info($agentName);
            Log::info($canal);

            // 2. Extrair os dados do Prospect do JSON

            // Status: json.callHistory.qualification.name
            $statusLigacao = $data['callHistory']['qualification']['name'] ?? null;

            // Dados: json.callHistory.mailing_data.data
            $dadosProspect = $data['callHistory']['mailing_data']['data'] ?? null;

            // Telefone: json.callHistory.mailing_data.phone
            $telefone = $data['callHistory']['mailing_data']['phone'] ?? null;

            // Nome do canal 
            $canal_text = $data['callHistory']['call_mode'] ?? null;

            if ($statusLigacao) {
                // 3. Criar o Prospect
                $prospect = Prospect::create([
                    'campanha_id' => $campanha ? $campanha->id : null, // Salva o ID da campanha ou null
                    'canal_id' => $canal ? $canal->id : null, // Salva o ID da campanha ou null
                    'canal' => $canal_text,
                    'telefone' => $telefone,
                    'dados' => $dadosProspect, // O Model vai converter para JSON
                    'status_ligacao' => $statusLigacao,
                    // 'status_whatsapp' não foi mencionado, ficará null por padrão
                ]);
           }


            return response()->json([
                'message' => 'Prospect salvo com sucesso!',
                'data' => $prospect
            ], 201); // 201 Created

        } catch (Exception $e) {
            // Loga o erro para debug
            Log::error('Falha ao salvar prospect via webhook: ' . $e->getMessage(), [
                'request' => $request->all()
            ]);

            return response()->json([
                'message' => 'Ocorreu um erro interno ao processar a solicitação.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
