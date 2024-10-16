<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
    <link rel="stylesheet "type="text/css" href="../templates/CSS/EsqueciSenha.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <title>Esqueci a Senha</title>
</head>
<body>
<?php
include_once '../bdConnection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$pdo = conectar();

// Função para enviar e-mail
function enviarEmail($email, $assunto, $mensagemHTML, $mensagemTexto) {
    $mail = new PHPMailer(true);
    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Username = 'testetcc198@gmail.com';     // SMTP username
        $mail->Password = 'jxpd cdwc lvjp dbnn';               // SMTP password
        $mail->SMTPSecure = 'tls';                     // Use 'tls' ou 'ssl' conforme seu provedor
        $mail->Host = 'smtp.gmail.com';                // Substitua pelo servidor SMTP correto
        $mail->Port = 587;                             // Porta TLS

        // Configurações do e-mail
        $mail->setFrom('testetcc198@gmail.com', 'Luar Floricultura'); // Substitua pelo seu e-mail
        $mail->addAddress($email);                                  // Destinatário

        $mail->isHTML(true);                                        // Formato HTML
        $mail->Subject = $assunto;                                  // Assunto do e-mail
        $mail->Body    = $mensagemHTML;                             // Corpo do e-mail em HTML
        $mail->AltBody = $mensagemTexto;                            // Alternativa para clientes sem suporte HTML

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Verifique se o e-mail existe no banco de dados
    $sql = "SELECT * FROM tb_clientes WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Gera um token seguro
        $token = bin2hex(random_bytes(50));

        // Armazena o token e a validade no banco de dados
        $query = "UPDATE tb_clientes SET token_cli = ?, token_validade = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$token, $email]);

        // Gera o link de redefinição de senha
        $link = "http://localhost/intranet%20Site/RedefinirSenha.php?token=$token";
        $assunto = "Redefinir sua senha";
        $mensagemHTML = "Clique no link para redefinir sua senha: <a href='$link'>$link</a>";
        $mensagemTexto = "Clique no link para redefinir sua senha: $link";

        // Envie o e-mail usando PHPMailer
        if (enviarEmail($email, $assunto, $mensagemHTML, $mensagemTexto)) {
            echo "<script>alert('Um link de redefinição de senha foi enviado para o seu e-mail.');</script>";
            header("Location: Confirmacao.php"); 
            exit; // Adicione exit após o header para garantir que o script não continue
        } else {
            echo "Erro ao enviar o e-mail. Tente novamente.";
        }
    } else {
        echo "E-mail não encontrado!";
    }
}
?>

<form action="" method="POST">
    <input type="email" name="email" placeholder="Digite seu e-mail" required>
    <button type="submit">Recuperar Senha</button>
</form>
<a href="loginUser.php" class="button">Voltar</a>
</body>
</html>