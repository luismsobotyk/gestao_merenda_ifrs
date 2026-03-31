@extends('dashboard.layout')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Importar Arquivos</h1>
    </div>

    <div class="row justify-content-center align-items-center" style="min-height: 60vh;">

        <div class="col-md-8 col-lg-6">

            <div class="alert alert-danger" role="alert" id="alerta-erro-tamanho" hidden>
                O arquivo não pode ter mais de 5MB.
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-body-tertiary text-uppercase fw-semibold d-flex justify-content-between align-items-center" style="font-size: 0.85rem;">
                    <span>Upload de Lista</span>
                    <button type="button" class="btn btn-link p-0 text-decoration-none text-secondary" data-bs-toggle="modal" data-bs-target="#modalInstrucoes" title="Ver orientações">
                        <i class="bi bi-info-circle fs-5"></i>
                    </button>
                </div>

                <div class="card-body text-center p-5">
                    <form action="#" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <i class="bi bi-file-earmark-arrow-up text-primary" style="font-size: 3.5rem; width: auto; height: auto;"></i>

                            <label for="arquivoUpload" class="form-label fw-medium d-block mt-3 fs-5">
                                Selecione o arquivo no seu computador
                            </label>

                            <input class="form-control mx-auto mt-3" type="file" id="arquivoUpload" name="documento" style="max-width: 400px;" accept=".xls" required>

                            <div class="form-text small mt-2">
                                Formatos suportados: XLS (Excel 97-2003)<br>Tamanho máximo: 5MB
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            <button type="submit" class="btn btn-primary px-4 py-2">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Enviar Arquivo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="modalInstrucoes" tabindex="-1" aria-labelledby="modalInstrucoesLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalInstrucoesLabel">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>
                        Estrutura do Arquivo
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-start" style="font-size: 0.95rem;">
                    <p>Para que o upload funcione corretamente, seu arquivo <strong>.xls</strong> deve seguir as regras abaixo:</p>

                    <ul class="mb-4">
                        <li><strong>Colunas obrigatórias:</strong>Nome, CPF, Matrícula, Email e Nome Curso;</li>
                        <li><strong>Nome Social:</strong> Caso exista a coluna Nome Social e esteja preenchida, essa será utilizada para criação do usuário;</li>
                        <li><strong>Nome Curso:</strong> O nome dos cursos deve seguir o padrão extraído do SIGAA.</li>
                    </ul>

                    <div class="alert alert-warning py-2 mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        A primeira linha do arquivo deve obrigatoriamente conter o cabeçalho.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Entendi</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Seleciona o seu campo de input pelo ID
        const campoUpload = document.getElementById('arquivoUpload');
        campoUpload.addEventListener('change', function() {
            const limiteBytes = 5242880;
            if (this.files.length > 0) {
                const tamanhoArquivo = this.files[0].size;
                if (tamanhoArquivo > limiteBytes) {
                    document.getElementById("alerta-erro-tamanho").hidden = false;
                    this.value = '';
                }else{
                    document.getElementById("alerta-erro-tamanho").hidden = true;
                }
            }
        });
    </script>
@endsection
