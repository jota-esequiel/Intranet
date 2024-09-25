<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../templates/CSS/homeClient.css">
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

 <!-- Bootstrap CSS -->
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Home | Cliente</title>
    <link href="../fontawesome/css/all.css" rel="stylesheet">
</head>
<body>
    <!-- Optional JavaScript -->
   <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
   <header class="d-flex justify-content-between align-items-center">
    
        <?php
        session_start();
        include_once '../bdConnection.php';
        include '../Controller/standardFunctionsController.php';

        echo getImgPath('logo', 90, 80, null);


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
        <form action="../Controller/searchProduct.php" method="GET" class="d-flex align-items-center">
            <input type="text" name="search" id="searchBoxInput" class="form-control" placeholder="Digite sua pesquisa" value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '', ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit" class="btn btn-link" id="searchIcon"><i class="fas fa-magnifying-glass"></i></button>
        </form>
          <a href="../View/homeClient.php"><i class="fa-solid fa-house"></i></a>
        <a href="../View/userProfile.php" class="ml-3"><i class="fas fa-circle-user"></i></a>
        <a href="../View/shoppingCart.php" class="ml-3"><i class="fas fa-cart-shopping"></i></a>
        <?php logoutUser('logout'); ?>
    </div>
    
    </header>
    <h1>Produtos Disponíveis</h1>
    <div class="produtos">
        <?php
        try {
            $pdo = conectar();
            $stmt = $pdo->prepare("SELECT 
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
                                INNER JOIN tb_categorias cat 
                                    ON prod.codcategoria = cat.codcategoria
                                LEFT JOIN tb_imagens img 
                                    ON prod.codimg = img.codimg
                                WHERE prod.ativo = 'S'");

            $stmt->execute();
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
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
                echo "<p>Tamanho: " . htmlspecialchars($produto['tamanhoProd'], ENT_QUOTES, 'UTF-8') . "</p>";
                
                echo "<div class='add-to-cart-button'>";
                
                if (isset($_SESSION['usuario']) && $_SESSION['usuario'] == true) {
                    echo "<form action='../Controller/addToShoppingCartController.php' method='POST'>";
                    echo "<input type='hidden' name='codproduto' value='" . htmlspecialchars($produto['codproduto'], ENT_QUOTES, 'UTF-8') . "'>";
                    echo "<button type='submit'><i class='fa-solid fa-cart-shopping'>Adicionar ao carrinho</i></button>";
                    echo "</form>";
                
                }
                echo "</div>";
            echo "</div>";
            echo "</div>";
            }
        } catch (PDOException $e) {
            echo 'Erro: ' . $e->getMessage();
        }
        ?>
    </div>
    </div>
    <script src="../templates/JS/main.js"></script>
</body>
</html>
