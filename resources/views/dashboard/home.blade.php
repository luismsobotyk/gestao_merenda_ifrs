@extends('dashboard.layout')

@section('content')
    <h1>Listar Alunos</h1>
    <hr>
    <div class="d-flex justify-content-end align-items-center gap-2 mb-3">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalFiltro">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel me-1" viewBox="0 0 16 16">
                <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2h-11z"/>
            </svg>
            Filtrar
        </button>

        <button type="button" class="btn btn-primary btn-sm">Criar Acessos</button>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col"><input class="form-check-input" type="checkbox" value="" id="checkDefault"></th>
                <th scope="col">Acessos</th>
                <th scope="col">CPF</th>
                <th scope="col">Nome</th>
                <th scope="col">Curso</th>
                <th scope="col">Ingresso</th>
                <th scope="col">Situação</th>
            </tr>
        </thead>
        <tbody>
        {{--  Dado mockados --}}
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow1"></th>
                <td><span class="badge text-bg-danger">Não criados</span></td>
                <td>12345678900</td>
                <td>Fulano de Tal</td>
                <td>Curso Superior de Tecnologia em Sistemas para Internet</td>
                <td>2026/1</td>
                <td>Ativo</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow2"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>98765432100</td>
                <td>Ana Silva</td>
                <td>Curso Técnico em Administração</td>
                <td>2025/1</td>
                <td>Ativo</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow3"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>45612378922</td>
                <td>Carlos Eduardo Pereira</td>
                <td>Curso Técnico de Informática Integrado ao Ensino Médio</td>
                <td>2024/1</td>
                <td>Concluído</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow4"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>32165498733</td>
                <td>Mariana Alves Rocha</td>
                <td>Mestrado Profissional em Informática na Educação (MPIE)</td>
                <td>2025/2</td>
                <td>Ativo</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow5"></th>
                <td><span class="badge text-bg-danger">Não criados</span></td>
                <td>78932145644</td>
                <td>João Pedro Souza</td>
                <td>Curso Técnico em Biotecnologia</td>
                <td>2026/1</td>
                <td>Ativo</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow6"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>14725836955</td>
                <td>Beatriz Lima Cardoso</td>
                <td>Curso Superior de Tecnologia em Gestão Ambiental</td>
                <td>2023/2</td>
                <td>Trancado</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow7"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>36925814766</td>
                <td>Lucas Moraes</td>
                <td>Curso Técnico em Segurança do Trabalho</td>
                <td>2025/1</td>
                <td>Ativo</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow8"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>25814736977</td>
                <td>Fernanda Costa Mendes</td>
                <td>Curso Superior de Tecnologia em Processos Gerenciais</td>
                <td>2024/2</td>
                <td>Ativo</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow9"></th>
                <td><span class="badge text-bg-danger">Não criados</span></td>
                <td>95175348688</td>
                <td>Rafael Gonçalves</td>
                <td>Curso Técnico em Administração EJA-EPT</td>
                <td>2026/1</td>
                <td>Ativo</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow10"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>75395184699</td>
                <td>Camila Ribeiro</td>
                <td>Mestrado Profissional em Educação Profissional e Tecnológica (ProfEPT)</td>
                <td>2024/1</td>
                <td>Concluído</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow11"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>85296374100</td>
                <td>Thiago Fernandes</td>
                <td>Curso de Especialização em Gestão Empresarial (GEM)</td>
                <td>2025/2</td>
                <td>Ativo</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow12"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>74185296311</td>
                <td>Letícia Martins</td>
                <td>Curso Superior de Licenciatura em Ciências da Natureza: Biologia e Química</td>
                <td>2023/1</td>
                <td>Evadido</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow13"></th>
                <td><span class="badge text-bg-danger">Não criados</span></td>
                <td>15935724822</td>
                <td>Diego Carvalho</td>
                <td>Curso Técnico em Meio Ambiente</td>
                <td>2026/1</td>
                <td>Ativo</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow14"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>35715948233</td>
                <td>Juliana Batista</td>
                <td>Curso Técnico em Biblioteconomia</td>
                <td>2025/1</td>
                <td>Ativo</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow15"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>42681357944</td>
                <td>Marcos Vinícius Dias</td>
                <td>Curso Técnico em Instrumento Musical</td>
                <td>2024/2</td>
                <td>Concluído</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow16"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>81342679555</td>
                <td>Patrícia Nunes</td>
                <td>Mestrado Profissional em Propriedade Intelectual e Transferência de Tecnologia para a Inovação (ProfNit)</td>
                <td>2025/2</td>
                <td>Ativo</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow17"></th>
                <td><span class="badge text-bg-danger">Não criados</span></td>
                <td>10293847566</td>
                <td>Felipe Castro</td>
                <td>Curso Técnico em Panificação</td>
                <td>2026/1</td>
                <td>Ativo</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow18"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>56473829177</td>
                <td>Amanda Ferreira</td>
                <td>Curso Técnico em Química</td>
                <td>2023/1</td>
                <td>Concluído</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow19"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>19283746588</td>
                <td>Rodrigo Almeida</td>
                <td>Curso Técnico em Transações Imobiliárias</td>
                <td>2025/1</td>
                <td>Trancado</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow20"></th>
                <td><span class="badge text-bg-danger">Não criados</span></td>
                <td>91827364599</td>
                <td>Carolina Farias</td>
                <td>Curso Técnico em Secretariado</td>
                <td>2026/1</td>
                <td>Ativo</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow21"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>11223344500</td>
                <td>Marcelo Borges</td>
                <td>Curso Técnico em Contabilidade</td>
                <td>2024/1</td>
                <td>Ativo</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow22"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>55667788911</td>
                <td>Larissa Barbosa</td>
                <td>Curso Superior de Tecnologia em Sistemas para Internet</td>
                <td>2025/2</td>
                <td>Ativo</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow23"></th>
                <td><span class="badge text-bg-danger">Não criados</span></td>
                <td>99887766522</td>
                <td>Bruno Vieira</td>
                <td>Curso Técnico de Administração Integrado ao Ensino Médio</td>
                <td>2026/1</td>
                <td>Ativo</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow24"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>44332211033</td>
                <td>Vanessa Gomes</td>
                <td>Curso Superior de Licenciatura em Ciências da Natureza: Biologia e Química</td>
                <td>2022/1</td>
                <td>Concluído</td>
            </tr>
            <tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="checkRow25"></th>
                <td><span class="badge text-bg-success">Criados</span></td>
                <td>66554433244</td>
                <td>Gustavo Henrique Silva</td>
                <td>Curso Técnico em Informática Integrado ao Ensino Médio</td>
                <td>2025/1</td>
                <td>Ativo</td>
            </tr>
        </tbody>
    </table>
    <div class="text-center mb-2">
        Mostrando 25 de 5.823 resultados.
    </div>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled">
                <a class="page-link">Anterior</a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#">Próxima</a>
            </li>
        </ul>
    </nav>

    <div class="modal fade" id="modalFiltro" tabindex="-1" aria-labelledby="modalFiltroLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="GET">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalFiltroLabel">Filtros de Pesquisa</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="filtroAcessos" class="form-label text-start d-block fw-medium">Acessos</label>
                            <select class="form-select" id="filtroAcessos" name="acessos">
                                <option value="">Todos</option>
                                <option value="criados">Criados</option>
                                <option value="nao_criados">Não criados</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="filtroCurso" class="form-label text-start d-block fw-medium">Curso</label>
                            <select class="form-select" id="filtroCurso" name="cursos[]" multiple size="3">
                                <option value="ads">Análise e Desenv. de Sistemas</option>
                                <option value="mat">Matemática</option>
                                <option value="adm">Administração</option>
                            </select>
                            <div class="form-text small">Segure Ctrl (Windows) ou Cmd (Mac) para selecionar vários.</div>
                        </div>

                        <div class="mb-3">
                            <label for="filtroIngresso" class="form-label text-start d-block fw-medium">Ingresso</label>
                            <input type="text" class="form-control" id="filtroIngresso" name="ingresso" placeholder="Ex: 2024/1" pattern="\d{4}/[12]" title="O formato deve ser AAAA/1 ou AAAA/2 (ex: 2024/1)">
                        </div>

                        <div class="mb-3">
                            <label for="filtroSituacao" class="form-label text-start d-block fw-medium">Situação</label>
                            <select class="form-select" id="filtroSituacao" name="situacoes[]" multiple size="3">
                                <option value="matriculado">Matriculado</option>
                                <option value="trancado">Trancado</option>
                                <option value="formado">Formado</option>
                                <option value="evadido">Evadido</option>
                            </select>
                            <div class="form-text small">Segure Ctrl (Windows) ou Cmd (Mac) para selecionar vários.</div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
