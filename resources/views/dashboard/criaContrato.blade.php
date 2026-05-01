@extends('dashboard.layout')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Cadastrar Novo Contrato</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('contratos') }}" class="btn btn-sm btn-outline-secondary">
                Voltar para Lista
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('contrato.salvar') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-md-3">
                    <label for="cnpj" class="form-label fw-bold">CNPJ do Fornecedor</label>
                    <input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="00.000.000/0001-00" maxlength="18" required>

                    <div class="form-text d-flex align-items-center gap-2 mt-1">
                        <div id="cnpj-spinner" class="spinner-border spinner-border-sm text-primary d-none" role="status">
                            <span class="visually-hidden">Buscando...</span>
                        </div>
                        <span id="cnpj-feedback-text"></span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="nome_fornecedor" class="form-label fw-bold">Razão Social</label>
                    <input type="text" class="form-control" id="nome_fornecedor" name="nome_fornecedor" placeholder="Digite ou aguarde a busca..." required>
                </div>

                <div class="col-md-3">
                    <label for="sigla_fornecedor" class="form-label fw-bold">Sigla</label>
                    <input type="text" class="form-control" id="sigla_fornecedor" name="sigla_fornecedor" placeholder="Opcional">
                </div>

                <div class="col-md-6">
                    <label for="processo" class="form-label fw-bold">Nº do Processo / SIPAC</label>
                    <input type="text" class="form-control" id="processo" name="processo" placeholder="Ex: 23344.001234/2026-10" maxlength="20" required>
                </div>

                <div class="col-md-4">
                    <label for="pregao" class="form-label fw-bold">Ano / Pregão</label>
                    <input type="text" class="form-control" id="pregao" name="pregao" placeholder="Ex: 05/2026" maxlength="11" required>
                </div>

                <div class="col-md-4">
                    <label for="inicio_vigencia" class="form-label fw-bold">Início da Vigência</label>
                    <input type="date" class="form-control" id="inicio_vigencia" name="inicio_vigencia" required>
                </div>

                <div class="col-md-4">
                    <label for="fim_vigencia" class="form-label fw-bold">Fim da Vigência</label>
                    <input type="date" class="form-control" id="fim_vigencia" name="fim_vigencia" required>
                </div>

                <div class="col-md-6">
                    <label for="valor_global" class="form-label fw-bold">Valor Global</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" class="form-control" id="valor_global" name="valor_global" placeholder="0,00" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="email_contato" class="form-label fw-bold">E-mail do Contato Principal</label>
                    <div class="input-group">
        <span class="input-group-text">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
            </svg>
        </span>
                        <input type="email" class="form-control" id="email_contato" name="email_contato" placeholder="contato@empresa.com.br" required>
                    </div>
                </div>
                <div class="col-12 mt-4">
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Itens do Contrato</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="btn-add-item">+ Adicionar Alimento</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered bg-white shadow-sm" id="tabela-itens">
                            <thead class="table-light small text-uppercase">
                            <tr>
                                <th>Nome do Alimento</th>
                                <th width="15%">Unidade</th>
                                <th width="15%">Quantidade</th>
                                <th width="18%">Valor Unit. (R$)</th>
                                <th width="5%"></th>
                            </tr>
                            </thead>
                            <tbody id="tbody-itens">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn btn-success px-4">Salvar Contrato</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('custom_js')
    <script>
        document.getElementById('cnpj').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');

            value = value.replace(/^(\d{2})(\d)/, '$1.$2');
            value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');

            e.target.value = value;
        });

        document.getElementById('processo').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');

            value = value.replace(/^(\d{5})(\d)/, '$1.$2');
            value = value.replace(/\.(\d{6})(\d)/, '.$1/$2');
            value = value.replace(/\/(\d{4})(\d)/, '/$1-$2');

            e.target.value = value;
        });

        document.getElementById('pregao').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');

            if (value.length > 4) {
                value = value.replace(/(\d+)(\d{4})$/, '$1/$2');
            }
            e.target.value = value;
        });

        document.getElementById('valor_global').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value === '') {
                e.target.value = '';
                return;
            }
            value = parseInt(value, 10).toString();
            if (value.length <= 2) {
                value = value.padStart(3, '0');
            }
            let cents = value.slice(-2);
            let integers = value.slice(0, -2);
            integers = integers.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            e.target.value = integers + ',' + cents;
        });

        document.getElementById('email_contato').addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/\s/g, '');
        });

        document.getElementById('cnpj').addEventListener('blur', function() {
            let cnpj = this.value.trim();
            let feedbackText = document.getElementById('cnpj-feedback-text');
            let spinner = document.getElementById('cnpj-spinner');
            let nomeInput = document.getElementById('nome_fornecedor');
            let siglaInput = document.getElementById('sigla_fornecedor');

            if (cnpj.length < 14) {
                feedbackText.innerText = "";
                spinner.classList.add('d-none');
                return;
            }

            spinner.classList.remove('d-none');
            feedbackText.innerText = "Buscando CNPJ...";
            feedbackText.className = "text-primary";

            fetch("{{ route('fornecedor.busca.cnpj') }}?cnpj=" + encodeURIComponent(cnpj))
                .then(response => {
                    if (response.ok) return response.json();
                    throw new Error('Novo');
                })
                .then(data => {
                    spinner.classList.add('d-none');

                    nomeInput.value = data.nome;
                    nomeInput.readOnly = true;

                    siglaInput.value = data.sigla || '';
                    siglaInput.readOnly = true;

                    feedbackText.innerText = "Fornecedor encontrado e vinculado!";
                    feedbackText.className = "text-success fw-bold";
                })
                .catch(error => {
                    spinner.classList.add('d-none');

                    nomeInput.value = "";
                    nomeInput.readOnly = false;

                    siglaInput.value = "";
                    siglaInput.readOnly = false;

                    document.getElementById('email_contato').value = "";
                    document.getElementById('email_contato').readOnly = false;

                    feedbackText.innerText = "Novo fornecedor. Preencha os campos ao lado.";
                    feedbackText.className = "text-warning text-dark fw-bold";
                });
        });

        let itemIndex = 0;

        const unidadesHtml = `@foreach($unidades as $unidade)<option value="{{ $unidade->id }}">{{ $unidade->sigla }} - {{ $unidade->descricao }}</option>@endforeach`;

        function adicionarLinhaItem() {
            const tbody = document.getElementById('tbody-itens');
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td><input type="text" name="itens[${itemIndex}][nome]" class="form-control" placeholder="Ex: Arroz Branco Tipo 1" required></td>
                <td>
                    <select name="itens[${itemIndex}][unidade_uuid]" class="form-select" required>
                        <option value="" disabled selected>Sel...</option>
                        ${unidadesHtml}
                    </select>
                </td>
                <td><input type="text" name="itens[${itemIndex}][quantidade]" class="form-control mask-item-numero" placeholder="0,00" required></td>
                <td><input type="text" name="itens[${itemIndex}][valor_unitario]" class="form-control mask-item-numero" placeholder="0,00" required></td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item" title="Remover">X</button>
                </td>
            `;
            tbody.appendChild(tr);
            itemIndex++;
        }

        adicionarLinhaItem();

        document.getElementById('btn-add-item').addEventListener('click', adicionarLinhaItem);

        document.getElementById('tbody-itens').addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-item')) {
                const totalLinhas = document.querySelectorAll('#tbody-itens tr').length;

                if (totalLinhas > 1) {
                    e.target.closest('tr').remove();
                } else {
                    alert('Atenção: O contrato precisa ter pelo menos um alimento cadastrado!');
                }
            }
        });

        document.getElementById('tbody-itens').addEventListener('input', function(e) {
            if (e.target.classList.contains('mask-item-numero')) {
                let value = e.target.value.replace(/\D/g, '');
                if (value === '') { e.target.value = ''; return; }

                value = parseInt(value, 10).toString();
                if (value.length <= 2) { value = value.padStart(3, '0'); }

                let cents = value.slice(-2);
                let integers = value.slice(0, -2);
                if(integers === '') integers = '0';

                integers = integers.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                e.target.value = integers + ',' + cents;
            }
        });
    </script>
@endsection
