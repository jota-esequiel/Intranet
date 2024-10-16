<?php

require '../bdConnection.php';
include '../Controller/enviarEmailController.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    $pdo = conectar();

    $strQuery = $pdo->prepare('SELECT email FROM tb_clientes WHERE email = :email');
    $strQuery->bindParam(':email', $email);
    $strQuery->execute();

    $userEmail = $strQuery->fetch(PDO::FETCH_ASSOC);

    if (!empty($userEmail)) {
        $tokenKey = bin2hex(random_bytes(50));
        $expH = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $updateToken = $pdo->prepare('UPDATE tb_clientes SET token = :token, expiracao = :expiracao WHERE email = :email');
        $updateToken->bindParam(':token', $tokenKey);
        $updateToken->bindParam(':expiracao', $expH);
        $updateToken->bindParam(':email', $email);
        $updateToken->execute();

        $url = 'http://localhost/Intranet/View/recuperarSenha.php?token=' . $tokenKey; 
        $corpo = "<p><a href='" . $url . "'>" . $url . "</a></p>";
        $assunto = "Recuperação de Senha";

        if (enviarEmail($email, $assunto, $corpo)) {
            echo '<script>alert("Um e-mail de recuperação de senha foi enviado para você!")</script>';
        } else {
            echo '<script>alert("Erro ao enviar o e-mail.")</script>';
        }
    } else {
        echo '<script>alert("Não encontramos seu e-mail em nossa base de dados, verifique se o e-mail digitado está correto!")</script>';
    }
}


?>
