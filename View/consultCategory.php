<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'A') {
    header('Location: ../View/logError.php'); //Criar uma página de erros
    exit();
}

include_once '../bdConnection.php';
include '../Controller/defaultFiltersController.php';

$pdo = conectar();
$sql = "SELECT *
            FROM tb_categorias 
            WHERE 1=1";

$params = [];
if (!empty($_POST['nomecategoria'])) {
    $sql .= " AND nomecategoria LIKE :nomecategoria";
    $params[':nomecategoria'] = '%' . $_POST['nomecategoria'] . '%';
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
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../templates/CSS/subMenu.css">
    <script src="../templates/JS/main.js"></script> 
    <title>Document</title>
</head>
<body>
    <?php 
    include_once '../Controller/subMenuController.php';
    
    $subMenu = [
        'Adicionar Categoria' => '../View/categoryRegistration.php'
    ];

    $additionalContent = '
    <div class="nav-bar-item">
        <button onclick = "toggleFilterForm(\'filterCategoryForm\')">Filtros</button>';

    if (function_exists('filterCategory')) {
        $additionalContent .= filterCategory();
    }

    $additionalContent .= '</div>';
    
    renderSubMenu($subMenu, $additionalContent);
    ?>

    <table border = "1px">
        <tr>
            <td>CATEGORIA</td>
            <td>AÇÕES</td>
        </tr>
        <?php foreach ($result as $r) { ?>
            <tr>
                <td><?= htmlspecialchars($r['nomecategoria']); ?></td>
                <td>
                    <a href="../View/editCategory.php?codcategoria=<?= $r['codcategoria']; ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <a href="../View/deleteCategory.php?codcategoria=<?= $r['codcategoria']; ?>" onclick = "return confirm('Tem certeza que deseja excluir a categoria? Isso também irá excluir todos os PRODUTOS associados a CATEGORIA!');  ">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                    <a href=""></a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
