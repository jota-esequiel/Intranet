<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
</head>
<body>
    <h2>Recuperar Senha</h2>
    <form action="../Controller/processarRecuperacaoPassword.php" method="POST">
        <label for="email">Digite seu e-mail:</label><br>
        <input type="email" name="email" id="email" required><br><br>
        <input type="submit" value="Enviar">
    </form>
</body>
</html>
