<?php
session_start();
include_once '../bdConnection.php';
include '../Controller/standardFunctionsController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codcliente  = $_POST['codcliente'];
    $nome        = $_POST['nome'];
    $email       = $_POST['email'];
    $cep         = formatarCEPSQL($_POST['cep']);
    $rua         = $_POST['rua'];
    $ncasa       = $_POST['ncasa'];
    $complemento = $_POST['complemento'];
    $fone        = formatarTelefoneSQL($_POST['fone']);

    $strPdo = conectar();

    $strQuery = "UPDATE tb_clientes SET 
                    nome          = :nome, 
                    email         = :email, 
                    cep           = :cep, 
                    rua           = :rua, 
                    ncasa         = :ncasa, 
                    complemento   = :complemento, 
                    fone          = :fone 
                 WHERE codcliente = :codcliente";

    $stmt = $strPdo->prepare($strQuery);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':cep', $cep);
    $stmt->bindParam(':rua', $rua);
    $stmt->bindParam(':ncasa', $ncasa);
    $stmt->bindParam(':complemento', $complemento);
    $stmt->bindParam(':fone', $fone);
    $stmt->bindParam(':codcliente', $codcliente);

    if ($stmt->execute()) {
        echo "<script>alert('Perfil atualizado com sucesso!'); window.location.href='../View/homeClient.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar perfil!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Método de requisição inválido!'); window.history.back();</script>";
}
?>
