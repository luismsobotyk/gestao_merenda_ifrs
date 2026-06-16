@extends('dashboard.layout')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-0">Consumo por Tipo de Merenda</h1>
            <small class="text-muted">Cruzamento histórico de retiradas com o cardápio efetivamente servido no dia.</small>
        </div>
    </div>

    {{-- BARRA DE FILTRO COM SELEÇÃO DE GRÁFICO --}}
    <form method="GET" action="{{ route('graficos.tipos_merenda') }}" class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body p-3 d-flex align-items-end gap-3 flex-wrap">
            <div>
                <label for="data_inicial" class="form-label small text-muted fw-bold mb-1">Data Inicial</label>
                {{-- Removido o atributo required --}}
                <input type="date" id="data_inicial" name="data_inicial" class="form-control border-primary text-primary fw-bold" value="{{ $dataInicial }}">
            </div>
            <div>
                <label for="data_final" class="form-label small text-muted fw-bold mb-1">Data Final</label>
                {{-- Removido o atributo required --}}
                <input type="date" id="data_final" name="data_final" class="form-control border-primary text-primary fw-bold" value="{{ $dataFinal }}">
            </div>

            {{-- NOVO: CAMPO DE TIPO DE GRÁFICO --}}
            <div>
                <label for="tipo_grafico" class="form-label small text-muted fw-bold mb-1">Visualização</label>
                <select id="tipo_grafico" name="tipo_grafico" class="form-select border-primary text-primary fw-bold">
                    <option value="bar" {{ $tipoGrafico == 'bar' ? 'selected' : '' }}>Gráfico de Barras</option>
                    <option value="pie" {{ $tipoGrafico == 'pie' ? 'selected' : '' }}>Gráfico de Rosca</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-bold px-4">
                    <i class="bi bi-search"></i> Atualizar
                </button>

                @if($dataInicial || $dataFinal)
                    <button type="submit" name="limpar" value="1" class="btn btn-outline-secondary px-3" formnovalidate>
                        Remover Filtro
                    </button>
                @endif
            </div>
        </div>
    </form>

    {{-- BLOCO DO GRÁFICO --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div id="graficoMerenda" style="width: 100%; height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- BLOCO DA TABELA DE DADOS --}}
    <div class="row mt-4 mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-secondary mb-4">Detalhamento dos Dados
                        @if($dataInicial && $dataFinal)
                            <span class="badge bg-primary ms-2 fs-6 fw-normal">Período: {{ \Carbon\Carbon::parse($dataInicial)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($dataFinal)->format('d/m/Y') }}</span>
                        @else
                            <span class="badge bg-secondary ms-2 fs-6 fw-normal">Período: Histórico Completo</span>
                        @endif
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle border">
                            <thead class="table-light text-secondary">
                            <tr>
                                <th scope="col">Composição do Lanche</th>
                                <th scope="col" class="text-end">Quantidade de Retiradas</th>
                                <th scope="col" class="text-end">Representatividade (%)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $totalGeral = array_sum($valores);
                            @endphp

                            @foreach($labels as $index => $lanche)
                                @php
                                    $quantidade = $valores[$index];
                                    $porcentagem = $totalGeral > 0 ? round(($quantidade / $totalGeral) * 100, 1) : 0;
                                @endphp
                                <tr>
                                    <td class="fw-bold text-dark">{{ $lanche }}</td>
                                    <td class="text-end fw-bold fs-5">{{ $quantidade }}</td>
                                    <td class="text-end text-muted">{{ number_format($porcentagem, 1, ',', '.') }}%</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="table-light fw-bold">
                            <tr>
                                <td class="text-uppercase">Total do Período</td>
                                <td class="text-end text-success fs-5">{{ $totalGeral }}</td>
                                <td class="text-end text-success">100%</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var myChart = echarts.init(document.getElementById('graficoMerenda'));

            var labelsCardapio = {!! json_encode($labels) !!};
            var dadosSaida = {!! json_encode($valores) !!};
            var tipoSelecionado = '{{ $tipoGrafico }}'; // Recebe a escolha do Controller

            var option;

            // Se o utilizador escolheu ROSCA (Pie)
            // Se o utilizador escolheu ROSCA (Pie)
            if (tipoSelecionado === 'pie') {
                var pieData = labelsCardapio.map(function(label, index) {
                    return { name: label, value: dadosSaida[index] };
                });

                option = {
                    title: { text: 'Ranking de Aceitação', left: 'center' },
                    tooltip: {
                        trigger: 'item',
                        formatter: '<strong>{b}</strong> <br/> {c} retiradas registradas ({d}%)'
                    },
                    legend: {
                        bottom: 0,
                        type: 'scroll',
                        padding: [15, 0, 0, 0]
                    },
                    series: [
                        {
                            name: 'Retiradas',
                            type: 'pie',
                            radius: ['40%', '70%'],
                            avoidLabelOverlap: true, // Garante que as porcentagens não fiquem umas sobre as outras
                            itemStyle: { borderRadius: 10, borderColor: '#fff', borderWidth: 2 },

                            // --- MUDANÇA AQUI: Mostra a porcentagem visível nas fatias ---
                            label: {
                                show: true,
                                formatter: '{d}%', // O {d} é o código interno do ECharts para Porcentagem
                                fontSize: 14,
                                fontWeight: 'bold',
                                color: '#495057'
                            },
                            labelLine: {
                                show: true, // Desenha a linhazinha elegante ligando o número à fatia
                                length: 15,
                                length2: 10
                            },
                            // -------------------------------------------------------------

                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            },
                            data: pieData
                        }
                    ]
                };

                // Se escolheu BARRAS (Bar) - Comportamento Padrão
            } else {
                // 1. Calcula a soma de todas as retiradas para basear a nossa porcentagem
                var totalGeral = dadosSaida.reduce(function(soma, valorAtual) {
                    return soma + valorAtual;
                }, 0);

                var seriesDinamicas = labelsCardapio.map(function(nome, index) {
                    return {
                        name: nome,
                        type: 'bar',
                        data: [dadosSaida[index]],
                        label: {
                            show: true,
                            position: 'top',
                            fontWeight: 'bold',
                            color: '#495057',
                            // 2. Customiza o texto que vai no topo da barra
                            formatter: function(params) {
                                if (totalGeral === 0) return '0';

                                // Calcula a porcentagem com 1 casa decimal
                                var porcentagem = ((params.value / totalGeral) * 100).toFixed(1);

                                // Retorna o valor e a % no formato brasileiro (trocando ponto por vírgula)
                                return params.value + ' (' + porcentagem.replace('.', ',') + '%)';
                            }
                        }
                    };
                });

                option = {
                    title: { text: 'Ranking de Aceitação', left: 'center' },
                    tooltip: {
                        trigger: 'item',
                        // 3. Atualizamos a caixinha flutuante para mostrar a % igual ao gráfico de rosca
                        formatter: function(params) {
                            var pct = totalGeral > 0 ? ((params.value / totalGeral) * 100).toFixed(1).replace('.', ',') : '0';
                            return '<strong>' + params.seriesName + '</strong> <br/> ' + params.value + ' retiradas (' + pct + '%)';
                        }
                    },
                    legend: {
                        bottom: 0,
                        type: 'scroll',
                        padding: [15, 0, 0, 0]
                    },
                    grid: { left: '5%', right: '5%', bottom: '15%', containLabel: true },
                    xAxis: { type: 'category', data: ['Comparativo Total'], axisTick: { show: false }, axisLabel: { show: false } },
                    yAxis: { type: 'value', name: 'Qtd. Retiradas' },
                    series: seriesDinamicas
                };
            }

            myChart.setOption(option);

            window.addEventListener('resize', function() {
                myChart.resize();
            });
        });
    </script>
@endsection
