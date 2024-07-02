<?php
include_once '../bdConnection.php';
include '../Controller/standardFunctionsController.php';

try {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT codcategoria, nomecategoria FROM tb_categorias");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
    $categories = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['nomeproduto'], $_POST['precoproduto'], $_POST['qtdprod'], $_POST['codcategoria'])) {
        
        $nomeproduto = $_POST['nomeproduto'];
        $precoproduto = $_POST['precoproduto'];
        $qtdprod = $_POST['qtdprod'];
        $codcategoria = $_POST['codcategoria'];
        
        if (floatval($precoproduto) > 999.99) {
            showtoast("O limite de preço é R$ 999,99.", "warning", "glyphicon glyphicon-alert");
            exit;
        }
        
        try {
            $pdo = conectar();
            $stmt = $pdo->prepare("INSERT INTO tb_produtos (nomeproduto, precoproduto, qtdprod, codcategoria) VALUES (:nomeproduto, :precoproduto, :qtdprod, :codcategoria)");
            $stmt->bindParam(':nomeproduto', $nomeproduto);
            $stmt->bindParam(':precoproduto', $precoproduto);
            $stmt->bindParam(':qtdprod', $qtdprod);
            $stmt->bindParam(':codcategoria', $codcategoria);
            $stmt->execute();

            $codproduto = $pdo->lastInsertId();

            if(isset($_FILES['imagem_produto'])){
                $total = count($_FILES['imagem_produto']['name']);
                for( $i=0 ; $i < $total ; $i++ ) {
                    $target_dir = "C:/xampp/htdocs/Intranet/img/";
                    $target_file = $target_dir . basename($_FILES["imagem_produto"]["name"][$i]);

                    $imgContent = file_get_contents($_FILES["imagem_produto"]["tmp_name"][$i]);

                    try {
                        $stmt = $pdo->prepare("INSERT INTO tb_imagens (codproduto, img) VALUES (:codproduto, :img)");
                        $stmt->bindParam(':codproduto', $codproduto);
                        $stmt->bindParam(':img', $imgContent, PDO::PARAM_LOB);
                        $stmt->execute();
                    } catch (PDOException $e) {
                        echo 'Erro ao inserir imagem: ' . $e->getMessage();
                    }
                }
            }

            echo "Produto cadastrado com sucesso!";
        } catch (PDOException $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    } else {
        echo "Por favor, preencha todos os campos do formulário.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos | TCC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="../templates/JS/mask.js"></script> 
</head>
<body>

<h1>CADASTRO DE PRODUTOS</h1>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <label for="">Nome</label>
    <input type="text" name="nomeproduto" placeholder="Informe o nome do produto...">
    <br><br>
    <label for="">Preço</label>
    <input type="text" name="precoproduto" id="precoProduto" oninput="formatarMoeda(this)" placeholder="R$:">
    <br><br>
    <label for="">Quantidade</label>
    <input type="text" name="qtdprod" placeholder="Informe a quantidade do produto...">
    <br><br>
    <label for="">Categoria</label>
    <select name="codcategoria" id="codcategoria">
        <option value="">Selecione uma categoria</option>
        <?php 
        foreach($categories as $categoria) {
            echo "<option value='{$categoria['codcategoria']}'>{$categoria['nomecategoria']}</option>";
        }
        ?>
    </select>
    <br><br>
    <label for="">Imagens do Produto</label>
    <input type="file" name="imagem_produto[]" accept="image/*" multiple>
    <br><br>
    <button type="submit">Cadastrar Produto</button>
</form>

</body>
</html>

