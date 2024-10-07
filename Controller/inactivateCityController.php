<?php
include_once '../bdConnection.php';

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['codcid'])) {
    $pdo = conectar();
    $codcategoria = $_GET['codcid'];

    $sql = "UPDATE tb_cidades
                SET ativo = 'N'
                WHERE codcid = :codcid";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codcid', $codcid, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Cidade inativada com sucesso!')</script>";
    } else {
        echo "<script>alert('Erro ao inativar o cidade!')</script>";
    }
} else {
    echo "Código da cidade não foi passada como parâmetro!";
}
header('Location: ../View/consultCity.php');
?>