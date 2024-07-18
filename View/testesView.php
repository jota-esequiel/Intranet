<?php
/**
 * Arquivo apenas de testes de funções padrão do sistema
 */



include '../Controller/standardFunctionsController.php';

$linkCSS = '../templates/CSS/showToast.css';
$linkJS = '../templates/JS/showToast.js'; 

echo montarCabecalhoHTML($linkCSS, $linkJS); 

$valor01 = 9;

if ($valor01 == 20) {
    echo '<script>showToast("success", "O valor ' . $valor01 . ' está correto!")</script>';
} else {
    echo '<script>showToast("error", "O valor ' . $valor01 . ' está incorreto!");</script>';
}
?>
</body>
</html>
