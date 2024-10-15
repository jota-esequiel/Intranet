<?php
session_start();

include_once '../bdConnection.php';
include '../Controller/defaultFiltersController.php';
include '../Controller/standardFunctionsController.php';

if (checkUserType('A')) {
    $pdo = conectar();

    $sql = "SELECT *
            FROM tb_cidades 
            WHERE 1=1";

    $params = [];
    if (!empty($_POST['nomecidade'])) {
        $sql .= " AND nomecidade LIKE :nomecidade";
        $params[':nomecidade'] = '%' . $_POST['nomecidade'] . '%';
    }
    if (!empty($_POST['uf'])) {
        $sql .= " AND uf LIKE :uf";
        $params[':uf'] = '%' . $_POST['uf'] . '%';
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params); 
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../templates/CSS/consultCity.css">
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <title>Consulta de Cidades</title>
</head>
<body>
    <a href="../View/cityRegistration.php">Adicione uma nova Cidade</a>
    <table border="1">
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
                    <a href="../View/deletecity.php?codcid=<?= $r['codcid']; ?>" 
                       onclick="return confirm('Tem certeza que deseja excluir esta cidade? Isso também irá excluir todos os registros associados a ela!');">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>

<?php 
} else {
    destroySession('../View/loginUser.php');
}
?>
