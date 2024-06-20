<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/xampp/htdocs/Intranet/vendor/autoload.php'; 
require '/xampp/htdocs/Intranet/emailComposer/emailFunctions.php'; 

function enviarEmail($emailDestino, $assunto, $corpo) {
    $mail = new PHPMailer(true); 

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'joaovitoresequielvieira@outlook.com'; 
        $mail->Password = 'V2nd3r13i@'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('joaovitoresequielvieira@outlook.com', 'João Vitor');
        $mail->addAddress($emailDestino);

        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body = $corpo;

        $mail->send();
        echo 'E-mail enviado com sucesso!';
    } catch (Exception $e) {
        echo 'Erro ao enviar e-mail: ' . $mail->ErrorInfo;
    }
}

$pdo = conectar();

$htmlClientes = usersToEmail($pdo);
$assunto = 'Dados dos Clientes Cadastrados';

$emailDestino = 'joaovitoresequielvieira@gmail.com';

enviarEmail($emailDestino, $assunto, $htmlClientes);

?>
