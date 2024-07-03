<?php 
include_once '../bdConnection.php';
$pdo = conectar();

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['codcategoria'])) {
    $codcategoria = $_GET['codcategoria'];

    $sqlCheck = "SELECT ativo
                    FROM tb_categorias
                    WHERE codcategoria = :codcategoria";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->bindParam(':codcategoria', $codcategoria, PDO::PARAM_INT);
    $stmtCheck->execute();

    $systemAtivo = $stmtCheck->fetchColumn();

    if($systemAtivo == 'N') {
        $sql = "UPDATE tb_categorias
                    SET ativo = 'S'
                    WHERE codcategoria = :codcategoria";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':codcategoria', $codcategoria, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Categoria ativo com sucesso!')</script>";
        } else {
            echo "<script>alert('Erro ao ativar categoria!')</script>";
        }
    } else {
        echo "<script>alert('Categoria já está ATIVA!</script>";
    }
    header('Location: ../View/consultCategory.php');
}
?>