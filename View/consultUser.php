<?php

include_once '../bdConnection.php';
include '../Controller/defaultFiltersController.php';
include '../Controller/standardFunctionsController.php';

if (checkUserType('A')) {
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
        <link rel="stylesheet "type="text/css" href="../templates/CSS/consultUser.css">
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
    ];
    
        $additionalContent = '
            <button class="nav-bar-item" onclick="toggleFilterForm(\'filterUserForm\')">Filtros</button>
            <form id="exportForm" action="../Composer/xlsxUsersComposer.php" method="get" style="display: none;">';
        
        if (!empty($_POST['nome'])) {
            $additionalContent .= '<input type="hidden" name="nome" value="' . htmlspecialchars($_POST['nome']) . '">';
        }
        if (!empty($_POST['email'])) {
            $additionalContent .= '<input type="hidden" name="email" value="' . htmlspecialchars($_POST['email']) . '">';
        }
        if (!empty($_POST['cpf'])) {
            $additionalContent .= '<input type="hidden" name="cpf" value="' . htmlspecialchars($_POST['cpf']) . '">';
        }
        if (!empty($_POST['fone'])) {
            $additionalContent .= '<input type="hidden" name="fone" value="' . htmlspecialchars($_POST['fone']) . '">';
        }
        if (!empty($_POST['dtnasc'])) {
            $additionalContent .= '<input type="hidden" name="dtnasc" value="' . htmlspecialchars($_POST['dtnasc']) . '">';
        }
        if (!empty($_POST['rua'])) {
            $additionalContent .= '<input type="hidden" name="rua" value="' . htmlspecialchars($_POST['rua']) . '">';
        }
        if (!empty($_POST['complemento'])) {
            $additionalContent .= '<input type="hidden" name="complemento" value="' . htmlspecialchars($_POST['complemento']) . '">';
        }
        if (!empty($_POST['ncasa'])) {
            $additionalContent .= '<input type="hidden" name="ncasa" value="' . htmlspecialchars($_POST['ncasa']) . '">';
        }
        if (!empty($_POST['cep'])) {
            $additionalContent .= '<input type="hidden" name="cep" value="' . htmlspecialchars($_POST['cep']) . '">';
        }
        if (!empty($_POST['ativo'])) {
            $additionalContent .= '<input type="hidden" name="ativo" value="' . htmlspecialchars($_POST['ativo']) . '">';
        }
        if (!empty($_POST['tipo'])) {
            $additionalContent .= '<input type="hidden" name="tipo" value="' . htmlspecialchars($_POST['tipo']) . '">';
        }
        if (!empty($_POST['cidade'])) {
            $additionalContent .= '<input type="hidden" name="cidade" value="' . htmlspecialchars($_POST['cidade']) . '">';
        }
        
        if (function_exists('filterUser')) {
            $additionalContent .= filterUser();
        }
        
        $additionalContent .= '</div>';
        
        renderSubMenu($subMenu, $additionalContent);
        ?>

    <table border="1px">
    <tr>
        <th>Nome</th>
        <th>E-mail</th>
        <th>Data de nascimento</th>
        <th>Contato</th>
        <th>CPF</th>
        <th>Rua</th>
        <th>Complemento</th>
        <th>Nº Casa</th>
        <th>CEP</th>
        <th>Tipo</th>
        <th>Status</th>
        <th>Cidade</th>
        <th>Ações</th>
    </tr>

    <?php foreach ($result as $r) { ?>
    <tr>
        <td><?= ucfirst($r['nome']); ?></td>
        <td><?= htmlspecialchars($r['email']); ?></td>
        <td><?= formatDateToBrazilian($r['dtnasc']); ?></td>
        <td><?= formatPhoneNumber($r['fone']); ?></td>
        <td><?= formatCPF($r['cpf']); ?></td>
        <td><?= ucfirst($r['rua']); ?></td>
        <td><?= ucfirst($r['complemento']); ?></td>
        <td><?= htmlspecialchars($r['ncasa']); ?></td>
        <td><?= formatCEP($r['cep']); ?></td>
        <td><?= htmlspecialchars($r['tipo'] == 'C' ? 'Cliente' : 'Administrador'); ?></td>
        <td style="color: <?= $r['ativo'] == 'S' ? 'green' : 'red'; ?>"><?= htmlspecialchars($r['ativo'] == 'S' ? 'Ativo' : 'Inativo'); ?></td>
        <td><?= ucfirst($r['nomecidade']); ?></td>
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
    
<?php 
    } else {
        destroySession('../View/loginUser.php');
    }
?>