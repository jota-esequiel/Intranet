<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
    <link rel="stylesheet "type="text/css" href="../templates/CSS/RedefinirSenha.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <title>Esqueci a Senha</title>
</head>
<body>
<div class="container">
<?php
include_once '../bdConnection.php';
$pdo = conectar();

if (isset($_GET['token_cli'])) {
    $token_cli = $_GET['token_cli'];
    error_log("Token recebido: " . $token_cli); // Corrigido para usar 'token_cli'

    // Verificar se o token é válido e ainda não expirou
    $query = "SELECT * FROM tb_clientes WHERE token_cli = ? AND token_validade > NOW()";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(1, $token_cli, PDO::PARAM_STR);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Se o token for válido, exibe o formulário para redefinir a senha
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nova_senha = $_POST['senha'];
            $confirmar_senha = $_POST['confirmar_senha'];

            if ($nova_senha === $confirmar_senha) {
                // Criptografa a nova senha
                $senha_criptografada = md5($nova_senha);

                // Atualiza a senha no banco de dados e remove o token
                $query = "UPDATE tb_clientes SET senha = :senha, token_cli = NULL, token_validade = NULL WHERE codcliente = :codcliente";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':senha', $senha_criptografada, PDO::PARAM_STR);
                $stmt->bindParam(':codcliente', $usuario['codcliente'], PDO::PARAM_INT);
                $stmt->execute();

                echo "<script>alert('Sua senha foi redefinida com sucesso!');</script>";
                echo "<script>location.href='loginUser.php';</script>";
                exit; // Adicionado exit para evitar execução adicional
            } else {
                echo "As senhas não coincidem. Tente novamente.";
            }
        }
    } else {
        echo "Link inválido ou expirado!";
    }
} else {
    echo "Token não fornecido!";
}
?>

<!-- Formulário de redefinição de senha -->
<form action="" method="POST">
    <input type="password" name="senha" placeholder="Nova senha" required>
    <input type="password" name="confirmar_senha" placeholder="Confirmar nova senha" required>
    <button type="submit">Redefinir Senha</button>
</form>

</div>
</body>
</html>
