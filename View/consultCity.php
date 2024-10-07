<?php
session_start();

include_once '../bdConnection.php';
include '../Controller/defaultFiltersController.php';
include '../Controller/standardFunctionsController.php';

if(checkUserType('A')) {
    $pdo = conectar();

$sql = "SELECT *
            FROM tb_cidades 
            WHERE 1=1";

$params = [];
if (!empty($_POST['nomeidade'])) {
    $sql .= " AND nomecidade LIKE :nomecidade";
    $params[':nomecidade'] = '%' . $_POST['nomecidade'] . '%';
} 
if (!empty($_POST['uf'])) {
    $sql .= " AND uf LIKE :uf ";
    $params[':uf'] = '%' . $_POST['uf'] . '%';
}
if (!empty($_POST['ativo'])) {
    $sql .= " AND ativo LIKE :ativo ";
    $params[':ativo'] = '%' . $_POST['ativo'] . '%';
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
    <link rel="stylesheet "type="text/css" href="../templates/CSS/consultCity.css">
    <a href="../View/cityRegistration.php">Adicione uma nova Cidade</a>
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <table border = "1px">
        <tr>
            <td>CIDADE</td>
            <td>UF</td>
            <td>STATUS</td>
            <td>AÇÕES</td>
        </tr>
        <?php foreach ($result as $r) { ?>
            <tr>
                <td><?= htmlspecialchars($r['nomecidade']); ?></td>
                <td><?= htmlspecialchars($r['uf']); ?></td>
                <td><?= htmlspecialchars($r['ativo']); ?></td>
                <td>
             <a href="../View/editCity.php?codcid=<?= $r['codcid']; ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
        <?php if ($r['ativo'] == 'S'): ?>
            <a href="../Controller/inactivateCityController.php?codcid=<?= $r['codcid']; ?>" onclick="return confirm('Tem certeza que deseja INATIVAR esta cidade?')">
            <i class="fa-solid fa-user-xmark" style="color: red;"></i> 
            </a>
        <?php else: ?>
            <a href="../Controller/activateCityController.php?codcid=<?= $r['codcid']; ?>" onclick="return confirm('Tem certeza que deseja ATIVAR esta cidade?')">
            <i class="fa-solid fa-user-check" style="color: green;"></i> 
            </a>
        <?php endif; ?>
        <a href="../View/deletecity.php?codcid=<?= $r['codcid']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta ciade? Isso também irá excluir todos os registros associados a ela!');">
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