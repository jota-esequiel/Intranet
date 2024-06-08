<?php 
session_start();
include_once '../bdConnection.php';

$pdo = conectar();
if(isset($_GET['codcategoria'])) {
    $codcategoria = $_GET['codcategoria'];

    $sql = "SELECT * FROM tb_categorias WHERE codcategoria = :codcategoria";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codcategoria', $codcategoria);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_OBJ);
} else {
    echo "<script>alert('Código da categoria não foi passado por parâmetro na URL!')</script>";
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <h1>EDIÇÃO DE CATEGORIA</h1>
        <br>
        <label>Categoria</label>
        <br>
        <input type="text" name="nomecategoria" value="<?php echo isset($result) ? $result->nomecategoria : ''; ?>">
        <br>
        <button type="submit" name="btnAlterar">ALTERAR E SALVAR</button>
    </form>

<?php 
if(isset($_POST['btnAlterar'])) { 
    $categoryName = $_POST['nomecategoria'];

    if(empty($categoryName)) {
        echo "<script>alert('Necessário informar uma cidade e UF!')</script>";
    } else {
        $sqlUp = "UPDATE tb_categorias SET nomecategoria = :nomecategoria WHERE codcategoria = :codcategoria"; 

        $stmtUp = $pdo->prepare($sqlUp);

        $stmtUp->bindParam(':nomecategoria', $categoryName);
        $stmtUp->bindParam(':codcategoria', $codcategoria);

        if($stmtUp->execute()) {
            echo "<script>alert('Alterado com SUCESSO!')</script>";
            header('Location: ../View/consultCategory.php'); 
            exit();
        } else {
            echo "<script>alert('Erro ao alterar!')</script>";
        }
    }
}
?>
</body>
</html>
