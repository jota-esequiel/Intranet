<?php 
include_once '../bdConnection.php';
$pdo = conectar();

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['codcid'])) {
    $codcategoria = $_GET['codcid'];

    $sqlCheck = "SELECT ativo
                    FROM tb_cidades
                    WHERE codcid = :codcid";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->bindParam(':codcid', $codcid, PDO::PARAM_INT);
    $stmtCheck->execute();

    $systemAtivo = $stmtCheck->fetchColumn();

    if($systemAtivo == 'N') {
        $sql = "UPDATE tb_cidades
                    SET ativo = 'S'
                    WHERE codcid = :codcid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':codcid', $codcategoria, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Cidade ativa com sucesso!')</script>";
        } else {
            echo "<script>alert('Erro ao ativar cidade!')</script>";
        }
    } else {
        echo "<script>alert('Cidade já está ATIVA!</script>";
    }
    header('Location: ../View/consultCity.php');
}
?>