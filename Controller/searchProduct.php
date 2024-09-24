<?php
session_start();
include_once '../bdConnection.php'; 
include '../Controller/standardFunctionsController.php'; 

$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($searchTerm)) {
    try {
        $pdo = conectar();
        
        $stmt = $pdo->prepare("SELECT 
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
                            WHERE prod.ativo = 'S' AND prod.nomeproduto LIKE :searchTerm");
        
        $stmt->execute(['searchTerm' => "%$searchTerm%"]);
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getMessage();
        $produtos = [];
    }
} else {
    $produtos = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../templates/CSS/homeClient.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Pesquisa</title>
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="../templates/CSS/productCatalog.css">
</head>
<body>
    <header>
        <a href="../View/userProfile.php"><i class="fas fa-circle-user"></i></a> 
        <a href="../View/shoppingCart.php"><i class="fas fa-cart-shopping"></i></a> 
        <a href="../View/homeClient.php"><i class="fa-solid fa-house"></i></a>
    </header>
    <h1>Resultados da Pesquisa para "<?php echo htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); ?>"</h1>
    <div class="produtos">
        <?php
        if (!empty($produtos)) {
            foreach ($produtos as $produto) {
                echo "<div class='produto'>";
                if (!empty($produto['img'])) {
                    $imgPath = "../imagens/Produtos/" . htmlspecialchars(basename($produto['img']), ENT_QUOTES, 'UTF-8');
                    echo "<img src='{$imgPath}' alt='Imagem do Produto' width='100' height='100'>";
                } else {
                    echo "<div class='circle'></div>";
                    echo "<div class='circle'></div>";
                }
                echo "<div class='info'>";
                echo "<h2>" . htmlspecialchars(ucfirst($produto['nomeproduto']), ENT_QUOTES, 'UTF-8') . "</h2>";
                echo "<p>Preço: " . formatarPrice($produto['precoproduto']) . "</p>";
                echo "<p>Categoria: " . htmlspecialchars($produto['nomecategoria'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<p>Cor: " . htmlspecialchars($produto['corProd'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<p>Tamanho: " . htmlspecialchars($produto['tamanhoProd'], ENT_QUOTES, 'UTF-8') . "</p>";

                if (isset($_SESSION['usuario']) && $_SESSION['usuario'] == true) {
                    echo "<form action='../Controller/addToShoppingCartController.php' method='post'>";
                    echo "<input type='hidden' name='codproduto' value='" . htmlspecialchars($produto['codproduto'], ENT_QUOTES, 'UTF-8') . "'>";
                    echo "<button type='submit'>Adicionar ao Carrinho</button>";
                    echo "</form>";
                }
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>Nenhum produto encontrado para o termo de pesquisa.</p>";
        }
        ?>
    </div>
</body>
</html>
