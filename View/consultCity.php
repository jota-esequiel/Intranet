<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'A') {
    header('Location: logError.php'); //Criar uma página de erros
    exit();
}

include_once '../bdConnection.php';

$pdo = conectar();
$sql = "SELECT * FROM tb_cidades";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <table border = "1px">
        <tr>
            <td>CIDADE</td>
            <td>UF</td>
            <td>AÇÕES</td>
        </tr>
        <?php foreach ($result as $r) { ?>
            <tr>
                <td><?= htmlspecialchars($r['nomecidade']); ?></td>
                <td><?= htmlspecialchars($r['uf']); ?></td>
                <td>
                    <a href="../View/editCity.php?codcid=<?= $r['codcid']; ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <a href="../View/deleteCity.php?codcid=<?= $r['codcid']; ?>" onclick = "return confirm('Tem certeza que deseja excluir a cidade? Isso também irá excluir todos os USUÁRIOS associados a CIDADE!');  ">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>