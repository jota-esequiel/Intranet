 <!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet "type="text/css" href="../templates/CSS/homeAdmin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <title>Home | Administrador</title>
    <link href="../fontawesome/css/all.css" rel="stylesheet">
</head>
<body>
    <header>
    <img src="intranet/img/logo.png"></img>
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
        <div class="cabecalho">
        <a href="productSearch.php"><i class="fas fa-magnifying-glass"></i></a> 
        </div>
        <a href="userProfile.php"><i class="fas fa-circle-user"></i></a> 
    
        <?php logoutUser('logout') ?>
    </div>
        
    </header>
        <body>
        <div class="organizacao">
        <a href="consultCategory.php"> Editar Categorias</a>
        <a href="../View/productRegistration.php">Adicionar Produtos</a>
        <a href="../View/consultUser.php">Rotinas Usuários</a>
        </div>
    </body>
</body>
</html>