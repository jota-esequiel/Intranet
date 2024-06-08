<?php 
include_once '../bdConnection.php';

if(isset($_POST['btnEnviar'])) {
    $nomecategoria = $_POST['nomecategoria'];

    try {
        $pdo = conectar();

        $stmt = $pdo->prepare("SELECT * FROM tb_categorias WHERE nomecategoria = :nomecategoria");
        $stmt->bindValue('nomecategoria', $nomecategoria);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $mensagem = "<script>alert('Categoria já cadastrada no sistema!')</script>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO tb_categorias (nomecategoria) VALUES (:nomecategoria)");
            $stmt->bindValue('nomecategoria', $nomecategoria);
            $stmt->execute();

            header("Location: ../View/categoryRegistration.php?mensagem=Categoria cadastrada com sucesso!");
            exit();
        }
    } catch (PDOException $e) {
        die("Erro" . $e->getMessage());
    }
}
?>