
<?php
session_start();

include '../bdConnection.php';
include '../Controller/standardFunctionsController.php';

if (empty($_SESSION['usuario'])) {
    header("Location: ../View/loginUser.php");
    exit();
}

$carrinho = $_SESSION['carrinho'];
$total = 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<header class="d-flex justify-content-between align-items-center">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../templates/CSS/shoppingCart.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
 
<?php
echo getImgPath('logo', 90, 80, null);
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
</head>
<body>

<!-- Optional JavaScript -->
   <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
    <div class="container">
        <h1>Carrinho de Compras</h1>
        <?php

        $nomeCliente = $_SESSION['usuario']['nome'];
        echo "<p>Este é seu carrinho de compras, " . htmlspecialchars($nomeCliente, ENT_QUOTES, 'UTF-8') . "!</p>";
        ?>
        
        <table class="table table-bordered">
            <tr>
                <th scope="col"></th>
                <th scope="col">Produto</th>
                <th scope="col">Preço</th>
                <th scope="col">Quantidade</th>
                <th scope="col">Total</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
            <tbody>
                <?php foreach ($carrinho as $codProduto => $produto): 
                    $subtotal = $produto['precoproduto'] * $produto['quantidade'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td>
                            <?php 
                            if (!empty($produto['img'])) {
                                $imgPath = "../imagens/Produtos/" . htmlspecialchars(basename($produto['img']), ENT_QUOTES, 'UTF-8');
                                echo "<img src='{$imgPath}' alt='Imagem do Produto' width='100' height='100'>";
                            } else {
                                echo "<div class='circle'></div><div class='circle'></div>";
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($produto['nomeproduto']); ?></td>
                        <td><?php echo formatarPrice($produto['precoproduto'], 2, ',', '.'); ?></td>
                        <td><?php echo $produto['quantidade']; ?></td>
                        <td><?php echo formatarPrice($subtotal, 2, ',', '.'); ?></td>
                        <td>
                            <a href="../Controller/updateCartController.php?action=add&codproduto=<?php echo $codProduto; ?>"><i class="fas fa-plus"></i></a>
                            <a href="../Controller/updateCartController.php?action=remove&codproduto=<?php echo $codProduto; ?>"><i class="fas fa-minus"></i></a>
                            <a href="../Controller/updateCartController.php?action=delete&codproduto=<?php echo $codProduto; ?>"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                <td colspan="4" class="text-right"><strong>Total</strong></td>
                    <td><?php echo number_format($total, 2, ',', '.'); ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <br>
        <a href="../Controller/checkOutController.php">Finalizar Compra</a>
    </div>
</body>
</html>
