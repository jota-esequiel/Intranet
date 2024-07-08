<?php
session_start();

include_once '../bdConnection.php';
include '../Controller/standardFunctionsController.php';

if (checkUserType('A')) {
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
        <?php displayHelp('Essa rotina do sistema não terá ações! Isso porque apenas a cidade de Cascavel / PR pode ser cadastrada, sem a necessidade de efetuar alterações ou exclusão!', 'alerta') ?>
        <table border = "1px">
            <tr>
                <td>CIDADE</td>
                <td>UF</td>
            </tr>
            <?php foreach ($result as $r) { ?>
                <tr>
                    <td><?= htmlspecialchars($r['nomecidade']); ?></td>
                    <td><?= htmlspecialchars($r['uf']); ?></td>
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