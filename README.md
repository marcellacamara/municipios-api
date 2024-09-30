# 🚀 Projeto: Municípios API

## Descrição

A **Municípios API** é uma aplicação construída em Laravel que permite a listagem de municípios brasileiros com base nas UFs. A aplicação utiliza duas fontes de dados como _providers_: a [Brasil API](https://brasilapi.com.br) e a API do [IBGE](https://servicodados.ibge.gov.br). O _provider_ é configurável através de variáveis de ambiente, tornando a aplicação flexível para diferentes cenários.

## 📝 Funcionalidades

-   Listar municípios de uma UF específica
-   Paginação dos resultados
-   Utilização de cache para otimizar o desempenho
-   Troca de _provider_ via variável de ambiente
-   Tratamento de exceções e erros
-   Frontend simples utilizando Blade e Ajax para consulta e listagem de municípios
-   Testes unitários e de integração

## 🔧 Tecnologias Utilizadas

-   Laravel 10
-   PHP 8.1+
-   Bootstrap 4.5
-   Axios para requisições HTTP assíncronas no frontend
-   Cache com Laravel Cache
-   PHPUnit e HTTP Fake para testes
-   API do Brasil API
-   API do IBGE

## 📄 Endpoints

### Listar Municípios por UF

**GET** `/api/municipios/{uf}`

-   **Descrição**: Retorna uma lista paginada de municípios para a UF informada.
-   **Parâmetro**: `{uf}` - Código da Unidade Federativa (ex.: RS, SP, MG, etc.).
-   **Resposta**:
    ```json
    {
        "data": [
            {
                "name": "Porto Alegre",
                "ibge_code": "4314902"
            },
            {
                "name": "Canoas",
                "ibge_code": "4304606"
            }
        ],
        "current_page": 1,
        "last_page": 2,
        "total": 100,
        "per_page": 50
    }
    ```

## 🚀 Instruções de Instalação

1. Clone o repositório:

    ```bash
    git clone https://github.com/marcellacamara/municipios-api.git
    cd municipios-api
    ```

2. Instale as dependências:
    ```bash
    composer install
    npm install
    ```
3. Configure o arquivo .env:

    - Copie o arquivo de exemplo .env.example:

    ```bash
    cp .env.example .env
    ```

    - Configure as credenciais do banco de dados e o provider da API:

    ```bash
    PROVIDER_API=brasil_api # Ou ibge_api
    ```

4. Gere a chave da aplicação:

    ```bash
    php artisan key:generate
    ```

5. Compile os assets:

    ```bash
    npm run dev
    ```

6. Inicie o servidor de desenvolvimento:

    ```bash
    php artisan serve
    ```

## 🚀 Testes

-   Para rodar os testes unitários e de integração, execute:

    ```bash
    php artisan test
    ```

## ⚙️ Variáveis de Ambiente

-   **PROVIDER_API**: Define o provedor da API a ser utilizado. Valores possíveis: `brasil_api` ou `ibge_api`.

## 📂 Estrutura do Projeto

-   app/Http/Controllers: Controladores responsáveis por lidar com as requisições HTTP.
-   app/Services: Serviços que encapsulam a lógica de negócio, como requisições às APIs externas.
-   routes/api.php: Definições das rotas da API.
-   tests/: Testes unitários e de integração.

## Autores

-   Feito com 💜 por [@marcellacamara](https://www.github.com/marcellacamara).
