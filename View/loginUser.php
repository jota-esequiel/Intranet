<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
</head>
<body>
  <h2>Login</h2>
  <form action="../Controller/loginUserController.php" method="POST">
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br>
    <label for="senha">Senha:</label><br>
    <input type="password" id="senha" name="senha" required><br><br>
    <input type="submit" value="Login">
  </form>
  <p>Não tem uma conta? <a href="../View/userRegistration.php">Crie uma aqui</a>.</p>
</body>
</html>