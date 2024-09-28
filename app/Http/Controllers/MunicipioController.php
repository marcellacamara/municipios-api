<?php

namespace App\Http\Controllers;

use App\Services\MunicipioService;
use Symfony\Component\HttpFoundation\Response;

class MunicipioController extends Controller
{
    protected $municipioService;

    // Constante com a lista de UFs válidas
    const UFS_VALIDAS = [
        'AC',
        'AL',
        'AP',
        'AM',
        'BA',
        'CE',
        'DF',
        'ES',
        'GO',
        'MA',
        'MT',
        'MS',
        'MG',
        'PA',
        'PB',
        'PR',
        'PE',
        'PI',
        'RJ',
        'RN',
        'RS',
        'RO',
        'RR',
        'SC',
        'SP',
        'SE',
        'TO'
    ];

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
        // Verifica se a UF passada é válida
        if (!in_array(strtoupper($uf), self::UFS_VALIDAS)) {
            return response()->json(['error' => 'UF inválida'], Response::HTTP_NOT_FOUND);
        }

        $municipios = $this->municipioService->listarMunicipios($uf);

        return response()->json($municipios);
    }
}
