<?php
/**
 * Este arquivo trata da ativação de produtos.
 *
 * Ele recebe um código de produto via GET e verifica se o produto está marcado como ativo ('S') ou inativo ('N') no banco de dados.
 * Se o produto estiver inativo, ele é ativado ('S'). Caso contrário, uma mensagem informando que o produto já está ativo é exibida.
 * @author Gabrielli 
 * @param string $codproduto O código do produto que será ativado.
 * @param array $stmtCheck O statement PDO utilizado para verificar se o produto está ativo ou não.
 */

include_once '../bdConnection.php';
$pdo = conectar();

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['codproduto'])) {
    $codproduto = $_GET['codproduto'];

    $sqlCheck = "SELECT ativo
                    FROM tb_produtos
                    WHERE codproduto = :codproduto";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->bindParam(':codproduto', $codproduto, PDO::PARAM_INT);
    $stmtCheck->execute();

    $systemAtivo = $stmtCheck->fetchColumn();

    if($systemAtivo == 'N') {
        $sql = "UPDATE tb_produtos
                    SET ativo = 'S'
                    WHERE codproduto = :codproduto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':codproduto', $codproduto, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Produto ativo com sucesso!')</script>";
        } else {
            echo "<script>alert('Erro ao ativar produto!')</script>";
        }
    } else {
        echo "<script>alert('Produto já está ATIVO!</script>";
    }
    header('Location: ../View/consultProduct.php');
}
?>