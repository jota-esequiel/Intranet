<?php 
include '../Controller/enviarEmailController.php';

$destinatario = 'joaovitoresequielvieira@gmail.com';
$assunto = 'Teste de e-mail';
$conteudo = '<p>Este é um teste de e-mail usando PHPMailer com SMTP do Outlook.</p>';

if (enviarEmail($destinatario, $assunto, $conteudo)) {
    echo 'E-mail enviado com sucesso!';
} else {
    echo 'Erro ao enviar o e-mail.';
}

?>