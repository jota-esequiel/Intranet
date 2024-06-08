<?php 
session_start();
include_once '../bdConnection.php';

$pdo = conectar();
if(isset($_GET['codcid'])) {
    $codcid = $_GET['codcid'];

    $sql = "SELECT * FROM tb_cidades WHERE codcid = :codcid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codcid', $codcid);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_OBJ);
} else {
    echo "<script>alert('Código da cidade não foi passado por parâmetro na URL!')</script>";
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
        <h1>EDIÇÃO DE CIDADE</h1>
        <br>
        <label>Cidade</label>
        <br>
        <input type="text" name="nomecidade" value="<?php echo isset($result) ? $result->nomecidade : ''; ?>">
        <br>
        <label>UF</label>
        <br>
        <select name="uf">
            <?php
            $ufs = array('PR');
            foreach ($ufs as $uf) {
                echo "<option value='$uf'" . (isset($result) && $result->uf == $uf ? ' selected' : '') . ">$uf</option>";
            }
            ?>
        </select>
        <br>
        <button type = "submit" name = "btnAlterar">ALTERAR E SALVAR</button>
    </form>

<?php 
if(isset($_POST['btnAlterar'])) { 
    $cityName = $_POST['nomecidade'];
    $uf = $_POST['uf'];

    if(empty($cityName || empty($uf))) {
        echo "<script>alert('Necessário informar uma cidade e UF!')</script>";
    } else {
        $sqlUp = "UPDATE tb_cidades SET nomecidade = :nomecidade, uf = :uf WHERE codcid = :codcid"; 

        $stmtUp = $pdo->prepare($sqlUp);

        $stmtUp->bindParam(':nomecidade', $cityName);
        $stmtUp->bindParam(':uf', $uf);
        $stmtUp->bindParam(':codcid', $codcid);

        if($stmtUp->execute()) {
            echo "<script>alert('Alterado com SUCESSO!')</script>";
            header('Location: ../View/consultCity.php'); 
            exit();
        } else {
            echo "<script>alert('Erro ao alterar!')</script>";
        }
    }
}
?>
</body>
</html>
