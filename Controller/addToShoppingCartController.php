<?php
session_start();
include '../bdConnection.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../View/loginUser.php");
    exit();
}

if (isset($_POST['codproduto'])) {
    $codProduto = $_POST['codproduto'];
    $codCliente = $_SESSION['usuario']['codcliente'];

    $strPdo = conectar();

    $strQuery = "SELECT codproduto, 
                        nomeproduto, 
                        precoproduto, 
                        ativo 
                        FROM tb_produtos 
                        WHERE codproduto = :codproduto 
                        AND ativo = 'S'";
    $stmt = $strPdo->prepare($strQuery);
    $stmt->execute([':codproduto' => $codProduto]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto) {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        if (!isset($_SESSION['carrinho'][$codProduto])) {
            $_SESSION['carrinho'][$codProduto] = [
                'nomeproduto' => $produto['nomeproduto'],
                'precoproduto' => $produto['precoproduto'],
                'quantidade' => 1
            ];
        } else {
            $_SESSION['carrinho'][$codProduto]['quantidade']++;
        }

        header("Location: ../View/shoppingCart.php");
        exit();
    } else {
        echo "Produto não encontrado ou inativo.";
    }
} else {
    echo "<script>alert('Aconteceu algum problema técnico! Em instantes nossa equipe de suporte irá estabilizar a situação!')</script>";
}
?>
