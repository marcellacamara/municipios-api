<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

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
    public function listarMunicipios($uf)
    {
        // Verifica qual provider está definido e chama o método correspondente.
        if ($this->provider == 'brasil_api') {
            return $this->listarMunicipiosBrasilAPI($uf);
        } else {
            return $this->listarMunicipiosIBGE($uf);
        }
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
        $response = Http::get("https://brasilapi.com.br/api/ibge/municipios/v1/{$uf}");

        if ($response->successful()) {
            return $response->json();
        }

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
        $response = Http::get("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$uf}/municipios");

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }
}
