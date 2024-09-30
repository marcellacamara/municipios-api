<?php

namespace App\Http\Controllers;

use App\Services\MunicipioService;
use Illuminate\Http\Request;
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
     * Carrega a página com a lista de UFs para o usuário escolher.
     */
    public function index()
    {
        $ufs = self::UFS_VALIDAS;
        return view('municipios', compact('ufs'));
    }

    /**
     * Lista os municípios de uma UF específica.
     *
     * @param string $uf
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse Retorna a lista de municípios em formato JSON.
     */
    public function listar($uf, Request $request)
    {
        // Verifica se a UF passada é válida
        if (!$this->ufValida($uf)) {
            return response()->json(['error' => 'UF inválida'], Response::HTTP_NOT_FOUND);
        }

        // Busca e pagina os municípios
        $page = $request->query('page', 1);
        $municipios = $this->municipioService->listarMunicipios(strtoupper($uf), $page);

        return response()->json($municipios);
    }

    /**
     * Verifica se a UF é válida.
     *
     * @param string $uf
     */
    private function ufValida($uf)
    {
        return in_array(strtoupper($uf), self::UFS_VALIDAS);
    }
}
