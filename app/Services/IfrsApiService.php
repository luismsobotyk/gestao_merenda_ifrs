<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class IfrsApiService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = config('services.ifrs_api.url');
        $this->token = config('services.ifrs_api.token');
    }

    /**
     * Retorna o cliente HTTP pré-configurado.
     */
    protected function client(): PendingRequest
    {
        return Http::withToken($this->token)
            ->baseUrl($this->baseUrl)
            ->acceptJson()
            ->timeout(15); // Evita que o sistema trave se a API demorar a responder
    }

    /**
     * Método para testar a conexão inicial
     */
    public function testarConexao()
    {
        // Substitua '/endpoint-de-teste' por uma rota GET real e leve da API (ex: '/status', '/ping', ou '/alunos/1')
        $response = $this->client()->get('/cursos?campus=31&situacao_turma=1');

        return $response;
    }

    public function buscarCursos()
    {
        return $this->client()->get('/cursos', [
            'campus' => 31,
            'situacao_turma' => 1
        ]);
    }
}
