<?php 
include_once '../bdConnection.php';
$pdo = conectar();

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['codcliente'])) {
    $codcliente = $_GET['codcliente'];

    $sqlCheck = "SELECT ativo
                    FROM tb_clientes
                    WHERE codcliente = :codcliente";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->bindParam(':codcliente', $codcliente, PDO::PARAM_INT);
    $stmtCheck->execute();

    $systemAtivo = $stmtCheck->fetchColumn();

    if($systemAtivo == 'N') {
        $sql = "UPDATE tb_clientes
                    SET ativo = 'S'
                    WHERE codcliente = :codcliente";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':codcliente', $codcliente, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Usuário ativo com sucesso!')</script>";
        } else {
            echo "<script>alert('Erro ao ativar usuário!')</script>";
        }
    } else {
        echo "<script>alert('Usuário já está ATIVO!</script>";
    }
    header('Location: ../View/consultUser.php');
}
?>