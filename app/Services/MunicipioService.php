<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class MunicipioService
{
    protected $provider;

    /**
     * Inicializa a classe definindo qual provider será utilizado com base na variável de ambiente.
     */
    public function __construct()
    {
        $this->provider = config('app.provider_api', 'brasil_api');
    }

    /**
     * Retorna a lista de municípios de uma UF específica.
     *
     * @param string $uf
     *
     * @return array Lista de municípios com nome e código IBGE.
     */
    public function listarMunicipios($uf, $page = 1, $perPage = 10)
    {
        $uf = strtoupper($uf);
        $cacheKey = "municipios_{$this->provider}_{$uf}";

        // Recupera ou armazena os municípios no cache
        $municipios = Cache::remember($cacheKey, 3600, function () use ($uf) {
            try {
                switch ($this->provider) {
                    case 'brasil_api':
                        return $this->listarMunicipiosBrasilAPI($uf);
                    case 'ibge_api':
                        return $this->listarMunicipiosIBGE($uf);
                    default:
                        throw new \Exception("Provider inválido: {$this->provider}");
                }
            } catch (\Exception $e) {
                Log::error("Erro ao listar municípios para a UF: {$uf} - Provider: {$this->provider} - Erro: {$e->getMessage()}");
                throw $e;
            }
        });

        // Cria um LengthAwarePaginator para paginar os resultados
        $start = ($page - 1) * $perPage;
        $items = array_slice($municipios, $start, $perPage);
        return new LengthAwarePaginator($items, count($municipios), $perPage, $page, [
            'path' => url()->current()
        ]);
    }

    /**
     * Busca os municípios utilizando a Brasil API.
     *
     * @param string $uf
     *
     * @return array Lista de municípios obtida da Brasil API.
     */
    protected function listarMunicipiosBrasilAPI($uf)
    {
        $ufMin = strtolower($uf);

        $response = Http::get("https://brasilapi.com.br/api/ibge/municipios/v1/{$ufMin}");

        if ($response->successful() && is_array($response->json())) {
            Log::info("Brasil API: Requisição bem-sucedida para {$ufMin}");
            return $response->json();
        }

        Log::warning("Brasil API: Falha ao buscar municípios para {$ufMin}");
        return [];
    }

    /**
     * Busca os municípios utilizando a API do IBGE.
     *
     * @param string $uf
     *
     * @return array Lista de municípios obtida da API do IBGE.
     */
    protected function listarMunicipiosIBGE($uf)
    {
        $ufMin = strtolower($uf);

        $response = Http::get("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$ufMin}/municipios");

        if ($response->successful() && is_array($response->json())) {
            Log::info("IBGE API: Requisição bem-sucedida para {$ufMin}");
            return $response->json();
        }

        Log::warning("IBGE API: Falha ao buscar municípios para {$ufMin}");
        return [];
    }
}
