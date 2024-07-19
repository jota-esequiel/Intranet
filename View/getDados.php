<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gráfico de Vendas por Mês</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width: 10%; margin: 0 auto;">
        <canvas id="myChart"></canvas>
    </div>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');

        function getChartData() {
            fetch('../Chart/getDadosChart.php')
                .then(response => response.json())
                .then(data => {
                    var chartData = {
                        labels: data.map(item => item.nomeproduto),
                        datasets: [{
                            label: 'Vendas por Produto',
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 206, 86, 0.8)',
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(153, 102, 255, 0.8)',
                                'rgba(255, 159, 64, 0.8)',
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 206, 86, 0.8)',
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(153, 102, 255, 0.8)',
                                'rgba(255, 159, 64, 0.8)'
                            ].slice(0, data.length),
                            borderColor: 'rgba(255, 255, 255, 1)',
                            borderWidth: 1,
                            data: data.map(item => item.totalVendas)
                        }]
                    };

                    var myChart = new Chart(ctx, {
                        type: 'pie', 
                        data: chartData,
                        options: {
                            responsive: true,
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Vendas por Produto'
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Erro ao buscar dados do servidor:', error);
                });
        }

        getChartData();
    </script>
</body>
</html>
