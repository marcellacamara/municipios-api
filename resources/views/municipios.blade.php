@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm p-4 mb-4 bg-white rounded" style="max-width: 600px; margin: auto;">
        <h1 class="text-center mb-4" style="font-size: 22px; font-weight: 600;">Lista de Municípios</h1>
        <div class="form-group">
            <label for="uf" class="font-weight-bold">Selecione a UF: </label>
            <select class="form-control" id="uf" name="uf" onchange="fetchMunicipios()" aria-label="Selecione uma UF">
                <option value="">Escolha a UF</option>
                @foreach($ufs as $estado)
                <option value="{{ $estado }}">{{ $estado }}</option>
                @endforeach
            </select>
        </div>

        <div id="municipios-list" class="mt-4" style="font-size: 14px; font-weight: 400;">
            <!-- Lista de municípios-->
        </div>

        <div id="pagination-controls" class="mt-4 d-flex justify-content-between" style="display:none;">
            <button class="btn btn-outline-primary" id="prev" onclick="fetchMunicipios(pagination.prev_page_url)" disabled>Anterior</button>
            <button class="btn btn-outline-primary" id="next" onclick="fetchMunicipios(pagination.next_page_url)" disabled>Próximo</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- Axios-->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    let pagination = {};

    function fetchMunicipios(url = null) {
        const uf = document.getElementById('uf').value;

        if (!url && uf !== "") {
            url = `/api/municipios/${uf}`;
        }

        if (!uf) {
            alert('Por favor, selecione uma UF.');
            return;
        }

        axios.get(url)
            .then(response => {
                const municipios = response.data.data;
                pagination = {
                    prev_page_url: response.data.prev_page_url
                    , next_page_url: response.data.next_page_url
                };

                let listHtml = '<ul class="list-group">';

                if (municipios && municipios.length > 0) {
                    municipios.forEach(municipio => {
                        // Verifica se existe codigo_ibge, caso contrário, usa o id (para IBGE API)
                        const codigo = municipio.codigo_ibge ? municipio.codigo_ibge : municipio.id;
                        listHtml += `<li class="list-group-item"><span style="font-size: 10px; color: gray;">&#9679;</span> ${municipio.nome} (${codigo})</li>`;
                    });
                } else {
                    listHtml += '<li class="list-group-item">Nenhum município encontrado.</li>';
                }

                listHtml += '</ul>';
                document.getElementById('municipios-list').innerHTML = listHtml;

                document.getElementById('pagination-controls').style.display = 'flex';
                document.getElementById('prev').disabled = !pagination.prev_page_url;
                document.getElementById('next').disabled = !pagination.next_page_url;
            })
            .catch(error => {
                console.error(error);
                alert('Erro ao buscar municípios.');
            });
    }

</script>

@endsection
