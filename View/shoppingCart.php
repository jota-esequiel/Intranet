<?php
session_start();
include '../bdConnection.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../View/loginUser.php");
    exit();
}

if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="style.css"> -->
</head>
<body>

    <?php
    $nomeCliente = $_SESSION['usuario']['nome']; 
    echo "<h1>Olá, $nomeCliente!</h1>"; ?>
    <table border="1">
        <thead>
            <tr>
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
                    <td><?php echo htmlspecialchars($produto['nomeproduto']); ?></td>
                    <td><?php echo number_format($produto['precoproduto'], 2, ',', '.'); ?></td>
                    <td><?php echo $produto['quantidade']; ?></td>
                    <td><?php echo number_format($subtotal, 2, ',', '.'); ?></td>
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
</body>
</html>
