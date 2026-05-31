@extends('dashboard.layout')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-0">Consumo por Tipo de Merenda</h1>
            <small class="text-muted">Cruzamento histórico de retiradas com o cardápio efetivamente servido no dia.</small>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div id="graficoMerenda" style="width: 100%; height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    {{-- Importa a biblioteca Apache ECharts --}}
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa no modo normal (claro)
            var myChart = echarts.init(document.getElementById('graficoMerenda'));

            // Recebe os dados do Laravel
            var labelsCardapio = {!! json_encode($labels) !!};
            var dadosSaida = {!! json_encode($valores) !!};

            // MÁGICA AQUI: Transforma cada lanche em uma "Series" separada.
            // É isso que faz o ECharts dar uma cor diferente para cada um e jogar na legenda.
            var seriesDinamicas = labelsCardapio.map(function(nome, index) {
                return {
                    name: nome,
                    type: 'bar',
                    data: [dadosSaida[index]], // Valor atrelado a este lanche
                    label: {
                        show: true,
                        position: 'top',
                        fontWeight: 'bold'
                    }
                };
            });

            var option = {
                title: {
                    text: 'Aceitação do Cardápio',
                    left: 'center'
                },
                tooltip: {
                    trigger: 'item',
                    // Deixa a caixa flutuante mais elegante mostrando o nome e a quantidade
                    formatter: '<strong>{a}</strong> <br/> {c} retiradas registradas'
                },
                legend: {
                    bottom: 0,
                    type: 'scroll', // Se você tiver dezenas de lanches, ele cria setinhas para rolar a legenda
                    padding: [15, 0, 0, 0] // Dá um respiro entre o gráfico e a legenda
                },
                grid: {
                    left: '5%',
                    right: '5%',
                    bottom: '15%', // Espaço suficiente para a legenda não esmagar o gráfico
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: ['Comparativo Total'], // Agrupa todas as barras no mesmo eixo central
                    axisTick: { show: false },
                    axisLabel: { show: false } // Esconde esse texto base para manter limpo
                },
                yAxis: {
                    type: 'value',
                    name: 'Qtd. Retiradas'
                },
                // Injeta as barras separadas que criamos lá em cima
                series: seriesDinamicas
            };

            myChart.setOption(option);

            window.addEventListener('resize', function() {
                myChart.resize();
            });
        });
    </script>
@endsection
