<?php
/**
 * Funções para manipulação de dados de clientes e geração de HTML para e-mails.
 *
 * Este arquivo contém funções para consultar dados de clientes e gerar HTML
 * formatado para exibir esses dados em um e-mail.
 *
 * @author Gabrielli
 */

require_once '/xampp/htdocs/Intranet/bdConnection.php';
require_once '/xampp/htdocs/Intranet/Controller/standardFunctionsController.php';

/**
 * Busca os dados dos clientes cadastrados no banco de dados.
 *
 * @param PDO $pdo Objeto PDO para conexão com o banco de dados.
 * @return array Array associativo com os dados dos clientes.
 *               Cada entrada do array contém 'nome', 'email', 'nomecidade' e 'uf'.
 */
function queryUsers($pdo) {
    $sql = "SELECT cli.nome,
                   cli.email,
                   cli.fone,
                   cli.dtnasc,
                   cli.ativo,
                   cli.tipo,
                   cid.nomecidade,
                   cid.uf
            FROM tb_clientes cli
            INNER JOIN tb_cidades cid  
            ON cli.codcid = cid.codcid";

    try {
        $stmt = $pdo->query($sql);

        if ($stmt) {
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $users;
        } else {
            return array(); 
        }
    } catch (PDOException $e) {
        die('Erro ao buscar dados dos clientes: ' . $e->getMessage());
    }
}

/**
 * Gera HTML formatado com os dados dos clientes para ser enviado por e-mail.
 *
 * @param PDO $pdo Objeto PDO para conexão com o banco de dados.
 * @return string HTML formatado com a tabela de dados dos clientes.
 */
function usersToEmail($pdo) {
    $users = queryUsers($pdo); 

    $html = '<h2>Dados dos Clientes Cadastrados</h2>';
    $html .= '<table border="1">';
    $html .= '<tr><th>Nome</th>
                  <th>E-mail</th>
                  <th>Contato</th>
                  <th>Data de Nascimento</th>
                  <th>Status</th>
                  <th>Tipo</th>
                  <th>Cidade</th>
                  <th>Estado</th>
                  </tr>';

    foreach ($users as $user) {
        $html .= '<tr>';
        $html .= '<td>' . tratarCaracteresEspeciais($user['nome']) . '</td>';
        $html .= '<td>' . htmlspecialchars($user['email']) . '</td>';
        $html .= '<td>' . formatPhoneNumber($user['fone']) . '</td>';
        $html .= '<td>' . formatDateToBrazilian($user['dtnasc']) . '</td>';
        $html .= '<td>' . htmlspecialchars($user['ativo'] == 'S' ? 'Ativo' : 'Inativo') . '</td>';
        $html .= '<td>' . htmlspecialchars($user['tipo'] == 'C' ? 'Cliente' : 'Administrador') . '</td>';
        $html .= '<td>' . htmlspecialchars($user['nomecidade']) . '</td>';
        $html .= '<td>' . htmlspecialchars($user['uf']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    return $html;
}
?>
