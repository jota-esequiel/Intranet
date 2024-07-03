<?php 
session_start();
include_once '../bdConnection.php';

if(isset($_POST['btnSalvar'])) {
    $nomeCidade = $_POST['nomecidade'];
    $uf = $_POST['uf'];

    try {
        $pdo = conectar();

        $stmt = $pdo->prepare("SELECT * FROM tb_cidades
                                    WHERE nomecidade = :nomecidade
                                    AND uf = :uf");
        $stmt->bindValue(':nomecidade', $nomeCidade);
        $stmt->bindValue(':uf', $uf);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $_SESSION['mensagem'] = "Cascavel/PR já está cadastrada no sistema";
            header("Location: ../View/cityRegistration.php"); 
            exit();
        } else {
            $stmt = $pdo->prepare("INSERT INTO tb_cidades (nomecidade, uf) VALUES (:nomecidade, :uf)");
            $stmt->bindValue(':nomecidade', $nomeCidade);
            $stmt->bindValue(':uf', $uf);
            $stmt->execute();

            $_SESSION['mensagem'] = "Cidade cadastrada com sucesso";
            header("Location: ../View/cityRegistration.php"); 
            exit();
        }
    } catch (PDOException $e) {
        die("Erro na execução da consulta: " . $e->getMessage());
    }
}
?>