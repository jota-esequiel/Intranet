<?php 
include_once '../bdConnection.php';

if(isset($_GET['codcliente'])) {
    $codcliente = $_GET['codcliente'];

    try {
        $pdo = conectar();
        $pdo->beginTransaction();

        $sqlDeleteCity = "DELETE FROM tb_cidades WHERE codcid = :codcid";
        $stmtDeleteCity = $pdo->prepare($sqlDeleteCity);
        $stmtDeleteCity->bindParam(':codcid', $codcliente, PDO::PARAM_INT);
        $stmtDeleteCity->execute();

        $sqlDeleteUser = "DELETE FROM tb_clientes WHERE codcliente = :codcliente";
        $stmtDeleteUser = $pdo->prepare($sqlDeleteUser);
        $stmtDeleteUser->bindParam('codcliente', $codcliente, PDO::PARAM_INT);
        $stmtDeleteUser->execute();

        $pdo->commit();

        header("Location: ../View/consultUser.php");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Erro ao excluir cliente: " . $e->getMessage();
    }
}
?>