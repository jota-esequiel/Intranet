<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../templates/CSS/productCatalog.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    <title>Home | Cliente</title>
    <link href="../fontawesome/css/all.css" rel="stylesheet">
</head>
<body>
    <header>

   
        <?php
        session_start();
        include_once '../bdConnection.php';
        include '../Controller/standardFunctionsController.php';

        if (isset($_SESSION['usuario'])) {
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
      
      <div class="categorias">
        <?php 
        include_once '../bdConnection.php';
        $pdo = conectar();

        $sql = 'SELECT codcategoria, nomecategoria FROM tb_categorias';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($categorias) {
            echo '<nav>';  
            echo '<ul>';
        
            foreach ($categorias as $categoria) {
                echo '<li><a href="productCatalog.php?categoria=' . urlencode($categoria['codcategoria']) . '">' . ucfirst($categoria['nomecategoria']) . '</a></li>';
            }

            echo '</ul>';
            echo '</nav>';
        } else {
            echo 'Nenhuma categoria encontrada';
        }
        ?>
        </div>
         <div class="icon">
         <a href="../View/serchProduct.php" id="searchIcon"></a><id="searchBar">
        <form action="searchProduct.php" method="get">
        <input type="text" name="search" id="searchBoxInput" placeholder="Digite sua pesquisa" value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '', ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit" id="searchIcon"><i class="fas fa-magnifying-glass"></i></button></form>
        <a href="../View/userProfile.php"><i class="fas fa-circle-user"></i></a> 
        <a href="../View/shoppingCart.php"><i class="fas fa-cart-shopping"></i></a> 
        <?php logoutUser('logout') ?>
    </div>
    </header>
    <h1>Produtos Disponíveis</h1>
    <div class="produtos">
        <?php
        try {
            $pdo = conectar();
            
            $codcategoria = !empty($_GET['categoria']) ? $_GET['categoria'] : null;

            $sql = "SELECT 
                        prod.codproduto,
                        prod.nomeproduto,
                        prod.precoproduto,
                        prod.ativo AS ativoproduct,
                        cat.nomecategoria,
                        CASE prod.cor
                            WHEN '1' THEN 'Vermelho'
                            WHEN '2' THEN 'Azul'
                            WHEN '3' THEN 'Amarelo'
                            ELSE 'Desconhecido'
                        END AS corProd,
                        CASE prod.tamanho
                            WHEN 'P' THEN 'Pequeno'
                            WHEN 'M' THEN 'Médio'
                            WHEN 'G' THEN 'Grande'
                            ELSE 'Desconhecido'
                        END AS tamanhoProd,
                        img.img
                    FROM tb_produtos prod
                    INNER JOIN tb_categorias cat ON prod.codcategoria = cat.codcategoria
                    LEFT JOIN tb_imagens img ON prod.codimg = img.codimg
                    WHERE prod.ativo = 'S'
                    AND (:codcategoria IS NULL OR prod.codcategoria = :codcategoria)";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':codcategoria', $codcategoria, PDO::PARAM_INT);
            $stmt->execute();
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($produtos) {
                foreach ($produtos as $produto) {
                    echo "<div class='produto'>";
                    if (!empty($produto['img'])) {
                        $imgPath = "../imagens/Produtos/" . htmlspecialchars(basename($produto['img']), ENT_QUOTES, 'UTF-8');
                        echo "<img src='{$imgPath}' alt='Imagem do Produto'>";
                    } else {
                        echo "<div class='circle'></div>";
                        echo "<div class='circle'></div>";
                    }
                    echo "<div class='info'>";
                    echo "<h2>" . htmlspecialchars(ucfirst($produto['nomeproduto']), ENT_QUOTES, 'UTF-8') . "</h2>";
                    echo "<p>Preço: " . formatarPrice($produto['precoproduto']) . "</p>";
                    echo "<p>Categoria: " . htmlspecialchars($produto['nomecategoria'], ENT_QUOTES, 'UTF-8') . "</p>";
                    echo "<p>Cor: " . htmlspecialchars($produto['corProd'], ENT_QUOTES, 'UTF-8') . "</p>";
                    echo "<p>Tamanho: " . htmlspecialchars($produto['tamanhoProd'], ENT_QUOTES, 'UTF-8') . "</p>";
                    echo "</div>";

                    if (isset($_SESSION['usuario']) && $_SESSION['usuario'] == true) {
                        echo "<form action='../Controller/addToShoppingCartController.php' method='post'>";
                        echo "<input type='hidden' name='codproduto' value='" . htmlspecialchars($produto['codproduto'], ENT_QUOTES, 'UTF-8') . "'>";
                        echo "<button type='submit'>Adicionar ao Carrinho</button>";
                        echo "</form>";
                    }
                    echo "</div>";
            
                    
                }
            } else {
                echo 'Nenhum produto encontrado para esta categoria';
            }
        } catch (PDOException $e) {
            echo 'Erro: ' . $e->getMessage();
        }
        ?>
    </div>
</body>
</html>
