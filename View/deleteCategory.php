<?php 
include_once '../bdConnection.php';

if(isset($_GET['codcategoria'])) {
    $codcategoria = $_GET['codcategoria'];

    try {
        $pdo = conectar();
        $pdo->beginTransaction();

        $sqlDeleteProduct = "DELETE FROM tb_produtos WHERE codcategoria = :codcategoria";
        $stmtDeleteProduct = $pdo->prepare($sqlDeleteProduct);
        $stmtDeleteProduct->bindParam(':codcategoria', $codcategoria, PDO::PARAM_INT);
        $stmtDeleteProduct->execute();

        $sqlDeleteCategory = "DELETE FROM tb_categorias WHERE codcategoria = :codcategoria";
        $stmtDeleteCategory = $pdo->prepare($sqlDeleteCategory);
        $stmtDeleteCategory->bindParam(':codcategoria', $codcategoria, PDO::PARAM_INT);
        $stmtDeleteCategory->execute();

        $pdo->commit();
        
        header("Location: ../View/consultCategory.php");
        exit();

    } catch(PDOException $e) {
        $pdo->rollback();
        echo "Erro ao excluir categoria: " . $e->getMessage();
    }
}
?>
