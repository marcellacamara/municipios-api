<?php

namespace Tests\Feature;

use App\Http\Controllers\MunicipioController;
use Tests\TestCase;

class MunicipioControllerTest extends TestCase
{
    public function test_fluxo_completo_listar_municipios_com_brasil_api()
    {
        // Requisição real para listar municípios com Brasil API
        $response = $this->get('/api/municipios/rs');

        // Verifica status 200 e estrutura do JSON
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['nome', 'codigo_ibge']
            ],
            'current_page',
            'last_page',
            'total',
            'per_page',
            'from',
            'to'
        ]);
    }

    public function test_fluxo_completo_listar_municipios_com_ibge_api()
    {
        config(['app.provider_api' => 'ibge_api']);

        // Requisição real para listar municípios com IBGE API
        $response = $this->get('/api/municipios/rs');

        // Verifica status 200 e estrutura do JSON
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['nome', 'id']
            ],
            'current_page',
            'last_page',
            'total',
            'per_page',
            'from',
            'to'
        ]);
    }

    public function test_rota_invalida_retorna_erro_404()
    {
        // Requisição para UF inválida
        $response = $this->get('/api/municipios/xyz');

        // Verifica se o status é 404
        $response->assertStatus(404);
    }

    public function test_view_de_municipios_retorna_com_sucesso()
    {
        $response = $this->get('/api/municipios');
        $response->assertStatus(200);

        $response->assertViewIs('municipios');

        // Verifica se cada UF está presente no conteúdo da página
        $ufsEsperadas = MunicipioController::UFS_VALIDAS;
        foreach ($ufsEsperadas as $uf) {
            $response->assertSee($uf);
        }
    }
}
