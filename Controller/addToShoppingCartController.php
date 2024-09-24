<?php
session_start();
include '../bdConnection.php';

if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['usuario'])) {
    header("Location: ../View/loginUser.php");
    exit();
}

if (isset($_POST['codproduto'])) {
    $codProduto = $_POST['codproduto'];
    $codCliente = $_SESSION['usuario']['codcliente'];

    $strPdo = conectar();

    $strQuery = "SELECT 
                    codproduto,
                    nomeproduto, 
                    precoproduto,
                    ativo, 
                    img
                FROM tb_produtos tp
                LEFT JOIN tb_imagens ti 
                    ON ti.codimg = tp.codimg 
                WHERE tp.codproduto = :codproduto
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
                'nomeproduto'  => $produto['nomeproduto'],
                'precoproduto' => $produto['precoproduto'],
                'quantidade'   => 1,
                'img'          => $produto['img']
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
