<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\MunicipioService;
use Illuminate\Support\Facades\Http;

class MunicipioServiceTest extends TestCase
{
    public function test_listar_municipios_com_brasil_api()
    {
        // Simula resposta da Brasil API
        Http::fake([
            'https://brasilapi.com.br/api/ibge/municipios/v1/rs' => Http::response([
                ['nome' => 'Porto Alegre', 'codigo_ibge' => '4314902'],
                ['nome' => 'Canoas', 'codigo_ibge' => '4304606']
            ], 200)
        ]);

        $service = new MunicipioService();
        $resultados = $service->listarMunicipios('RS');

        // Verifica se a resposta contém dois municípios
        $this->assertCount(2, $resultados);
        $this->assertEquals('Porto Alegre', $resultados[0]['nome']);
        $this->assertEquals('4314902', $resultados[0]['codigo_ibge']);
    }

    public function test_listar_municipios_com_ibge_api()
    {
        // Simula resposta da API do IBGE
        Http::fake([
            'https://servicodados.ibge.gov.br/api/v1/localidades/estados/rs/municipios' => Http::response([
                ['nome' => 'Porto Alegre', 'id' => '4314902'],
                ['nome' => 'Canoas', 'id' => '4304606']
            ], 200)
        ]);

        config(['app.provider_api' => 'ibge_api']);
        $service = new MunicipioService();
        $resultados = $service->listarMunicipios('RS');

        // Verifica se a resposta contém dois municípios
        $this->assertCount(2, $resultados);
        $this->assertEquals('Porto Alegre', $resultados[0]['nome']);
        $this->assertEquals('4314902', $resultados[0]['id']);
    }

    public function test_listar_municipios_sem_resultados()
    {
        // Simula resposta vazia
        Http::fake([
            'https://brasilapi.com.br/api/ibge/municipios/v1/rs' => Http::response([], 200)
        ]);

        $service = new MunicipioService();
        $resultados = $service->listarMunicipios('RS');

        // Verifica se a resposta é um array vazio
        $this->assertEmpty($resultados);
    }

    public function test_listar_municipios_com_erro_na_api()
    {
        // Simula erro na API
        Http::fake([
            'https://brasilapi.com.br/api/ibge/municipios/v1/rs' => Http::response(null, 500)
        ]);

        $service = new MunicipioService();
        $resultados = $service->listarMunicipios('RS');

        // Verifica se a resposta é um array vazio em caso de erro
        $this->assertEmpty($resultados);
    }

    public function test_listar_municipios_com_uf_invalida()
    {
        // Simula resposta vazia para UF inválida
        Http::fake([
            'https://brasilapi.com.br/api/ibge/municipios/v1/ZZ' => Http::response([], 200)
        ]);

        $service = new MunicipioService();
        $resultados = $service->listarMunicipios('ZZ');

        // Verifica se a resposta é um array vazio para UF inválida
        $this->assertEmpty($resultados);
    }

    public function test_listar_municipios_com_provider_invalido()
    {
        config(['app.provider_api' => 'provider_invalido']);
        $service = new MunicipioService();

        // Verifica se uma exceção é lançada para provider inválido
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Provider inválido: provider_invalido');
        $service->listarMunicipios('RS');
    }

    public function test_listar_municipios_com_timeout()
    {
        // Simula timeout na API
        Http::fake([
            'https://brasilapi.com.br/api/ibge/municipios/v1/rs' => Http::response(null, 408)
        ]);

        $service = new MunicipioService();
        $resultados = $service->listarMunicipios('RS');

        // Verifica se a resposta é um array vazio em caso de timeout
        $this->assertEmpty($resultados);
    }
}
