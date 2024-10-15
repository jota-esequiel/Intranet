<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; 

function enviarEmail($destinatario, $assunto, $conteudo) {
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 3;
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'joaovitoresequielvieira@outlook.com'; 
        $mail->Password = 'pyhtjietvinxeafv'; 
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587; 

        
        $mail->setFrom('joaovitoresequielvieira@outlook.com', 'João Vitor Esequiel Vieira'); 
        $mail->addAddress($destinatario); 

        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body = $conteudo;

        $mail->send();
        return true; 
    } catch (Exception $e) {
        return false; 
    }
}

?>