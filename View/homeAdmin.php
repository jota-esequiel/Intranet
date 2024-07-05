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
                    echo "<p>Olá, {$usuario['nome']}!</p>";
                } else {
                    echo "<p>Olá, usuário!</p>";
                }
            } catch (PDOException $e) {
                echo 'Erro ao recuperar o nome de usuário: ' . $e->getMessage();
            }
        }
        ?>
        <a href="userProfile.php"><i class="fas fa-circle-user"></i></a> 
        <a href="productSearch.php"><i class="fas fa-magnifying-glass"></i></a> 
        <a href="consultCategory.php"><i class="fa-solid fa-vials"></i></a>
        <?php logoutUser('logout') ?>
    </header>
    <h1>Produtos Disponíveis</h1>
    <?php
    try {
        $pdo = conectar();
        $stmt = $pdo->prepare("SELECT prod.codproduto,
                                        prod.nomeproduto,
                                        prod.precoproduto,
                                        img.img
                                FROM tb_produtos prod
                                LEFT JOIN tb_imagens img 
                                    ON prod.codproduto = img.codproduto
                                WHERE prod.ativo = 'S';");

        $stmt->execute();
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($produtos as $produto) {
            echo "<h2>{$produto['nomeproduto']}</h2>";
            if (!empty($produto['img'])) {
                echo "<img src='data:image/jpeg;base64," . base64_encode($produto['img']) . "' alt='{$produto['nomeproduto']}'>";
            } else {
                echo "<p>Imagem Indisponível</p>";
            }
            echo "<p>Preço: R$ {$produto['precoproduto']}</p>";
            echo "<form action='../Controller/addToShoppingCartController.php' method='post'>";
            echo "<input type='hidden' name='codproduto' value='{$produto['codproduto']}'>";
            echo "<button type='submit'>Adicionar ao Carrinho</button>";
            echo "</form>";
        }
    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getMessage();
    }
    ?>
</body>
</html>