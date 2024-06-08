<?php
session_start();
include '../Controller/standardFunctionsController.php';

if(isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'C' && isset($_SESSION['ativo']) && $_SESSION['ativo'] === 'S') {
    if(isset($_POST['codproduto'])) {
        $codproduto = $_POST['codproduto'];

        if(isset($_SESSION['carrinho'])) {
            if(isset($_SESSION['carrinho'][$codproduto])) {
                $_SESSION['carrinho'][$codproduto]++;
            } else {
                $_SESSION['carrinho'][$codproduto] = 1;
            }
        } else {
            $_SESSION['carrinho'] = array($codproduto => 1);
        }

        if (confirm("Produto adicionado ao carrinho. Continuar comprando?")) {
            header('Location: productCatalog.php'); 
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
} 
// else {
//     header('Location: login.php');
//     exit();
// }
?>
