<?php
session_start();
include '../bdConnection.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../View/loginUser.php");
    exit();
}

if (isset($_GET['action']) && isset($_GET['codproduto'])) {
    $action = $_GET['action'];
    $codProduto = $_GET['codproduto'];

    if (isset($_SESSION['carrinho'][$codProduto])) {
        switch ($action) {
            case 'add':
                $_SESSION['carrinho'][$codProduto]['quantidade']++;
                break;

            case 'remove':
                if ($_SESSION['carrinho'][$codProduto]['quantidade'] > 1) {
                    $_SESSION['carrinho'][$codProduto]['quantidade']--;
                } else {
                    unset($_SESSION['carrinho'][$codProduto]);
                }
                break;

            case 'delete':
                unset($_SESSION['carrinho'][$codProduto]);
                break;

            default:
                echo "Ocorreu algum problema! Relatar ao suporte " . '<a href="mailto:gabrielli.dotto@escola.pr.gov.br">' . getLink('email') . '</a>';
                exit();
        }
    }

    header("Location: ../View/shoppingCart.php");
    exit();
} else {
    echo "Ação ou código do produto não informados.";
    exit();
}
?>
