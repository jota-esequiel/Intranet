<?php
session_start();
include '../bdConnection.php';
include '../Controller/standardFunctionsController.php';

$strPdo = conectar();

$strQuery = "SELECT 
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
            WHERE prod.ativo = 'S' ";

$params = [];

if (!empty($_POST['nomeproduto'])) {
    $strQuery .= " AND prod.nomeproduto LIKE :nomeproduto";
    $params[':nomeproduto'] = '%' . $_POST['nomeproduto'] . '%';
}
if (!empty($_POST['precoproduto'])) {
    $strQuery .= " AND prod.precoproduto LIKE :precoproduto";
    $params[':precoproduto'] = '%' . $_POST['precoproduto'] . '%';
}
if (!empty($_POST['codcategoria'])) {
    $strQuery .= " AND prod.codcategoria = :codcategoria";
    $params[':codcategoria'] = $_POST['codcategoria'];
}
if (!empty($_POST['cor'])) {
    $strQuery .= " AND prod.cor = :cor";
    $params[':cor'] = $_POST['cor'];
}
if (!empty($_POST['tamanho'])) {
    $strQuery .= " AND prod.tamanho = :tamanho";
    $params[':tamanho'] = $_POST['tamanho'];
}

$stmt = $strPdo->prepare($strQuery);
$stmt->execute($params);
$strResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Produtos | TCC</title>
    <link rel="stylesheet" href="../templates/CSS/productCatalog.css">
</head>
<body>
    <?php 
        include_once '../Controller/subMenuController.php';
        include '../Controller/defaultFiltersController.php';
        echo getLink('main');
        
        $subMenu = [];

        $additionalContent = '
            <button class="nav-bar-item" onclick="toggleFilterForm(\'filterProductClientForm\')">Filtros</button>';

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

        if (function_exists('filterProductClient')) {
            $additionalContent .= filterProductClient();
        }

        $additionalContent .= '</div>';
        
        renderSubMenu($subMenu, $additionalContent);
    ?>

    <div class="produtos">
        <?php foreach ($strResult as $produto): ?>
            <div class="produto">
                <?php if (!empty($produto['img'])): ?>
                    <img src="../imagens/Produtos/<?php echo htmlspecialchars(basename($produto['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Imagem do Produto">
                <?php else: ?>
                    <div class="circle"></div>
                    <div class="circle"></div>
                <?php endif; ?>
                <div class="info">
                    <h2><?php echo htmlspecialchars(ucfirst($produto['nomeproduto']), ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p>Preço: <?php echo formatarPrice($produto['precoproduto']); ?></p>
                    <p>Categoria: <?php echo htmlspecialchars($produto['nomecategoria'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p>Cor: <?php echo htmlspecialchars($produto['corProd'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p>Tamanho: <?php echo htmlspecialchars($produto['tamanhoProd'], ENT_QUOTES, 'UTF-8'); ?></p>

                    <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario'] == true): ?>
                        <form action="../Controller/addToShoppingCartController.php" method="POST">
                            <input type="hidden" name="codproduto" value="<?php echo htmlspecialchars($produto['codproduto'], ENT_QUOTES, 'UTF-8'); ?>">
                            <button type="submit">Adicionar ao Carrinho</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
