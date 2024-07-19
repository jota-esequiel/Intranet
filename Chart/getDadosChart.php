<?php
include '../bdConnection.php';
$pdo = conectar();

try {
    $strQuery = "SELECT 
                    CASE MONTH(tc.ddcompra)
                        WHEN 1 THEN 'Janeiro'
                        WHEN 2 THEN 'Fevereiro'
                        WHEN 3 THEN 'Março'
                        WHEN 4 THEN 'Abril'
                        WHEN 5 THEN 'Maio'
                        WHEN 6 THEN 'Junho'
                        WHEN 7 THEN 'Julho'
                        WHEN 8 THEN 'Agosto'
                        WHEN 9 THEN 'Setembro'
                        WHEN 10 THEN 'Outubro'
                        WHEN 11 THEN 'Novembro'
                        WHEN 12 THEN 'Dezembro'
                    END AS mes,
                    tp.nomeproduto,
                    SUM(tci.qtd) AS totalVendas
                FROM tb_compras tc
                INNER JOIN tb_compras_itens tci 
                    ON tc.codencomenda = tci.codcompra
                INNER JOIN tb_produtos tp 
                    ON tci.codproduto = tp.codproduto 
                WHERE YEAR(tc.ddcompra) = YEAR(CURRENT_DATE())
                GROUP BY mes, tp.nomeproduto
                ORDER BY mes";

    $stmt = $pdo->prepare($strQuery);
    $stmt->execute();

    $data = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = [
            'mes' => $row['mes'],
            'nomeproduto' => $row['nomeproduto'],
            'totalVendas' => $row['totalVendas']
        ];
    }

    echo json_encode($data);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array('message' => 'Erro ao executar a consulta: ' . $e->getMessage()));
}
?>
