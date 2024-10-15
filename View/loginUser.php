<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet "type="text/css" href="../templates/CSS/loginUser.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
   
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
</head>
<body>

</img>
   <div class="container">   
  <h2>LOGIN</h2>
  <form action="../Controller/loginUserController.php" method="POST">
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br>
    <label for="senha">Senha:</label><br>
    <input type="password" id="senha" name="senha" required><br><br>
    <input type="submit" value="Login">
  </form>
  <div class="senha">
  <p><a href="">(Esqueci minha senha)</a></p>
</div>
  <p>Não tem uma conta? <a href="../View/userRegistration.php">Crie uma aqui</a>.</p>
</div>
<div class="img">   
<?php
        session_start();
        include_once '../bdConnection.php';
        include '../Controller/standardFunctionsController.php';

        echo getImgPath('mais', 2000, 1900, null);
?>
</div>
</body>
</html>
