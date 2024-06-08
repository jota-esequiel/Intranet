<?php
include_once '../bdConnection.php';

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['codproduto'])) {
    $pdo = conectar();
    $codproduto = $_GET['codproduto'];

    $sql = "UPDATE tb_produtos
                SET ativo = 'N'
                WHERE codproduto = :codproduto";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codproduto', $codproduto, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Produto inativado com sucesso!')</script>";
    } else {
        echo "<script>alert('Erro ao inativar o produto!')</script>";
    }
} else {
    echo "Código do cliente não foi passado como parâmetro!";
}
header('Location: ../View/consultProduct.php');
?>
