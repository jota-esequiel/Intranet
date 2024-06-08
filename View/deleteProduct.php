<?php 
include_once '../bdConnection.php';

if(isset($_GET['codproduto'])) {
    $codproduto = $_GET['codproduto'];

    try {
        $pdo = conectar();
        $pdo->beginTransaction();

        $sqlDeleteCategory = "DELETE FROM tb_categorias WHERE codcategoria = :codcategoria";
        $stmtDeleteCategory = $pdo->prepare($sqlDeleteCategory);
        $stmtDeleteCategory->bindParam(':codcategoria', $codproduto, PDO::PARAM_INT);
        $stmtDeleteCategory->execute();

        $sqlDeleteProduct = "DELETE FROM tb_produtos WHERE codproduto = :codproduto";
        $stmtDeleteProduct = $pdo->prepare($sqlDeleteProduct);
        $stmtDeleteProduct->bindParam('codproduto', $codproduto, PDO::PARAM_INT);
        $stmtDeleteProduct->execute();

        $pdo->commit();

        header("Location: ../View/consultProduct.php");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Erro ao excluir produto: " . $e->getMessage();
    }
}
?>