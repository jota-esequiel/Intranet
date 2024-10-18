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

    <!-- Bootstrap CSS -->
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>

<header class="d-flex justify-content-between align-items-center">
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
            } catch (PDOException $e) {
                echo 'Erro ao recuperar o nome de usuário: ' . $e->getMessage();
            }
        }

        
        ?>

<div class="img">
        <?php
        echo getImgPath('logo', 90, 80, null);

?>
</div>


        <div class="icon">
        <a href="../View/homeAdmin.php"><i class="fa-solid fa-house"></i></a>
        <a href="userProfile.php"><i class="fas fa-circle-user"></i></a> 
        <?php logoutUser('logout') ?>
    </div>
        
    </header>

        <body>
        <div class="organizacao">
        <a href="../View/consultUser.php">ADICIONAR ADMINISTRADOR, CONSULTAR, EXCLUIR, INATIVAR E EDITAR USUARIOS</a>
        <a href="consultCategory.php">ADICIONAR, CONSULTAR, EXCLUIR, INATIVAR E EDITAR CATEGORIAS</a>
        <a href="../View/consultProduct.php">ADICIONAR, CONSULTAR, EXCLUIR, INATIVAR E EDITAR PRODUTOS</a>
        <a href="../View/consultCity.php">ADICIONAR, CONSULTAR, EXCLUIR, INATIVAR E EDITAR CIDADES</a>
        </div>
        
        </body>
</body>
</html>