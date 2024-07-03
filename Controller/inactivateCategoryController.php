<?php
include_once '../bdConnection.php';

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['codcategoria'])) {
    $pdo = conectar();
    $codcategoria = $_GET['codcategoria'];

    $sql = "UPDATE tb_categorias
                SET ativo = 'N'
                WHERE codcategoria = :codcategoria";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codcategoria', $codcategoria, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Categoria inativado com sucesso!')</script>";
    } else {
        echo "<script>alert('Erro ao inativar o categoria!')</script>";
    }
} else {
    echo "Código da categoria não foi passada como parâmetro!";
}
header('Location: ../View/consultCategory.php');
?>
