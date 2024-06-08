<?php
session_start();
include_once '../bdConnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = md5($_POST['senha']); 

    $pdo = conectar();

    $sql = "SELECT * FROM tb_clientes WHERE email = :email AND senha = :senha";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        if ($usuario['ativo'] == 'S') {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['ativo'] = 'S';
            if ($usuario['tipo'] == 'C') {
                header('Location: ../View/homeClient.php');
                exit();
            } elseif ($usuario['tipo'] == 'A') {
                header('Location: ../View/homeAdmin.php');
                exit();
            }
        } else {
            echo "<script>alert('Usuário inativo.')</script>";
            echo "<script>window.location.href = 'loginUser.php';</script>";
        }
    } else {
        echo "<script>alert('Usuário ou senha inválidos.')</script>";
        echo "<script>window.location.href = 'loginUser.php';</script>";
    }
}
?>
