<?php

namespace Tests\Feature;

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
            '*' => ['nome', 'codigo_ibge']
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
            '*' => ['nome', 'id']
        ]);
    }

    public function test_rota_invalida_retorna_erro_404()
    {
        // Requisição para UF inválida
        $response = $this->get('/api/municipios/xyz');

        // Verifica se o status é 404
        $response->assertStatus(404);
    }
}
