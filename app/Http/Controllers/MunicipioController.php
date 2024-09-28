<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MunicipioService;

class MunicipioController extends Controller
{
    protected $municipioService;

    /**
     * @param MunicipioService $municipioService Instância do serviço de municípios.
     */
    public function __construct(MunicipioService $municipioService)
    {
        $this->municipioService = $municipioService;
    }

    /**
     * Lista os municípios de uma UF específica.
     *
     * @param string $uf
     *
     * @return \Illuminate\Http\JsonResponse Retorna a lista de municípios em formato JSON.
     */
    public function listar($uf)
    {
        $municipios = $this->municipioService->listarMunicipios($uf);

        return response()->json($municipios);
    }
}
