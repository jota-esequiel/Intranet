<?php
// session_start();
// if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'A') {
//     header('Location: logError.php'); // Criar uma página de erros
//     exit();
// }

include_once '../bdConnection.php';
include '../Controller/defaultFiltersController.php';
include '../Controller/standardFunctionsController.php';

try {
    $pdo = conectar();

    $sql = "SELECT 
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
            WHERE 1=1
";

    $params = [];
    if (!empty($_POST['nomeproduto'])) {
        $sql .= " AND prod.nomeproduto LIKE :nomeproduto";
        $params[':nomeproduto'] = '%' . $_POST['nomeproduto'] . '%';
    }
    if (!empty($_POST['precoproduto'])) {
        $sql .= " AND prod.precoproduto LIKE :precoproduto";
        $params[':precoproduto'] = '%' . $_POST['precoproduto'] . '%';
    }
    if (!empty($_POST['codcategoria'])) {
        $sql .= " AND prod.codcategoria = :codcategoria";
        $params[':codcategoria'] = $_POST['codcategoria'];
    }
    if (!empty($_POST['cor'])) {
        $sql .= " AND prod.cor = :cor";
        $params[':cor'] = $_POST['cor'];
    }
    if (!empty($_POST['tamanho'])) {
        $sql .= " AND prod.tamanho = :tamanho";
        $params[':tamanho'] = $_POST['tamanho'];
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
<html lang="pt-BR">
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
        $additionalContent .= '<input type="hidden" name="nomeproduto" value="' . htmlspecialchars($_POST['nomeproduto'], ENT_QUOTES, 'UTF-8') . '">';
    }
    if (!empty($_POST['precoproduto'])) {
        $additionalContent .= '<input type="hidden" name="precoproduto" value="' . htmlspecialchars($_POST['precoproduto'], ENT_QUOTES, 'UTF-8') . '">';
    }
    if (!empty($_POST['codcategoria'])) {
        $additionalContent .= '<input type="hidden" name="codcategoria" value="' . htmlspecialchars($_POST['codcategoria'], ENT_QUOTES, 'UTF-8') . '">';
    }
    if (!empty($_POST['cor'])) {
        $additionalContent .= '<input type="hidden" name="cor" value="' . htmlspecialchars($_POST['cor'], ENT_QUOTES, 'UTF-8') . '">';
    }
    if (!empty($_POST['tamanho'])) {
        $additionalContent .= '<input type="hidden" name="tamanho" value="' . htmlspecialchars($_POST['tamanho'], ENT_QUOTES, 'UTF-8') . '">';
    }

    $additionalContent .= '</form>
        <button class="nav-bar-item" onclick="document.getElementById(\'exportForm\').submit();">Exportar XLSX</button>';

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
            <th>Imagens</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Categoria</th>
            <th>Cor</th>
            <th>Tamanho</th>
            <th>Status</th> 
            <th>Ações</th>
        </tr>
        <?php foreach($productsImages as $product): ?>
        <tr>
            <td>
                <?php if(!empty($product['images'])): ?>
                    <?php foreach($product['images'] as $image): ?>
                        <img src="../imagens/Produtos/<?php echo htmlspecialchars(basename($image), ENT_QUOTES, 'UTF-8'); ?>" alt="Imagem do Produto" style="width: 100px; height: auto;">
                    <?php endforeach; ?>
                <?php else: ?>
                    <em>Nenhuma imagem disponível</em>
                <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($product['nomeproduto'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo formatarPrice($product['precoproduto']); ?></td>
            <td><?php echo htmlspecialchars($product['nomecategoria'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($product['corProd'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($product['tamanhoProd'], ENT_QUOTES, 'UTF-8'); ?></td>
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
