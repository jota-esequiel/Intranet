<?php 
include_once '../bdConnection.php';

if(isset($_GET['codcid'])) {
    $codcid = $_GET['codcid'];

    try {
        $pdo = conectar();
        $pdo->beginTransaction();

        $sqlDeleteClientes = "DELETE FROM tb_clientes WHERE codcid = :codcid";
        $stmtDeleteClientes = $pdo->prepare($sqlDeleteClientes);
        $stmtDeleteClientes->bindParam(':codcid', $codcid, PDO::PARAM_INT);
        $stmtDeleteClientes->execute();

        $sqlDeleteCidade = "DELETE FROM tb_cidades WHERE codcid = :codcid";
        $stmtDeleteCidade = $pdo->prepare($sqlDeleteCidade);
        $stmtDeleteCidade->bindParam(':codcid', $codcid, PDO::PARAM_INT);
        $stmtDeleteCidade->execute();

        $pdo->commit();
        
        header("Location: ../View/consultCity.php");
        exit();

    } catch(PDOException $e) {
        $pdo->rollback();
        echo "Erro ao excluir cidade: " . $e->getMessage();
    }
}
?>
