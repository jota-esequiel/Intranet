<?php
include_once '../bdConnection.php';

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['codcliente'])) {
    $pdo = conectar();
    $codcliente = $_GET['codcliente'];

    $sql = "UPDATE tb_clientes
                SET ativo = 'N'
                WHERE codcliente = :codcliente";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codcliente', $codcliente, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Usuário inativado com sucesso!')</script>";
    } else {
        echo "<script>alert('Erro ao inativar o usuário!')</script>";
    }
} else {
    echo "Código do cliente não foi passado como parâmetro!";
}
header('Location: ../View/consultUser.php');
?>
