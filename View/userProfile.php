<?php 
session_start();
include_once '../bdConnection.php';
include '../Controller/standardFunctionsController.php';

echo getLink('mask');

$strPdo = conectar();

if ($_SESSION['usuario']['codcliente']) {
    $codcliente = $_SESSION['usuario']['codcliente'];

    $strQuery = "SELECT *
                    FROM tb_clientes
                    WHERE codcliente = :codcliente
                ";

    $stmt = $strPdo->prepare($strQuery);
    $stmt->bindParam(':codcliente', $codcliente);
    $stmt->execute();

    $strResult = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($strResult && checkUserType('C')) {
        $_SESSION['usuario']['nome'] = $strResult['nome'];

        echo displayHelp('Prezado ' . ucfirst($_SESSION['usuario']['nome']) . ', o CPF e DATA DE NASCIMENTO são dados que NÃO podem ser alterados! Caso tenha digitado serrado ao se cadastrar, aperte ', 'alerta', 'link') . '<a href="mailto:gabrielli.dotto@escola.pr.gov.br">' . getLink('email') . '</a>' . ' e solicite a alteração de seus dados, com justificativa plausível!';

        echo '<form action="../Controller/updateUserProfileController.php" method="POST">';
        echo "<input type='hidden' name='codcliente' value='" . htmlspecialchars($strResult['codcliente']) . "'>";

        echo "<label>Nome </label>";
        echo "<input type='text' name='nome' value='" . htmlspecialchars($strResult['nome']) . "'><br><br>";

        echo "<label>Email </label>";
        echo "<input type='text' name='email' value='" . htmlspecialchars($strResult['email']) . "'><br><br>";

        echo "<label>CPF </label>";
        echo "<input type='text' name='cpf' value='" . formatCPF($strResult['cpf']) . "' disabled required oninput='mascaraCPF(this)'><br><br>";

        echo "<label>Data de Nascimento </label>";
        echo "<input type='text' name='dtnasc' value='" . formatDateToBrazilian($strResult['dtnasc']) . "' disabled required oninput='mascaraData(this)'><br><br>";

        echo "<label>CEP </label>";
        echo "<input type='text' name='cep' value='" . formatCEP($strResult['cep']) . "' required oninput='mascaraCEP(this)'><br><br>";

        echo "<label>Rua </label>";
        echo "<input type='text' name='rua' value='" . htmlspecialchars($strResult['rua']) . "'><br><br>";

        echo "<label>Nº Casa </label>";
        echo "<input type='text' name='ncasa' value='" . htmlspecialchars($strResult['ncasa']) . "'><br><br>";

        echo "<label>Complemento </label>";
        echo "<input type='text' name='complemento' value='" . htmlspecialchars($strResult['complemento']) . "'><br><br>";

        echo "<label>Contato </label>";
        echo "<input type='text' name='fone' value='" . formatPhoneNumber($strResult['fone']) . "' required oninput='mascaraTelefone(this)'><br><br>";

        echo getLink('link');
        echo "<button type='submit' name='btnAlterar'>ATUALIZAR PERFIL <i class='fa-solid fa-user-pen'></i></button>";
        echo '</form>';

    } elseif (checkUserType('A')) {
        $_SESSION['usuario']['nome'] = $strResult['nome'];

        echo '<form action="../Controller/updateUserProfileController.php" method="POST">';
        echo "<input type='hidden' name='codcliente' value='" . htmlspecialchars($strResult['codcliente']) . "'>";

        echo "<label>Nome </label>";
        echo "<input type='text' name='nome' value='" . htmlspecialchars($strResult['nome']) . "'><br><br>";

        echo "<label>Email </label>";
        echo "<input type='text' name='email' value='" . htmlspecialchars($strResult['email']) . "'><br><br>";

        echo "<label>CPF </label>";
        echo "<input type='text' name='cpf' value='" . formatCPF($strResult['cpf']) . "' required oninput='mascaraCPF(this)'><br><br>";

        echo "<label>Data de Nascimento </label>";
        echo "<input type='text' name='dtnasc' value='" . formatDateToBrazilian($strResult['dtnasc']) . "' required oninput='mascaraData(this)'><br><br>";

        echo "<label>CEP </label>";
        echo "<input type='text' name='cep' value='" . formatCEP($strResult['cep']) . "' required oninput='mascaraCEP(this)'><br><br>";

        echo "<label>Rua </label>";
        echo "<input type='text' name='rua' value='" . htmlspecialchars($strResult['rua']) . "'><br><br>";

        echo "<label>Nº Casa </label>";
        echo "<input type='text' name='ncasa' value='" . htmlspecialchars($strResult['ncasa']) . "'><br><br>";

        echo "<label>Complemento </label>";
        echo "<input type='text' name='complemento' value='" . htmlspecialchars($strResult['complemento']) . "'><br><br>";

        echo "<label>Contato </label>";
        echo "<input type='text' name='fone' value='" . formatPhoneNumber($strResult['fone']) . "' required oninput='mascaraTelefone(this)'><br><br>";

        echo getLink('link');
        echo "<button type='submit' name='btnAlterar'>ATUALIZAR PERFIL <i class='fa-solid fa-user-pen'></i></button>";
        echo '</form>';
} else {
    echo "<script>alert('Não foi possível encontrar o usuário logado!')</script>";
}}
?>