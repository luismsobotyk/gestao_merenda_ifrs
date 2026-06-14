@extends('dashboard.layout')

@section('custom_css')
    <style>
        .sus-admin-wrap { max-width: 1180px; }
        .sus-card { border: 0; border-radius: 14px; box-shadow: 0 1px 4px rgba(15,31,22,.06), 0 8px 28px rgba(15,31,22,.08); }
        .sus-section-title { font-size: .78rem; font-weight: 700; letter-spacing: .09em; text-transform: uppercase; color: #6b8778; margin-bottom: .9rem; }
        .sus-muted { color: #5c7167; }
        .task-table th { font-size: .72rem; text-transform: uppercase; letter-spacing: .06em; color: #6b8778; background: #f3f7f4; }
        .task-table td, .task-table th { vertical-align: middle; }
        .task-id { font-weight: 700; color: #14422A; white-space: nowrap; }
        .task-label { min-width: 320px; font-size: .88rem; line-height: 1.35; }
        .task-module { display: inline-flex; padding: 2px 8px; border-radius: 999px; font-size: .68rem; font-weight: 700; white-space: nowrap; }
        .mod-publico { background: #D4EFE0; color: #1B5C3B; }
        .mod-auth { background: #E3ECFD; color: #1A4EA0; }
        .mod-gestao { background: #FEF3DC; color: #A05C00; }
        .mod-contrato { background: #EDE9FE; color: #4C2A99; }
        .mod-merenda { background: #EEF8F3; color: #22754C; }
        .mod-dados { background: #F0F9FF; color: #075985; }
        .save-dot { width: 8px; height: 8px; border-radius: 50%; background: #2A9060; display: inline-block; margin-right: .4rem; }
        .autosave-badge { font-size: .8rem; color: #5c7167; }
    </style>
@endsection

@section('content')
    @php
        $p = $payload['participante'] ?? [];
        $tarefas = $payload['tarefas']['resultados'] ?? [];
        $obsTarefas = $payload['tarefas']['obs'] ?? '';

        $tasks = [
            ['id'=>'T1',  'mod'=>'público',  'cls'=>'mod-publico',  'label'=>'Identifique o cardápio escolar atual disponível no sistema.'],
            ['id'=>'T2',  'mod'=>'público',  'cls'=>'mod-publico',  'label'=>'Localize o cardápio da próxima semana.'],
            ['id'=>'T3',  'mod'=>'auth',     'cls'=>'mod-auth',     'label'=>'Autentique-se no sistema usando suas credenciais LDAP.'],
            ['id'=>'T4',  'mod'=>'gestão',   'cls'=>'mod-gestao',   'label'=>'Sincronize os cursos da instituição a partir do sistema externo (CTA).'],
            ['id'=>'T5',  'mod'=>'gestão',   'cls'=>'mod-gestao',   'label'=>'Habilite o acesso à merenda escolar para uma turma ou grupo de estudantes.'],
            ['id'=>'T6',  'mod'=>'gestão',   'cls'=>'mod-gestao',   'label'=>'Sincronize os discentes da instituição a partir do sistema externo (CTA).'],
            ['id'=>'T7',  'mod'=>'contrato', 'cls'=>'mod-contrato', 'label'=>'Identifique os contratos ativos cadastrados no sistema.'],
            ['id'=>'T8',  'mod'=>'contrato', 'cls'=>'mod-contrato', 'label'=>'Cadastre um novo contrato incluindo ao menos um alimento.'],
            ['id'=>'T9',  'mod'=>'contrato', 'cls'=>'mod-contrato', 'label'=>'Edite os dados do contrato recém-cadastrado.'],
            ['id'=>'T10', 'mod'=>'contrato', 'cls'=>'mod-contrato', 'label'=>'Visualize os dados completos de um contrato existente.'],
            ['id'=>'T11', 'mod'=>'contrato', 'cls'=>'mod-contrato', 'label'=>'Cadastre um empenho vinculado ao contrato e visualize seus dados.'],
            ['id'=>'T12', 'mod'=>'merenda',  'cls'=>'mod-merenda',  'label'=>'Faça um pedido de merenda para a semana seguinte.'],
            ['id'=>'T13', 'mod'=>'merenda',  'cls'=>'mod-merenda',  'label'=>'Atualize o status de um pedido para "Recebido".'],
            ['id'=>'T14', 'mod'=>'merenda',  'cls'=>'mod-merenda',  'label'=>'Visualize a lista de pedidos realizados.'],
            ['id'=>'T15', 'mod'=>'contrato', 'cls'=>'mod-contrato', 'label'=>'Identifique outros contratos vinculados à mesma empresa fornecedora.'],
            ['id'=>'T16', 'mod'=>'merenda',  'cls'=>'mod-merenda',  'label'=>'Cadastre um novo cardápio para uma data futura.'],
            ['id'=>'T17', 'mod'=>'merenda',  'cls'=>'mod-merenda',  'label'=>'Simule ou execute a retirada de merenda por um estudante.'],
            ['id'=>'T18', 'mod'=>'dados',    'cls'=>'mod-dados',    'label'=>'Analise os dados de retirada de merenda em formato de gráfico.'],
            ['id'=>'T19', 'mod'=>'dados',    'cls'=>'mod-dados',    'label'=>'Visualize o gráfico de acessos mais frequentes, sobras e distribuição por turno.'],
        ];
    @endphp

    <div class="sus-admin-wrap">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2 mb-1">Observação da avaliação</h1>
                <div class="sus-muted">
                    Participante: <strong>{{ $avaliacao->ldap_username }}</strong>
                    @if($avaliacao->submitted_at)
                        · <span class="badge bg-success">Submetida em {{ $avaliacao->submitted_at->format('d/m/Y H:i') }}</span>
                    @else
                        · <span class="badge bg-warning text-dark">Rascunho do participante</span>
                    @endif
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <span class="autosave-badge"><span class="save-dot"></span><span id="save-status">Autosave ativo</span></span>
                <button class="btn btn-sm btn-primary" type="button" onclick="salvarModeracaoAgora()">Salvar agora</button>
                <a href="{{ route('avaliacao.respostas') }}" class="btn btn-sm btn-outline-secondary">Voltar às respostas</a>
            </div>
        </div>

        <div class="card sus-card mb-3">
            <div class="card-body">
                <div class="sus-section-title">Identificação registrada pelo moderador</div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Código do participante</label>
                        <input class="form-control tracked" id="p-codigo" type="text" value="{{ $p['codigo'] ?? '' }}" placeholder="P01, P02…">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Data</label>
                        <input class="form-control tracked" id="p-data" type="date" value="{{ $p['data'] ?? now()->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hora de início</label>
                        <input class="form-control tracked" id="p-hora" type="time" value="{{ $p['hora'] ?? now()->format('H:i') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Moderador</label>
                        <input class="form-control tracked" id="p-moderador" type="text" value="{{ $p['moderador'] ?? '' }}" placeholder="Nome do pesquisador">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Função na escola</label>
                        <select class="form-select tracked" id="p-perfil">
                            @foreach(['', 'Diretor(a)', 'Secretário(a) escolar', 'Responsável pela merenda', 'Servidor administrativo', 'Professor(a)', 'Gestor de TI', 'Outro'] as $opcao)
                                <option value="{{ $opcao }}" @selected(($p['perfil'] ?? '') === $opcao)>{{ $opcao ?: 'Selecionar…' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Faixa etária</label>
                        <select class="form-select tracked" id="p-idade">
                            @foreach(['', '18–29 anos', '30–44 anos', '45–59 anos', '60+ anos'] as $opcao)
                                <option value="{{ $opcao }}" @selected(($p['idade'] ?? '') === $opcao)>{{ $opcao ?: 'Selecionar…' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Experiência com sistemas web</label>
                        <select class="form-select tracked" id="p-experiencia">
                            @foreach(['', 'Iniciante (uso básico)', 'Intermediário (usa regularmente)', 'Avançado (usa com frequência sistemas complexos)'] as $opcao)
                                <option value="{{ $opcao }}" @selected(($p['experiencia'] ?? '') === $opcao)>{{ $opcao ?: 'Selecionar…' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Já usou o SISGEM antes?</label>
                        <select class="form-select tracked" id="p-uso-prev">
                            @foreach(['', 'Não, nunca', 'Somente demonstração', 'Sim, uso em produção'] as $opcao)
                                <option value="{{ $opcao }}" @selected(($p['uso_prev'] ?? '') === $opcao)>{{ $opcao ?: 'Selecionar…' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Observações iniciais do moderador</label>
                        <textarea class="form-control tracked" id="p-obs" rows="3" placeholder="Contexto relevante, estado do participante, condições técnicas do ambiente de teste…">{{ $p['obs'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card sus-card mb-3">
            <div class="card-body">
                <div class="sus-section-title">Registro das tarefas observadas</div>
                <div class="table-responsive">
                    <table class="table table-sm task-table align-middle">
                        <thead>
                        <tr>
                            <th style="width:70px">Tarefa</th>
                            <th>Módulo</th>
                            <th>Descrição</th>
                            <th style="width:150px">Resultado</th>
                            <th style="width:110px">Tempo (s)</th>
                            <th style="width:165px">Nível de erro</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tasks as $task)
                            @php $r = $tarefas[$task['id']] ?? []; @endphp
                            <tr data-task="{{ $task['id'] }}">
                                <td class="task-id">{{ $task['id'] }}</td>
                                <td><span class="task-module {{ $task['cls'] }}">{{ $task['mod'] }}</span></td>
                                <td class="task-label">{{ $task['label'] }}</td>
                                <td>
                                    <select class="form-select form-select-sm tracked task-result" data-task="{{ $task['id'] }}">
                                        @foreach(['', 'Concluída', 'Parcial', 'Falha', 'N/A'] as $opcao)
                                            <option value="{{ $opcao }}" @selected(($r['result'] ?? '') === $opcao)>{{ $opcao ?: '—' }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm tracked task-time" data-task="{{ $task['id'] }}" type="number" min="0" value="{{ $r['time'] ?? '' }}" placeholder="—">
                                </td>
                                <td>
                                    <select class="form-select form-select-sm tracked task-error" data-task="{{ $task['id'] }}">
                                        @foreach(['' => '—', '0' => 'Sem erro', '1' => '1 – Catastrófico', '2' => '2 – Sério', '3' => '3 – Menor', '4' => '4 – Cosmético'] as $valor => $label)
                                            <option value="{{ $valor }}" @selected((string)($r['errorLevel'] ?? '') === (string)$valor)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <label class="form-label">Observações gerais sobre as tarefas</label>
                    <textarea class="form-control tracked" id="obs-tarefas" rows="5" placeholder="Padrões de comportamento observados, erros recorrentes, hesitações significativas, comentários espontâneos relevantes…">{{ $obsTarefas }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <script>
        const SAVE_URL = @json(route('avaliacao.moderacao.salvar', $avaliacao));
        const CSRF_TOKEN = @json(csrf_token());
        let saveTimer = null;
        let isSaving = false;

        function valor(id) {
            return document.getElementById(id)?.value || '';
        }

        function getModeracaoPayload() {
            const resultados = {};

            document.querySelectorAll('tr[data-task]').forEach(row => {
                const tid = row.dataset.task;
                resultados[tid] = {
                    result: row.querySelector('.task-result')?.value || '',
                    time: row.querySelector('.task-time')?.value || '',
                    errorLevel: row.querySelector('.task-error')?.value || ''
                };
            });

            return {
                participante: {
                    codigo: valor('p-codigo'),
                    data: valor('p-data'),
                    hora: valor('p-hora'),
                    moderador: valor('p-moderador'),
                    perfil: valor('p-perfil'),
                    idade: valor('p-idade'),
                    experiencia: valor('p-experiencia'),
                    uso_prev: valor('p-uso-prev'),
                    obs: valor('p-obs')
                },
                tarefas: {
                    resultados,
                    obs: valor('obs-tarefas')
                }
            };
        }

        function agendarAutoSave() {
            clearTimeout(saveTimer);
            saveTimer = setTimeout(() => salvarModeracaoAgora(false), 600);
        }

        async function salvarModeracaoAgora(showToast = true) {
            if (isSaving) return;

            isSaving = true;
            document.getElementById('save-status').textContent = 'Salvando...';

            try {
                const response = await fetch(SAVE_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({ payload: getModeracaoPayload() })
                });

                if (!response.ok) throw new Error('Erro ao salvar');

                const data = await response.json();
                document.getElementById('save-status').textContent = 'Salvo ' + (data.last_saved_at || '');

                if (showToast && window.bootstrap) {
                    // Se você usa Bootstrap Toast no projeto, pode acoplar aqui.
                }
            } catch (e) {
                console.error(e);
                document.getElementById('save-status').textContent = 'Erro ao salvar';
                alert('Não foi possível salvar a observação. Verifique o console.');
            } finally {
                isSaving = false;
            }
        }

        document.querySelectorAll('.tracked').forEach(el => {
            el.addEventListener('input', agendarAutoSave);
            el.addEventListener('change', agendarAutoSave);
        });
    </script>
@endsection
