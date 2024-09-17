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
        <title>Documento</title>
    </head>
    <body>
        <form method="post" action="../Controller/categoryRegistrationController.php">
        <link rel="stylesheet "type="text/css" href="../templates/CSS/categoryRegistration.css">
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
