<?php
session_start(); 
include_once '../bdConnection.php';

$pdo = conectar();

$sqlCategorias = "SELECT * FROM tb_categorias";
$stmtCategorias = $pdo->query($sqlCategorias);
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

if(isset($_GET['codproduto'])) {
    $codproduto = $_GET['codproduto'];

    $sql = "SELECT * FROM tb_produtos WHERE codproduto = :codproduto";
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição de Produtos | TCC</title>
</head>
<body>
    <form method="POST">
        <label for="">EDIÇÃO DE PRODUTOS</label>
        <br><br>
        <label for="">Nome</label>
        <input type="text" name="nome" placeholder="Atualizar nome..." value="<?php echo isset($result) ? $result['nomeproduto'] : ''; ?>">
        <br><br>
        <label for="">Preço</label>
        <input type="text" name="preco" placeholder="Atualizar preço..." value="<?php echo isset($result) ? $result['precoproduto'] : ''; ?>">
        <br><br>
        <label for="">Quantidade</label>
        <input type="text" name="qtd" placeholder="Atualizar quantidade..." value="<?php echo isset($result) ? $result['qtdprod'] : ''; ?>">
        <br><br>
        <label for="">Categoria</label>
        <select name="categoria">
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?php echo $categoria['codcategoria']; ?>" <?php echo isset($result) && $result['codcategoria'] == $categoria['codcategoria'] ? 'selected' : ''; ?>><?php echo $categoria['nomecategoria']; ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <button type="submit" name="btnAlterar">ALTERAR E SALVAR</button>
    </form>

<?php 
if(isset($_POST['btnAlterar'])) {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $qtd = $_POST['qtd'];
    $categoria = $_POST['categoria'];

    $sqlUp = "UPDATE tb_produtos SET nomeproduto = :nome, precoproduto = :preco, qtdprod = :qtd, codcategoria = :categoria WHERE codproduto = :codproduto";
    $stmtUp = $pdo->prepare($sqlUp);

    $stmtUp->bindParam(':nome', $nome);
    $stmtUp->bindParam(':preco', $preco);
    $stmtUp->bindParam(':qtd', $qtd);
    $stmtUp->bindParam(':categoria', $categoria);
    $stmtUp->bindParam(':codproduto', $codproduto);

    if($stmtUp->execute()) {
        $_SESSION['mensagem'] = "Produto atualizado com sucesso!";
        header('Location: ../View/consultProduct.php');
        exit();
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar o produto";
    }
}
?>
</body>
</html>