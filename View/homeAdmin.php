 <!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet "type="text/css" href="../templates/CSS/homeAdmin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Administrador</title>
    <link href="../fontawesome/css/all.css" rel="stylesheet">
</head>
<body>
    <header>
        <?php
        session_start();
        include_once '../bdConnection.php';
        include_once '../Controller/standardFunctionsController.php';

        if(isset($_SESSION['usuario'])) {

            try {
                $pdo = conectar();

                $stmt = $pdo->prepare("SELECT nome FROM tb_clientes WHERE codcliente = :codcliente");
                $stmt->bindParam(':codcliente', $_SESSION['usuario']['codcliente']);
                $stmt->execute();
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if($usuario) { 
                    echo saudar() . ucfirst($usuario['nome']) . "!";
                } else {
                    echo saudar() . "<p>usuário!</p>";
                }
            } catch (PDOException $e) {
                echo 'Erro ao recuperar o nome de usuário: ' . $e->getMessage();
            }
        }
        ?>
        <a href="userProfile.php"><i class="fas fa-circle-user"></i></a> 
        <a href="productSearch.php"><i class="fas fa-magnifying-glass"></i></a> 
        <a href="consultCategory.php"><i class="fa-solid fa-vials"></i></a>
        <a href="../View/productRegistration.php"><i class="fa-solid fa-tag"></i></a>
        <a href="../View/consultUser.php"><i class="fa-solid fa-book"></i></a>
        <?php logoutUser('logout') ?>
</body>
</html>