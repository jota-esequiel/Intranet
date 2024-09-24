<?php
session_start();

include '../bdConnection.php';

if (empty($_SESSION['usuario'])) {
    header("Location: ../View/loginUser.php");
    exit();
}

if (empty($_SESSION['carrinho'])) {
    echo "Seu carrinho de compras está vazio.";
    exit();
}

$carrinho = $_SESSION['carrinho'];
$total = 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../templates/CSS/shoppingCart.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>

<!-- Optional JavaScript -->
   <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
   <header class="d-flex justify-content-between align-items-center">
    
    <div class="container">
        <h1>Carrinho de Compras</h1>
        <?php
        include '../Controller/standardFunctionsController.php';

        $nomeCliente = $_SESSION['usuario']['nome'];
        echo "<p>Este é seu carrinho de compras, " . htmlspecialchars($nomeCliente, ENT_QUOTES, 'UTF-8') . "!</p>";
        ?>
        
        <table border="1">
            <thead>
                <tr>
                    <th></th>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                    <th>Ações</th>
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
                    <td colspan="3">Total</td>
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
