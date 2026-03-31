@extends('dashboard.layout')

@section('custom_css')
    <style>
        .ck-editor__editable_inline {
            min-height: 300px;
            font-size: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Criar Modelo de E-mail</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-body-tertiary text-uppercase fw-semibold" style="font-size: 0.85rem;">
                    Detalhes do Modelo
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('teste') }}" method="POST" id="formModeloEmail">
                        @csrf

                        <div class="mb-3">
                            <label for="nomeModelo" class="form-label fw-medium">Nome do Modelo (Interno)</label>
                            <input type="text" class="form-control" id="nomeModelo" name="nome_modelo" placeholder="Ex: Boas-vindas aos novos alunos" required>
                        </div>

                        <div class="mb-3">
                            <label for="assuntoEmail" class="form-label fw-medium">Assunto do E-mail</label>
                            <input type="text" class="form-control" id="assuntoEmail" name="assunto" placeholder="Ex: Bem-vindo ao IFRS, @{{nome_aluno}}!" required>
                        </div>

                        <div class="mb-3 p-3 bg-light rounded border">
                            <label class="form-label fw-medium mb-2 d-block text-secondary" style="font-size: 0.85rem;">
                                <i class="bi bi-tags-fill me-1"></i> Variáveis Dinâmicas
                            </label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary btn-variavel" data-tag="@{{nome_aluno}}">Nome do Aluno</button>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-variavel" data-tag="@{{login}}">Login</button>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-variavel" data-tag="@{{senha}}">Senha Inicial</button>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-variavel" data-tag="@{{curso}}">Curso</button>
                            </div>
                            <div class="form-text mt-2" style="font-size: 0.8rem;">
                                Clique em um botão acima para inserir a variável no texto onde o cursor estiver posicionado.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">Corpo da Mensagem</label>

                            <textarea name="mensagem_html" id="editor-container"></textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-floppy-fill me-2"></i> Salvar Modelo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 d-none d-lg-block">
            <div class="alert alert-info border-0 shadow-sm">
                <h5 class="alert-heading"><i class="bi bi-lightbulb-fill me-2"></i>Como funciona?</h5>
                <p class="small mb-0">
                    As variáveis entre chaves <code>@verbatim{{  }}@endverbatim</code> serão substituídas automaticamente pelo sistema na hora do envio.
                    Por exemplo, se você escrever "Olá <code>@verbatim{{nome_aluno}}@endverbatim</code>", o sistema enviará "Olá Fulano de Tal" para o Fulano de Tal.
                </p>
                <br />
                <p class="small mb-0">
                    <strong>As variáveis configuradas são:</strong>
                </p>
                <ul class="small mb-0">
                    <li><code>@verbatim{{nome_aluno}}@endverbatim</code>: Refere-se ao nome completo do aluno.<</li>
                    <li><code>@verbatim{{login}}@endverbatim</code>: Login utilizado para acessar os sistemas do campus.</li>
                    <li><code>@verbatim{{senha}}@endverbatim</code>: Senha inicial cadastrada na criação dos acessos aos sistemas do campus.</li>
                    <li><code>@verbatim{{curso}}@endverbatim</code>: Curso em que o aluno está matriculado</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        let myEditor;

        ClassicEditor
            .create(document.querySelector('#editor-container'), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo' ]
            })
            .then(editor => {
                myEditor = editor;
            })
            .catch(error => {
                console.error('Erro ao inicializar o CKEditor:', error);
            });

        const botoesVariaveis = document.querySelectorAll('.btn-variavel');

        botoesVariaveis.forEach(botao => {
            botao.addEventListener('click', function() {
                const tag = this.getAttribute('data-tag');

                if (myEditor) {
                    myEditor.model.change(writer => {
                        const insertPosition = myEditor.model.document.selection.getFirstPosition();
                        writer.insertText(tag, insertPosition);
                    });

                    myEditor.editing.view.focus();
                }
            });
        });

        const form = document.getElementById('formModeloEmail');

        form.addEventListener('submit', function(e) {
            const conteudo = myEditor.getData();
            if (conteudo.trim() === '') {
                e.preventDefault();
                alert('Por favor, escreva uma mensagem para o modelo de e-mail.');
            }
        });
    </script>
@endsection
