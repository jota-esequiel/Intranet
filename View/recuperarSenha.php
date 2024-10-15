<?php
include '../bdConnection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $pdo = conectar();
    $query = $pdo->prepare('SELECT * FROM tb_clientes WHERE token = :token AND expiracao > NOW()');
    $query->bindParam(':token', $token);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $novaSenha = md5($_POST['newPass']); 

            $update = $pdo->prepare('UPDATE tb_clientes SET senha = :senha, token = NULL, expiracao = NULL WHERE email = :email');
            $update->bindParam(':senha', $novaSenha);
            $update->bindParam(':email', $user['email']);
            $update->execute();

            echo 'Senha redefinida com sucesso!';
        }
    } else {
        echo 'Token inválido ou expirado!';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
</head>
<body>
    <h2>Redefinir Senha</h2>
    <form method="POST">
        <label for="newPass">Nova Senha:</label>
        <input type="password" name="newPass" required>
        <button type="submit">Redefinir Senha</button>
    </form>
</body>
</html>
