<?php
/**
 * Funções para manipulação de dados de clientes e geração de HTML para e-mails.
 *
 * Este arquivo contém funções para consultar dados de clientes, gerar HTML
 * formatado para exibir esses dados em um e-mail, capturar informações de
 * acesso e enviar e-mail para o suporte.
 *
 * @author Gabrielli
 */

require_once '/xampp/htdocs/Intranet/bdConnection.php';
require_once '/xampp/htdocs/Intranet/Controller/standardFunctionsController.php';
require_once '/xampp/htdocs/Intranet/schedule/schedule.php'; // Incluindo schedule.php para ter acesso à função enviarEmail()

/**
 * Busca os dados dos clientes cadastrados no banco de dados.
 *
 * @param PDO $pdo Objeto PDO para conexão com o banco de dados.
 * @return array Array associativo com os dados dos clientes.
 *               Cada entrada do array contém 'nome', 'email', 'nomecidade' e 'uf'.
 */
function queryUsers($pdo) {
    $sql = "SELECT cli.codcliente,
                   cli.nome,
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

/**
 * Busca o email do usuário logado no banco de dados.
 *
 * @param PDO $pdo Objeto PDO para conexão com o banco de dados.
 * @param int $userId ID do usuário logado.
 * @return string Email do usuário logado.
 */
function getUserEmailById($pdo, $userId) {
    $sql = "SELECT email FROM tb_clientes WHERE codcliente = :id";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user['email'] : null;
    } catch (PDOException $e) {
        die('Erro ao buscar email do usuário: ' . $e->getMessage());
    }
}

/**
 * Captura informações de acesso e envia um e-mail para o suporte se não for um administrador autenticado.
 *
 * @param PDO $pdo Objeto PDO para conexão com o banco de dados.
 * @param string $rotinaAcessada Nome da rotina do sistema que foi acessada.
 */
function capturarEEnviarEmailSuporte($pdo, $rotinaAcessada) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $idCliente = isset($_SESSION['usuario']['codcliente']) ? $_SESSION['usuario']['codcliente'] : '(Não tem registro)';
    $nomeCliente = isset($_SESSION['usuario']['nome']) ? $_SESSION['usuario']['nome'] : 'Usuário não cadastrado';
    date_default_timezone_set('America/Sao_Paulo');
    $horarioAcesso = date('d/m/Y H:i:s');

    $assunto = 'Tentativa de Acesso à Rotina Administrativa';

    $corpo = '<h2>Tentativa de Acesso à Rotina Administrativa</h2>';
    $corpo .= '<p>Detalhes do acesso:</p>';
    $corpo .= '<table border="1">';
    $corpo .= '<tr><th>IP do Usuário</th><td>' . htmlspecialchars($ip, ENT_QUOTES, 'UTF-8') . '</td></tr>';
    $corpo .= '<tr><th>ID do Usuário</th><td>' . htmlspecialchars($idCliente, ENT_QUOTES, 'UTF-8') . '</td></tr>';
    $corpo .= '<tr><th>Nome do Usuário</th><td>' . htmlspecialchars($nomeCliente, ENT_QUOTES, 'UTF-8') . '</td></tr>';
    $corpo .= '<tr><th>Horário de Acesso</th><td>' . htmlspecialchars($horarioAcesso, ENT_QUOTES, 'UTF-8') . '</td></tr>';
    $corpo .= '<tr><th>Rotina Acessada</th><td>' . htmlspecialchars($rotinaAcessada, ENT_QUOTES, 'UTF-8') . '</td></tr>';
    $corpo .= '</table>';

    $emailSuporte = getUserEmailById($pdo, $idCliente);
    if ($emailSuporte) {
        enviarEmail($emailSuporte, $assunto, $corpo);
    } else {
        echo "Erro: Não foi possível encontrar o email do suporte.";
    }
    return false;
}   

?>
