<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class IfrsApiService
{
    protected string $baseUrl;
    protected string $token;
    protected string $campusId;

    public function __construct()
    {
        $this->baseUrl = config('services.ifrs_api.url');
        $this->token = config('services.ifrs_api.token');
        $this->campusId = config('services.ifrs_api.campus_id');
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
     * Busca alunos matriculados de um curso específico (Paginado)
     */
    public function buscarAlunosPorCurso($idCursoApi, $pagina = 1)
    {
        return $this->client()->get('/matriculados', [
            'matriculado' => 'sim',
            'unidade' => $this->campusId, // Correção aqui
            'curso' => $idCursoApi,
            'per_page' => 25,
            'page' => $pagina
        ]);
    }

    public function buscarCursos()
    {
        return $this->client()->get('/cursos', [
            'campus' => $this->campusId, // Correção aqui
            'situacao_turma' => 1
        ]);
    }
}
