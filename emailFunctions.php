<?php

require_once '/xampp/htdocs/Intranet/bdConnection.php';

function buscarDadosClientes($pdo) {
    $sql = "SELECT c.nome,
                   c.email,
                   ci.nomecidade,
                   ci.uf
            FROM tb_clientes c
            INNER JOIN tb_cidades ci  
            ON c.codcid = ci.codcid";

    try {
        $stmt = $pdo->query($sql);

        if ($stmt) {
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $clientes;
        } else {
            return array(); 
        }
    } catch (PDOException $e) {
        die('Erro ao buscar dados dos clientes: ' . $e->getMessage());
    }
}

function gerarHtmlClientes($pdo) {
    $clientes = buscarDadosClientes($pdo); 

    $html = '<h2>Dados dos Clientes Cadastrados</h2>';
    $html .= '<table border="1">';
    $html .= '<tr><th>Nome</th><th>E-mail</th><th>Cidade</th><th>Estado</th></tr>';

    foreach ($clientes as $cliente) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($cliente['nome']) . '</td>';
        $html .= '<td>' . htmlspecialchars($cliente['email']) . '</td>';
        $html .= '<td>' . htmlspecialchars($cliente['nomecidade']) . '</td>';
        $html .= '<td>' . htmlspecialchars($cliente['uf']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    return $html;
}

?>