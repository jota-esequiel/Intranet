<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gráfico de Vendas por Mês</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width: 80%; margin: 0 auto;">
        <canvas id="myChart"></canvas>
    </div>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');

        <?php
        include '../bdConnection.php';

        try {
            $strQuery = "SELECT 
                            MONTH(tc.ddcompra) AS mes,
                            SUM(tci.qtd) AS totalVendas
                         FROM tb_compras tc
                         INNER JOIN tb_compras_itens tci 
                            ON tc.codencomenda = tci.codcompra
                         WHERE YEAR(tc.ddcompra) = YEAR(CURRENT_DATE())
                         GROUP BY mes
                         ORDER BY mes";

            $stmt = $pdo->prepare($strQuery);
            $stmt->execute();

            $data = array_fill(1, 12, 0); // Inicializa um array com 12 meses, todos com valor 0

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $mes = $row['mes'];
                $totalVendas = $row['totalVendas'];
                $data[$mes] = $totalVendas;
            }

            // Convertendo para formato adequado para JavaScript
            echo "var chartData = {";
            echo "    labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],";
            echo "    datasets: [{";
            echo "        label: 'Vendas por Mês',";
            echo "        backgroundColor: 'rgba(54, 162, 235, 0.2)',";
            echo "        borderColor: 'rgba(54, 162, 235, 1)',";
            echo "        borderWidth: 1,";
            echo "        data: " . json_encode(array_values($data));
            echo "    }]";
            echo "};";

        } catch (PDOException $e) {
            die("Erro ao executar a consulta: " . $e->getMessage());
        }
        ?>

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
</body>
</html>
