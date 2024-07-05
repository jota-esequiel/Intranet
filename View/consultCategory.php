<?php
session_start();

include_once '../bdConnection.php';
include '../Controller/defaultFiltersController.php';
require_once '/xampp/htdocs/Intranet/emailComposer/emailFunctions.php'; 

$pdo = conectar();

$rotinaAcessada = 'consultCategory'; 

$message = checkUserStatusAndLogout($pdo, $rotinaAcessada);

if ($message !== null) {
    echo "<script>
        alert('$message');
        setTimeout(function() {
            window.location.href = '../View/loginUser.php';
        }, 0); 
    </script>";
    exit();
}

$sql = "SELECT *
            FROM tb_categorias 
            WHERE 1=1";

$params = [];
if (!empty($_POST['nomecategoria'])) {
    $sql .= " AND nomecategoria LIKE :nomecategoria";
    $params[':nomecategoria'] = '%' . $_POST['nomecategoria'] . '%';
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
            <td>STATUS</td>
            <td>AÇÕES</td>
        </tr>
        <?php foreach ($result as $r) { ?>
            <tr>
                <td><?= htmlspecialchars($r['nomecategoria']); ?></td>
                <td style="color: <?= $r['ativo'] == 'S' ? 'green' : 'red'; ?>"><?= htmlspecialchars($r['ativo'] == 'S' ? 'Ativo' : 'Inativo'); ?></td>
                <td>
                    <a href="../View/editCategory.php?codcategoria=<?= $r['codcategoria']; ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <a href="../View/deleteCategory.php?codcategoria=<?= $r['codcategoria']; ?>" onclick = "return confirm('Tem certeza que deseja excluir a categoria? Isso também irá excluir todos os PRODUTOS associados a CATEGORIA!');  ">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                    <?php if ($r['ativo'] == 'S'): ?>
                        <a href="../Controller/inactivateCategoryController.php?codcategoria=<?= $r['codcategoria']; ?>" onclick="return confirm('Tem certeza que deseja INATIVAR esta CATEGORIA?')">
                        <i class="fa-regular fa-circle-xmark" style="color: red;"></i> 
                        </a>
                    <?php else: ?>
                        <a href="../Controller/activateCategoryController.php?codcategoria=<?= $r['codcategoria']; ?>" onclick="return confirm('Tem certeza que deseja ATIVAR esta CATEGORIA?')">
                        <i class="fa-regular fa-circle-check" style="color: green;"></i> 
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
