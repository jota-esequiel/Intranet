<?php
// session_start();
// if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'A') {
//     header('Location: logError.php'); //Criar uma página de erros
//     exit();
// }

include_once '../bdConnection.php';
include '../Controller/defaultFiltersController.php';
include '../Controller/standardFunctionsController.php';

try {
    $pdo = conectar();

    $sql = "SELECT prod.codproduto,
                   prod.nomeproduto,
                   prod.precoproduto,
                   prod.qtdprod,
                   prod.ativo AS ativoproduct,
                   cat.nomecategoria,
                   img.img
            FROM tb_produtos prod
            INNER JOIN tb_categorias cat 
                ON prod.codcategoria = cat.codcategoria
            LEFT JOIN tb_imagens img 
                ON prod.codproduto = img.codproduto
            WHERE 1=1";

    $params = [];
    if (!empty($_POST['nomeproduto'])) {
        $sql .= " AND prod.nomeproduto LIKE :nomeproduto";
        $params[':nomeproduto'] = '%' . $_POST['nomeproduto'] . '%';
    }
    if (!empty($_POST['precoproduto'])) {
        $sql .= " AND prod.precoproduto LIKE :precoproduto";
        $params[':precoproduto'] = '%' . $_POST['precoproduto'] . '%';
    }
    if (!empty($_POST['qtdprod'])) {
        $sql .= " AND prod.qtdprod LIKE :qtdprod";
        $params[':qtdprod'] = '%' . $_POST['qtdprod'] . '%';
    }
    if (!empty($_POST['codcategoria'])) {
        $sql .= " AND prod.codcategoria LIKE :codcategoria";
        $params[':codcategoria'] = '%' . $_POST['codcategoria'] . '%';
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $productsImages = [];
    foreach ($products as $product) {
        $productCod = $product['codproduto'];
        if (!isset($productsImages[$productCod])) {
            $productsImages[$productCod] = $product;
            $productsImages[$productCod]['images'] = [];
        }
        if (!empty($product['img'])) {
            $productsImages[$productCod]['images'][] = $product['img'];
        }
    }
} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
    $productsImages = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../templates/CSS/consultProduct.css">
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <script src="../templates/JS/main.js"></script>
    <title>Consulta de Produtos | TCC</title>
</head>
<body>
    <?php 
    include_once '../Controller/subMenuController.php';
    
    $subMenu = [
        'Adicionar Produtos' => '../View/productRegistration.php'
    ];

    $additionalContent = '
        <button class="nav-bar-item" onclick="toggleFilterForm(\'filterProductForm\')">Filtros</button>
        <form id="exportForm" action="../Composer/xlsxProductComposer.php" method="get" style="display: none;">';

    if (!empty($_POST['nomeproduto'])) {
        $additionalContent .= '<input type = "hidden" name = "nomeproduto" value = "' . htmlspecialchars($_POST['nomeproduto']) . '">';
    }
    if (!empty($_POST['precoproduto'])) {
        $additionalContent .= '<input type = "hidden" name = "precoproduto" value = "' . htmlspecialchars($_POST['precoproduto']) . '">';
    }
    if (!empty($_POST['qtdprod'])) {
        $additionalContent .= '<input type = "hidden" name = "qtdprod" value = "' . htmlspecialchars($_POST['qtdprod']) . '">';
    }
    if (!empty($_POST['nomecategoria'])) {
        $additionalContent .= '<input type = "hidden" name = "nomecategoria" value = "' . htmlspecialchars($_POST['nomecategoria']) . '">';
    }
    if (!empty($_POST['ativoproduct'])) {
        $additionalContent .= '<input type = "hidden" name = "ativoproduct" value = "' . htmlspecialchars($_POST['ativoproduct']) . '">';
    }

    $additionalContent .= '</form>
        <button class = "nav-bar-item" onclick = "document.getElementById(\'exportForm\').submit();">Exportar XLSX</button>';

    if (function_exists('filterProduct')) {
        $additionalContent .= filterProduct();
    }

    $additionalContent .= '</div>';
    
    renderSubMenu($subMenu, $additionalContent);
    ?>    

    <h1>CONSULTA DE PRODUTOS</h1>

    <?php if (!empty($productsImages)): ?>
    <table border="1px">
        <tr>
            <th>Imagem</th>
            <th>Nome do Produto</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Categoria</th>
            <th>Status</th> 
            <th>Ações</th>
        </tr>
        <?php foreach($productsImages as $product): ?>
        <tr>
            <td>
                <?php if(!empty($product['images'])): ?>
                    <?php foreach($product['images'] as $image): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($image); ?>" alt="Imagem do Produto">
                    <?php endforeach; ?>
                <?php else: ?>
                    <em>Nenhuma imagem disponível</em>
                <?php endif; ?>
            </td>
            <td><?php echo $product['nomeproduto']; ?></td>
            <td><?php echo formatPrice($product['precoproduto']); ?></td>
            <td><?php echo $product['qtdprod']; ?></td>
            <td><?php echo $product['nomecategoria']; ?></td>
            <td>
                <?php if ($product['ativoproduct'] == 'S'): ?>
                    <span style="color: green;">Ativo</span>
                <?php else: ?>
                    <span style="color: red;">Inativo</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($product['ativoproduct'] == 'S'): ?>
                    <a href="../View/editProduct.php?codproduto=<?= $product['codproduto']; ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                <?php else: ?>
                    <i class="fa-solid fa-pen-to-square" style="color: grey;" disabled></i>
                <?php endif; ?>
                <?php if ($product['ativoproduct'] == 'S'): ?>
                    <a href="../Controller/inactivateProductController.php?codproduto=<?= $product['codproduto']; ?>" onclick="return confirm('Tem certeza que deseja INATIVAR este produto?')">
                        <i class="fa-regular fa-circle-xmark" style="color: red;"></i>
                    </a>
                <?php else: ?>
                    <a href="../Controller/activateProductController.php?codproduto=<?= $product['codproduto']; ?>" onclick="return confirm('Tem certeza que deseja ATIVAR este produto?')">
                        <i class="fa-regular fa-circle-check" style="color: green;"></i>                    
                    </a>
                <?php endif; ?>
                <a href="../View/deleteProduct.php?codproduto=<?= $product['codproduto']; ?>" onclick="return confirm('Tem certeza que deseja excluir o produto? Isso também irá excluir todos os registros associados ao produto!');">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p>Nenhum produto encontrado.</p>
    <?php endif; ?>
</body>
</html>