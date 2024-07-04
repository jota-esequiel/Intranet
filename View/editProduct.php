<?php
session_start();
include_once '../bdConnection.php';

$pdo = conectar();

$sqlCategorias = "SELECT * FROM tb_categorias";
$stmtCategorias = $pdo->query($sqlCategorias);
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

$cores = [
    '1' => 'Vermelho',
    '2' => 'Azul',
    '3' => 'Amarelo'
];

$tamanhos = [
    'P' => 'Pequeno',
    'M' => 'Médio',
    'G' => 'Grande'
];

if (isset($_GET['codproduto'])) {
    $codproduto = $_GET['codproduto'];

    $sql = "SELECT prod.*,
            img.img AS imagem
            FROM tb_produtos prod
            LEFT JOIN tb_imagens img 
                ON prod.codimg = img.codimg
            WHERE prod.codproduto = :codproduto";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codproduto', $codproduto);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "<script>alert('Código do produto não foi passado como parâmetro na URL!')</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição de Produtos | TCC</title>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <label for="">EDIÇÃO DE PRODUTOS</label>
        <br><br>
        <label for="">Nome</label>
        <input type="text" name="nome" placeholder="Atualizar nome..." value="<?php echo isset($result) ? htmlspecialchars($result['nomeproduto'], ENT_QUOTES, 'UTF-8') : ''; ?>">
        <br><br>
        <label for="">Preço</label>
        <input type="text" name="preco" placeholder="Atualizar preço..." value="<?php echo isset($result) ? htmlspecialchars($result['precoproduto'], ENT_QUOTES, 'UTF-8') : ''; ?>">
        <br><br>
        <label for="">Categoria</label>
        <select name="categoria">
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?php echo $categoria['codcategoria']; ?>" <?php echo isset($result) && $result['codcategoria'] == $categoria['codcategoria'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($categoria['nomecategoria'], ENT_QUOTES, 'UTF-8'); ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="">Cor</label>
        <select name="cor">
            <?php foreach ($cores as $valor => $nome): ?>
                <option value="<?php echo $valor; ?>" <?php echo isset($result) && $result['cor'] == $valor ? 'selected' : ''; ?>><?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="">Tamanho</label>
        <select name="tamanho">
            <?php foreach ($tamanhos as $valor => $nome): ?>
                <option value="<?php echo $valor; ?>" <?php echo isset($result) && $result['tamanho'] == $valor ? 'selected' : ''; ?>><?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="">Imagem Atual</label>
        <?php if (isset($result['imagem']) && !empty($result['imagem'])): ?>
            <img src="../imagens/Produtos/<?php echo htmlspecialchars(basename($result['imagem']), ENT_QUOTES, 'UTF-8'); ?>" alt="Imagem do Produto" style="max-width: 200px; max-height: 200px;">
        <?php else: ?>
            <p>Nenhuma imagem cadastrada.</p>
        <?php endif; ?>
        <br><br>

        <label for="">Substituir Imagem</label>
        <input type="file" name="imagem">
        <br><br>
        <button type="submit" name="btnAlterar">ALTERAR E SALVAR</button>
    </form>

<?php 
if (isset($_POST['btnAlterar'])) {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];
    $cor = $_POST['cor'];
    $tamanho = $_POST['tamanho'];
    $imagem = $_FILES['imagem'];

    $sqlUp = "UPDATE tb_produtos SET nomeproduto = :nome, precoproduto = :preco, codcategoria = :categoria, cor = :cor, tamanho = :tamanho WHERE codproduto = :codproduto";
    $stmtUp = $pdo->prepare($sqlUp);

    $stmtUp->bindParam(':nome', $nome);
    $stmtUp->bindParam(':preco', $preco);
    $stmtUp->bindParam(':categoria', $categoria);
    $stmtUp->bindParam(':cor', $cor);
    $stmtUp->bindParam(':tamanho', $tamanho);
    $stmtUp->bindParam(':codproduto', $codproduto);

    if ($stmtUp->execute()) {
        if ($imagem['error'] == UPLOAD_ERR_OK) {
            $uploadDir = '../imagens/Produtos/';
            $uploadFile = $uploadDir . basename($imagem['name']);

            if (isset($result['imagem']) && !empty($result['imagem'])) {
                $oldImagePath = $uploadDir . basename($result['imagem']);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            if (move_uploaded_file($imagem['tmp_name'], $uploadFile)) {
                $sqlImgIns = "INSERT INTO tb_imagens (img) VALUES (:imagem)";
                $stmtImgIns = $pdo->prepare($sqlImgIns);
                $stmtImgIns->bindParam(':imagem', $uploadFile);
                $stmtImgIns->execute();

                $codimg = $pdo->lastInsertId();

                $sqlImgUpdate = "UPDATE tb_produtos SET codimg = :codimg WHERE codproduto = :codproduto";
                $stmtImgUpdate = $pdo->prepare($sqlImgUpdate);
                $stmtImgUpdate->bindParam(':codimg', $codimg);
                $stmtImgUpdate->bindParam(':codproduto', $codproduto);
                $stmtImgUpdate->execute();

                $_SESSION['mensagem'] = "Produto e imagem atualizados com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Produto atualizado, mas não foi possível fazer o upload da imagem.";
            }
        } else {
            $_SESSION['mensagem'] = "Produto atualizado com sucesso!";
        }

        header('Location: ../View/consultProduct.php');
        exit();
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar o produto";
    }
}
?>
</body>
</html>