<?php
//02.06.2024 - Teste de permissão à acesso a rotina administrativas concluída. Apenas melhorar o arquivo de log para capturar dados de acesso indevidos.

// session_start();
// if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'A') {
//     header('Location: logError.php'); //Criar uma página de erros
//     exit();
// }

include_once '../bdConnection.php';
include '../Controller/defaultFiltersController.php';
include '../Controller/standardFunctionsController.php';

$pdo = conectar();

$sql = "SELECT
            cli.*,
            cid.nomecidade
        FROM tb_clientes cli
        LEFT JOIN tb_cidades cid
            ON cli.codcid = cid.codcid
        WHERE 1=1";

$params = [];
if (!empty($_POST['nome'])) {
    $sql .= " AND cli.nome LIKE :nome";
    $params[':nome'] = '%' . $_POST['nome'] . '%';
} 
if (!empty($_POST['email'])) {
    $sql .= " AND cli.email LIKE :email";
    $params[':email'] = '%' . $_POST['email'] . '%';
} 
if (!empty($_POST['cpf'])) {
    $sql .= " AND cli.cpf LIKE :cpf";
    $params[':cpf'] = '%' . $_POST['cpf'] . '%';
}
if (!empty($_POST['fone'])) {
    $sql .= " AND cli.fone LIKE :fone";
    $params[':fone'] = '%' . $_POST['fone'] . '%';
}
if (!empty($_POST['dtnasc'])) {
    $sql .= " AND cli.dtnasc LIKE :dtnasc";
    $params[':dtnasc'] = '%' . $_POST['dtnasc'] . '%';
}
if (!empty($_POST['rua'])) {
    $sql .= " AND cli.rua LIKE :rua";
    $params[':rua'] = '%' . $_POST['rua'] . '%';
}
if (!empty($_POST['complemento'])) {
    $sql .= " AND cli.complemento LIKE :complemento";
    $params[':complemento'] = '%' . $_POST['complemento'] . '%';
}
if (!empty($_POST['ncasa'])) {
    $sql .= " AND cli.ncasa LIKE :ncasa";
    $params[':ncasa'] = '%' . $_POST['ncasa'] . '%';
}
if (!empty($_POST['cep'])) {
    $sql .= " AND cli.cep LIKE :cep";
    $params[':cep'] = '%' . $_POST['cep'] . '%';
}
if (!empty($_POST['ativo'])) {
    $sql .= " AND cli.ativo LIKE :ativo";
    $params[':ativo'] = '%' . $_POST['ativo'] . '%';
}
if (!empty($_POST['tipo'])) {
    $sql .= " AND cli.tipo LIKE :tipo";
    $params[':tipo'] = '%' . $_POST['tipo'] . '%';
}

if (!empty($_POST['cidade'])) {
    $sql .= " AND cli.codcid = :cidade";
    $params[':cidade'] = $_POST['cidade'];
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
        'Adicionar Administrador' => '../View/userRegistrationAdmin.php',
        'Apenas link teste'       => 'https://www.google.com/',
        'Exportar XLSX'           => '../Controller/xlsxFunctionComposer.php'
    ];

    $additionalContent = '
        <button class="nav-bar-item" onclick="toggleFilterForm(\'filterUserForm\')">Filtros</button>';

    if (function_exists('filterUser')) {
        $additionalContent .= filterUser();
    }

    $additionalContent .= '</div>';
    
    renderSubMenu($subMenu, $additionalContent);
    ?>

  <table border="1px">
  <tr>
    <td>Nome</td>
    <td>E-mail</td>
    <td>Data de nascimento</td>
    <td>Contato</td>
    <td>CPF</td>
    <td>Rua</td>
    <td>Complemento</td>
    <td>Nº Casa</td>
    <td>CEP</td>
    <td>Tipo</td>
    <td>Status</td>
    <td>Cidade</td>
    <td>Ações</td>
  </tr>

  <?php foreach ($result as $r) { ?>
  <tr>
    <td><?= htmlspecialchars($r['nome']); ?></td>
    <td><?= htmlspecialchars($r['email']); ?></td>
    <td><?= formatDateToBrazilian($r['dtnasc']); ?></td>
    <td><?= formatPhoneNumber($r['fone']); ?></td>
    <td><?= formatCPF($r['cpf']); ?></td>
    <td><?= htmlspecialchars($r['rua']); ?></td>
    <td><?= htmlspecialchars($r['complemento']); ?></td>
    <td><?= htmlspecialchars($r['ncasa']); ?></td>
    <td><?= formatCEP($r['cep']); ?></td>
    <td><?= htmlspecialchars($r['tipo'] == 'C' ? 'Cliente' : 'Administrador'); ?></td>
    <td style="color: <?= $r['ativo'] == 'S' ? 'green' : 'red'; ?>"><?= htmlspecialchars($r['ativo'] == 'S' ? 'Ativo' : 'Inativo'); ?></td> <!-- Colorindo o status -->
    <td><?= htmlspecialchars($r['nomecidade']); ?></td>
    <td>
      <?php if ($r['ativo'] == 'S'): ?>
        <a href="../View/editUser.php?codcliente=<?= $r['codcliente']; ?>">
          <i class="fa-solid fa-pen-to-square"></i>
        </a>
      <?php else: ?>
        <i class="fa-solid fa-pen-to-square" style="color: gray;" disabled></i>
      <?php endif; ?>
      <?php if ($r['ativo'] == 'S'): ?>
        <a href="../Controller/inactivateUserController.php?codcliente=<?= $r['codcliente']; ?>" onclick="return confirm('Tem certeza que deseja INATIVAR este usuário?')">
          <i class="fa-solid fa-user-xmark" style="color: red;"></i> 
        </a>
      <?php else: ?>
        <a href="../Controller/activateUserController.php?codcliente=<?= $r['codcliente']; ?>" onclick="return confirm('Tem certeza que deseja ATIVAR este usuário?')">
          <i class="fa-solid fa-user-check" style="color: green;"></i> 
        </a>
      <?php endif; ?>
      <a href="../View/deleteUser.php?codcliente=<?= $r['codcliente']; ?>" onclick="return confirm('Tem certeza que deseja excluir o USUÁRIO? Isso também irá excluir todos os registros associados ao USUÁRIO!');">
        <i class="fa-solid fa-trash"></i>
      </a>
    </td>
  </tr>
  <?php } ?>
</table>
</body>
</html>