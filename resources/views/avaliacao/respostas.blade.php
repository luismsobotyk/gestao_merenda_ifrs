@extends('dashboard.layout')

@section('custom_css')
    <style>
        .sus-admin-wrap { max-width: 1280px; }

        .sus-card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 1px 4px rgba(15,31,22,.06), 0 8px 28px rgba(15,31,22,.08);
        }

        .metric-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .85rem;
        }

        .metric-card {
            background: #fff;
            border: 1px solid #D5E5DB;
            border-radius: 14px;
            padding: 1rem;
            box-shadow: 0 1px 4px rgba(15,31,22,.05);
        }

        .metric-value {
            font-size: 2rem;
            line-height: 1;
            font-weight: 800;
            color: #1B5C3B;
        }

        .metric-label {
            color: #5c7167;
            font-size: .82rem;
            margin-top: .35rem;
        }

        .chart-card {
            background: #fff;
            border: 1px solid #D5E5DB;
            border-radius: 14px;
            padding: 1.25rem;
            box-shadow: 0 1px 4px rgba(15,31,22,.05);
            overflow: hidden;
        }

        /*
         * IMPORTANTE:
         * O Chart.js precisa de um container com altura explícita quando usamos
         * maintainAspectRatio: false. Sem isso, o canvas pode crescer indefinidamente.
         */
        .chart-box {
            position: relative;
            width: 100%;
            height: 280px;
            max-height: 280px;
            min-height: 280px;
            overflow: hidden;
        }

        .chart-box canvas {
            display: block;
            width: 100% !important;
            height: 280px !important;
            max-height: 280px !important;
        }

        .chart-title {
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #6b8778;
            margin-bottom: 1rem;
        }

        .task-table th {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #6b8778;
            background: #f3f7f4;
        }

        .task-table td,
        .task-table th {
            vertical-align: middle;
        }

        .task-module {
            display: inline-flex;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: .68rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .mod-publico { background: #D4EFE0; color: #1B5C3B; }
        .mod-auth { background: #E3ECFD; color: #1A4EA0; }
        .mod-gestao { background: #FEF3DC; color: #A05C00; }
        .mod-contrato { background: #EDE9FE; color: #4C2A99; }
        .mod-merenda { background: #EEF8F3; color: #22754C; }
        .mod-dados { background: #F0F9FF; color: #075985; }

        .qual-box {
            white-space: pre-wrap;
            background: #f8faf9;
            border: 1px solid #D5E5DB;
            border-radius: 10px;
            padding: .8rem;
            font-size: .87rem;
        }

        @media (max-width: 900px) {
            .metric-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 768px) {
            .chart-box,
            .chart-box canvas {
                height: 240px !important;
                max-height: 240px !important;
                min-height: 240px !important;
            }
        }

        @media (max-width: 560px) {
            .metric-grid { grid-template-columns: 1fr; }
        }
    </style>
@endsection

@section('content')
    <div class="sus-admin-wrap">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2 mb-1">Respostas consolidadas da avaliação SUS</h1>
                <p class="text-muted mb-0">
                    Dados carregados do banco, incluindo respostas do participante e registros de observação preenchidos pelo moderador.
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('avaliacao.index') }}" class="btn btn-sm btn-outline-secondary">Ir para formulário</a>
                <button type="button" class="btn btn-sm btn-primary" onclick="exportJSON()">Exportar JSON</button>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="exportCSV()">Exportar CSV</button>
            </div>
        </div>

        <div class="metric-grid mb-4">
            <div class="metric-card">
                <div class="metric-value" id="metric-total">—</div>
                <div class="metric-label">Avaliações registradas</div>
            </div>
            <div class="metric-card">
                <div class="metric-value" id="metric-submitted">—</div>
                <div class="metric-label">Avaliações submetidas</div>
            </div>
            <div class="metric-card">
                <div class="metric-value" id="metric-sus">—</div>
                <div class="metric-label">Pontuação SUS média</div>
            </div>
            <div class="metric-card">
                <div class="metric-value" id="metric-completion">—</div>
                <div class="metric-label">Conclusão média das tarefas</div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="chart-title">SUS por participante</div>
                    <div class="chart-box">
                        <canvas id="chart-sus"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="chart-title">Classificação SUS</div>
                    <div class="chart-box">
                        <canvas id="chart-sus-class"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="chart-title">Conclusão por tarefa</div>
                    <div class="chart-box">
                        <canvas id="chart-completion"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="chart-title">Tempo médio por tarefa</div>
                    <div class="chart-box">
                        <canvas id="chart-time"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="chart-title">Erros por nível</div>
                    <div class="chart-box">
                        <canvas id="chart-errors"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="chart-title">Média das respostas SUS por questão</div>
                    <div class="chart-box">
                        <canvas id="chart-sus-items"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card sus-card mb-4">
            <div class="card-body">
                <h2 class="h5 mb-3">Resultados por tarefa</h2>
                <div class="table-responsive">
                    <table class="table table-sm task-table" id="task-summary-table">
                        <thead>
                        <tr>
                            <th>Tarefa</th>
                            <th>Módulo</th>
                            <th>Conclusão</th>
                            <th>Tempo médio</th>
                            <th>Erros registrados</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card sus-card mb-4">
            <div class="card-body">
                <h2 class="h5 mb-3">Avaliações individuais</h2>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>Participante</th>
                            <th>Usuário LDAP</th>
                            <th>Status</th>
                            <th>SUS</th>
                            <th>Último salvamento</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody id="sessions-table-body"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card sus-card mb-4">
            <div class="card-body">
                <h2 class="h5 mb-3">Respostas qualitativas</h2>
                <div id="qualitative-list"></div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

    <script>
        const SESSIONS = @json($sessions);

        const TASKS = [
            { id:'T1',  mod:'público',  cls:'mod-publico',  label:'Identifique o cardápio escolar atual disponível no sistema.' },
            { id:'T2',  mod:'público',  cls:'mod-publico',  label:'Localize o cardápio da próxima semana.' },
            { id:'T3',  mod:'auth',     cls:'mod-auth',     label:'Autentique-se no sistema usando suas credenciais LDAP.' },
            { id:'T4',  mod:'gestão',   cls:'mod-gestao',   label:'Sincronize os cursos da instituição a partir do sistema externo (CTA).' },
            { id:'T5',  mod:'gestão',   cls:'mod-gestao',   label:'Habilite o acesso à merenda escolar para uma turma ou grupo de estudantes.' },
            { id:'T6',  mod:'gestão',   cls:'mod-gestao',   label:'Sincronize os discentes da instituição a partir do sistema externo (CTA).' },
            { id:'T7',  mod:'contrato', cls:'mod-contrato', label:'Identifique os contratos ativos cadastrados no sistema.' },
            { id:'T8',  mod:'contrato', cls:'mod-contrato', label:'Cadastre um novo contrato incluindo ao menos um alimento.' },
            { id:'T9',  mod:'contrato', cls:'mod-contrato', label:'Edite os dados do contrato recém-cadastrado.' },
            { id:'T10', mod:'contrato', cls:'mod-contrato', label:'Visualize os dados completos de um contrato existente.' },
            { id:'T11', mod:'contrato', cls:'mod-contrato', label:'Cadastre um empenho vinculado ao contrato e visualize seus dados.' },
            { id:'T12', mod:'merenda',  cls:'mod-merenda',  label:'Faça um pedido de merenda para a semana seguinte.' },
            { id:'T13', mod:'merenda',  cls:'mod-merenda',  label:'Atualize o status de um pedido para "Recebido".' },
            { id:'T14', mod:'merenda',  cls:'mod-merenda',  label:'Visualize a lista de pedidos realizados.' },
            { id:'T15', mod:'contrato', cls:'mod-contrato', label:'Identifique outros contratos vinculados à mesma empresa fornecedora.' },
            { id:'T16', mod:'merenda',  cls:'mod-merenda',  label:'Cadastre um novo cardápio para uma data futura.' },
            { id:'T17', mod:'merenda',  cls:'mod-merenda',  label:'Simule ou execute a retirada de merenda por um estudante.' },
            { id:'T18', mod:'dados',    cls:'mod-dados',    label:'Analise os dados de retirada de merenda em formato de gráfico.' },
            { id:'T19', mod:'dados',    cls:'mod-dados',    label:'Visualize o gráfico de acessos mais frequentes, sobras e distribuição por turno.' },
        ];

        const QUAL_LABELS = {
            q1: 'O que você mais gostou no sistema?',
            q2: 'O que causou mais dificuldade ou frustração?',
            q3: 'Se pudesse mudar uma coisa no sistema, o que seria?',
            q4: 'O processo de login (LDAP) foi claro e rápido?',
            q5: 'A sincronização de cursos e discentes (importação) foi intuitiva?',
            q6: 'O fluxo de cadastro e gestão de contratos/empenhos foi compreensível?',
            q7: 'A simulação de retirada de merenda refletiu o processo real da escola?',
            q8: 'Os gráficos e dados de retirada foram fáceis de interpretar?',
            q9: 'Você confiaria neste sistema para gerenciar a merenda escolar da sua instituição?',
            q10: 'Há algo que falta no sistema para atender às necessidades reais da escola?',
            q11: 'Comentários livres ou sugestões adicionais'
        };

        let charts = {};

        function participanteLabel(session) {
            return session.payload?.participante?.codigo || session.ldap_username || ('ID ' + session.id);
        }

        function scoreInfo(score) {
            if (score >= 90) return { label: 'Excelente', bucket: 'Excelente' };
            if (score >= 80) return { label: 'Bom', bucket: 'Bom' };
            if (score >= 70) return { label: 'OK', bucket: 'OK' };
            if (score >= 60) return { label: 'Pobre', bucket: 'Pobre' };
            return { label: 'Inaceitável', bucket: 'Inaceitável' };
        }

        function numericScores() {
            return SESSIONS
                .map(s => Number(s.sus_score ?? s.payload?.sus?.score))
                .filter(v => Number.isFinite(v));
        }

        function avg(values) {
            return values.length ? values.reduce((a, b) => a + b, 0) / values.length : null;
        }

        function taskStats() {
            return TASKS.map(t => {
                const results = SESSIONS
                    .map(s => s.payload?.tarefas?.resultados?.[t.id]?.result)
                    .filter(Boolean);

                const total = results.filter(r => r !== 'N/A').length;
                const ok = results.filter(r => r === 'Concluída').length;
                const pct = total > 0 ? Math.round((ok / total) * 100) : null;

                const times = SESSIONS
                    .map(s => Number(s.payload?.tarefas?.resultados?.[t.id]?.time))
                    .filter(v => Number.isFinite(v) && v > 0);

                const errors = SESSIONS
                    .map(s => s.payload?.tarefas?.resultados?.[t.id]?.errorLevel)
                    .filter(v => v !== undefined && v !== null && v !== '' && String(v) !== '0');

                return {
                    ...t,
                    completion: pct,
                    avgTime: times.length ? Math.round(avg(times)) : null,
                    errors: errors.length,
                };
            });
        }

        function completionAverage() {
            const values = taskStats()
                .map(t => t.completion)
                .filter(v => v !== null);

            return values.length ? Math.round(avg(values)) : null;
        }

        function renderMetrics() {
            const scores = numericScores();
            const avgSus = scores.length ? Math.round(avg(scores)) : null;
            const avgCompletion = completionAverage();

            document.getElementById('metric-total').textContent = SESSIONS.length;
            document.getElementById('metric-submitted').textContent = SESSIONS.filter(s => s.status === 'Submetida').length;
            document.getElementById('metric-sus').textContent = avgSus !== null ? avgSus : '—';
            document.getElementById('metric-completion').textContent = avgCompletion !== null ? avgCompletion + '%' : '—';
        }

        function renderSessionsTable() {
            const body = document.getElementById('sessions-table-body');

            if (!SESSIONS.length) {
                body.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Nenhuma avaliação encontrada.</td></tr>';
                return;
            }

            body.innerHTML = SESSIONS.map(s => {
                const p = s.payload?.participante || {};
                const statusClass = s.status === 'Submetida' ? 'bg-success' : 'bg-warning text-dark';
                const score = s.sus_score ?? s.payload?.sus?.score ?? '—';

                return `
                    <tr>
                        <td>
                            <strong>${escapeHtml(p.codigo || '—')}</strong><br>
                            <small class="text-muted">${escapeHtml(p.perfil || 'Perfil não informado')}</small>
                        </td>
                        <td>${escapeHtml(s.ldap_username || '—')}</td>
                        <td><span class="badge ${statusClass}">${escapeHtml(s.status || '—')}</span></td>
                        <td><strong>${escapeHtml(String(score))}</strong></td>
                        <td>${escapeHtml(s.last_saved_at || '—')}</td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="${escapeHtml(s.moderacao_url || '#')}">Preencher observação</a>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function renderTaskTable() {
            const body = document.querySelector('#task-summary-table tbody');
            const rows = taskStats();

            body.innerHTML = rows.map(t => `
                <tr>
                    <td><strong>${escapeHtml(t.id)}</strong></td>
                    <td><span class="task-module ${escapeHtml(t.cls)}">${escapeHtml(t.mod)}</span></td>
                    <td>${t.completion !== null ? t.completion + '%' : '—'}</td>
                    <td>${t.avgTime !== null ? t.avgTime + 's' : '—'}</td>
                    <td>${t.errors || '—'}</td>
                </tr>
            `).join('');
        }

        function renderQualitative() {
            const list = document.getElementById('qualitative-list');

            if (!SESSIONS.length) {
                list.innerHTML = '<div class="text-muted">Nenhuma resposta qualitativa encontrada.</div>';
                return;
            }

            list.innerHTML = SESSIONS.map(s => {
                const q = s.payload?.qualitativo || {};
                const answers = Object.keys(QUAL_LABELS).map(key => `
                    <div class="mb-3">
                        <div class="fw-semibold">${escapeHtml(QUAL_LABELS[key])}</div>
                        <div class="qual-box mt-1">${escapeHtml(q[key] || '—')}</div>
                    </div>
                `).join('');

                return `
                    <details class="mb-3">
                        <summary class="fw-semibold">
                            ${escapeHtml(participanteLabel(s))}
                            · ${escapeHtml(s.ldap_username || '')}
                            · SUS ${escapeHtml(String(s.sus_score ?? s.payload?.sus?.score ?? '—'))}
                        </summary>
                        <div class="mt-3">${answers}</div>
                    </details>
                `;
            }).join('');
        }

        function destroyCharts() {
            Object.values(charts).forEach(chart => chart?.destroy?.());
            charts = {};
        }

        function chartOptions(extra = {}) {
            return {
                responsive: true,
                maintainAspectRatio: false,
                resizeDelay: 150,
                animation: false,
                plugins: {
                    legend: { display: false },
                    ...(extra.plugins || {})
                },
                scales: extra.scales ?? {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,.06)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            };
        }

        function createChart(id, config) {
            const canvas = document.getElementById(id);

            if (!canvas) {
                console.warn('Canvas não encontrado:', id);
                return null;
            }

            return new Chart(canvas.getContext('2d'), config);
        }

        function renderCharts() {
            destroyCharts();

            const scored = SESSIONS.filter(s =>
                Number.isFinite(Number(s.sus_score ?? s.payload?.sus?.score))
            );

            charts.sus = createChart('chart-sus', {
                type: 'bar',
                data: {
                    labels: scored.map(participanteLabel),
                    datasets: [{
                        label: 'SUS',
                        data: scored.map(s => Number(s.sus_score ?? s.payload?.sus?.score)),
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: chartOptions({
                    scales: {
                        y: {
                            beginAtZero: true,
                            min: 0,
                            max: 100,
                            grid: { color: 'rgba(0,0,0,.06)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                })
            });

            const buckets = {
                'Excelente': 0,
                'Bom': 0,
                'OK': 0,
                'Pobre': 0,
                'Inaceitável': 0
            };

            scored.forEach(s => {
                const score = Number(s.sus_score ?? s.payload?.sus?.score);
                buckets[scoreInfo(score).bucket]++;
            });

            charts.susClass = createChart('chart-sus-class', {
                type: 'doughnut',
                data: {
                    labels: Object.keys(buckets),
                    datasets: [{
                        data: Object.values(buckets)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    resizeDelay: 150,
                    animation: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            const tasks = taskStats();

            charts.completion = createChart('chart-completion', {
                type: 'bar',
                data: {
                    labels: tasks.map(t => t.id),
                    datasets: [{
                        label: 'Conclusão %',
                        data: tasks.map(t => t.completion ?? 0),
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: chartOptions({
                    scales: {
                        y: {
                            beginAtZero: true,
                            min: 0,
                            max: 100,
                            grid: { color: 'rgba(0,0,0,.06)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                })
            });

            charts.time = createChart('chart-time', {
                type: 'bar',
                data: {
                    labels: tasks.map(t => t.id),
                    datasets: [{
                        label: 'Tempo médio (s)',
                        data: tasks.map(t => t.avgTime ?? 0),
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: chartOptions()
            });

            const errorLevels = {
                '1 – Catastrófico': 0,
                '2 – Sério': 0,
                '3 – Menor': 0,
                '4 – Cosmético': 0
            };

            SESSIONS.forEach(s => {
                Object.values(s.payload?.tarefas?.resultados || {}).forEach(r => {
                    const level = String(r.errorLevel ?? '');

                    if (level === '1') errorLevels['1 – Catastrófico']++;
                    if (level === '2') errorLevels['2 – Sério']++;
                    if (level === '3') errorLevels['3 – Menor']++;
                    if (level === '4') errorLevels['4 – Cosmético']++;
                });
            });

            charts.errors = createChart('chart-errors', {
                type: 'bar',
                data: {
                    labels: Object.keys(errorLevels),
                    datasets: [{
                        label: 'Ocorrências',
                        data: Object.values(errorLevels),
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: chartOptions()
            });

            const susItemMeans = Array.from({ length: 10 }, (_, i) => {
                const values = SESSIONS
                    .map(s => Number(s.payload?.sus?.respostas?.[i]))
                    .filter(v => Number.isFinite(v) && v >= 1 && v <= 5);

                return values.length ? Number(avg(values).toFixed(2)) : 0;
            });

            charts.susItems = createChart('chart-sus-items', {
                type: 'bar',
                data: {
                    labels: susItemMeans.map((_, i) => 'Q' + (i + 1)),
                    datasets: [{
                        label: 'Média',
                        data: susItemMeans,
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: chartOptions({
                    scales: {
                        y: {
                            beginAtZero: true,
                            min: 0,
                            max: 5,
                            grid: { color: 'rgba(0,0,0,.06)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                })
            });
        }

        function exportJSON() {
            download(JSON.stringify(SESSIONS, null, 2), 'avaliacao_sus_respostas.json', 'application/json');
        }

        function exportCSV() {
            const header = [
                'participante',
                'ldap_username',
                'status',
                'sus_score',
                'submitted_at',
                'last_saved_at'
            ];

            const rows = SESSIONS.map(s => [
                participanteLabel(s),
                s.ldap_username || '',
                s.status || '',
                s.sus_score ?? s.payload?.sus?.score ?? '',
                s.submitted_at || '',
                s.last_saved_at || ''
            ]);

            const csv = [header, ...rows]
                .map(row => row.map(value => '"' + String(value).replaceAll('"', '""') + '"').join(';'))
                .join('\n');

            download(csv, 'avaliacao_sus_resumo.csv', 'text/csv;charset=utf-8');
        }

        function download(content, filename, mime) {
            const a = document.createElement('a');
            a.href = URL.createObjectURL(new Blob([content], { type: mime }));
            a.download = filename;
            a.click();
            URL.revokeObjectURL(a.href);
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderMetrics();
            renderSessionsTable();
            renderTaskTable();
            renderQualitative();
            renderCharts();
        });
    </script>
@endsection
