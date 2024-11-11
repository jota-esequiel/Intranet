<?php
include_once '../bdConnection.php';
include '../Controller/standardFunctionsController.php';

if (checkUserType('A')) {
    try {
        $pdo = conectar();
        $stmt = $pdo->prepare("SELECT codcategoria, nomecategoria FROM tb_categorias");
        $stmt->execute();
        $category = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getMessage();
    }
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Categorias | TCC</title>
    <script src="../templates/JS/mask.js"></script>
    <link rel="stylesheet" type="text/css" href="../templates/CSS/productRegistration.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    </head>
    <body>
        <form method="post" action="../Controller/categoryRegistrationController.php">
        <link rel="stylesheet "type="text/css" href="../templates/CSS/productRegistration.css">
        
        <div class="container">
            <h1>CADASTRE UMA NOVA CATEGORIA</h1>
            <label>Nome</label>
            <input type="text" name="nomecategoria" placeholder="Informe o nome da categoria..." required>
            <br><br>
            <button type="submit" name="btnEnviar">CADASTRE</button>
        </form>
    </body>
    </html>

    <?php 
    } else {
        destroySession('../View/loginUser.php');
    }
    ?>
</div>