<?php
include_once '../bdConnection.php';
include '../Controller/standardFunctionsController.php';

if (checkUserType('A')) {
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
        if (isset($_POST['nomeproduto'], $_POST['precoproduto'], $_POST['codcategoria'], $_POST['cor'], $_POST['tamanho'])) {

            $nomeproduto  = htmlspecialchars($_POST['nomeproduto']);
            $precoproduto = $_POST['precoproduto'];
            $cor          = htmlspecialchars($_POST['cor']);
            $tamanho      = htmlspecialchars($_POST['tamanho']);
            $codcategoria = intval($_POST['codcategoria']);

            $precoproduto = str_replace(['R$', '.', ','], ['', '', '.'], $precoproduto);
            $precoproduto = floatval($precoproduto);

            if ($precoproduto > 999.99) {
                echo 'O limite de preço é R$ 999,99.';
                exit;
            }

            try {
                $pdo = conectar();
                $stmt = $pdo->prepare("INSERT INTO tb_produtos (nomeproduto, precoproduto, cor, tamanho, codcategoria) VALUES (:nomeproduto, :precoproduto, :cor, :tamanho, :codcategoria)");
                $stmt->bindParam(':nomeproduto', $nomeproduto);
                $stmt->bindParam(':precoproduto', $precoproduto);
                $stmt->bindParam(':cor', $cor);
                $stmt->bindParam(':tamanho', $tamanho);
                $stmt->bindParam(':codcategoria', $codcategoria);
                $stmt->execute();

                $codproduto = $pdo->lastInsertId();

                if (isset($_FILES['imagem_produto']) && !empty($_FILES['imagem_produto']['name'][0])) {
                    $total = count($_FILES['imagem_produto']['name']);
                    for ($i = 0; $i < $total; $i++) {
                        $fileName = basename($_FILES['imagem_produto']['name'][$i]);
                        $fileTmpName = $_FILES['imagem_produto']['tmp_name'][$i];
                        $uploadDir = '../imagens/Produtos/';
                        $filePath = $uploadDir . $fileName;

                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        if (move_uploaded_file($fileTmpName, $filePath)) {
                            $stmt = $pdo->prepare("INSERT INTO tb_imagens (img) VALUES (:img)");
                            $filePath = 'imagens/Produtos/' . $fileName;
                            $stmt->bindParam(':img', $filePath);
                            $stmt->execute();

                            $codimg = $pdo->lastInsertId();

                            $stmt = $pdo->prepare("UPDATE tb_produtos SET codimg = :codimg WHERE codproduto = :codproduto");
                            $stmt->bindParam(':codimg', $codimg);
                            $stmt->bindParam(':codproduto', $codproduto);
                            $stmt->execute();
                        } else {
                            echo 'Erro ao mover o arquivo para o diretório de uploads.';
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
    <script src="../templates/JS/mask.js"></script>
    <link rel="stylesheet" type="text/css" href="../templates/CSS/productRegistration.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="../fontawesome/css/all.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>CADASTRO DE PRODUTOS</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="nomeproduto">Nome</label>
        <input type="text" name="nomeproduto" id="nomeproduto" placeholder="Informe o nome do produto..." required>
        <br><br>
        <label for="precoproduto">Preço</label>
        <input type="text" name="precoproduto" id="precoproduto" oninput="formatarMoeda(this)" placeholder="R$:" required>
        <br><br>
        <label for="codcategoria">Categoria</label>
        <select name="codcategoria" id="codcategoria" required>
            <option value="">Selecione uma categoria</option>
            <?php 
            foreach ($categories as $categoria) {
                echo "<option value='{$categoria['codcategoria']}'>{$categoria['nomecategoria']}</option>";
            }
            ?>
        </select>
        <br><br>
        <label for="cor">Cor</label>
        <select id="cor" name="cor" required>
            <option value="">Selecione uma cor</option>
            <?php 
                $cores = [
                    '1' => ['nome' => 'Vermelho', 'emoji' => '🔴'],
                    '2' => ['nome' => 'Azul', 'emoji' => '🔵'],
                    '3' => ['nome' => 'Amarelo', 'emoji' => '🟡'],
                ];

                foreach($cores as $valor => $cor) {
                    echo "<option value='{$valor}'>{$cor['emoji']} {$cor['nome']}</option>";
                }
            ?>
        </select>
        <br><br>
        <label for="tamanho">Tamanho</label>
        <select id="tamanho" name="tamanho" required>
            <option value="">Selecione um tamanho</option>
            <?php 
                $tamanhos = [
                    'P' => 'Pequeno',
                    'M' => 'Médio',
                    'G' => 'Grande'
                ];
    
                foreach($tamanhos as $value => $size) {
                    echo "<option value='{$value}'>{$size}</option>";
                }
            ?>
        </select>
        <br><br>
        <label for="imagem_produto">Imagens do Produto</label>
        <input type="file" name="imagem_produto[]" id="imagem_produto" accept="image/*" multiple>
        <br><br>
        <button type="submit">Cadastrar Produto</button>
    </form>
</div>
</body>
</html>

<?php 
} else {
    destroySession('../View/loginUser.php');
}
?>
