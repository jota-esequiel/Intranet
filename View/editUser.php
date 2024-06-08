<?php
session_start(); 
include_once '../bdConnection.php';

$pdo = conectar();

if(isset($_GET['codcliente'])) {
    $codcliente = $_GET['codcliente'];

    $sql = "SELECT * FROM tb_clientes WHERE codcliente = :codcliente";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codcliente', $codcliente);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "<script>alert('Código do cliente não foi passado como parâmetro na URL!')</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição de Usuário</title>
</head>
<body>
    <form method = "POST">
        <label for = "">EDIÇÃO DE USUÁRIOS</label>
        <br><br>
        <label for = "">Nome</label>
        <input type="text" name="nome" placeholder="Atualizar nome..." value="<?php echo isset($result) ? $result['nome'] : ''; ?>">
        <br><br>
        <label for = "">CPF</label>
        <input type="text" name="cpf" placeholder="Atualizar CPF..." value="<?php echo isset($result) ? $result['cpf'] : ''; ?>">
        <br><br>
        <label for = "">Contato</label>
        <input type="text" name="fone" placeholder="Atualizar telefone..." value="<?php echo isset($result) ? $result['fone'] : ''; ?>">
        <br><br>
        <label for = "">E-mail</label>
        <input type="text" name="email" placeholder="Atualizar email..." value="<?php echo isset($result) ? $result['email'] : ''; ?>">
        <br><br>
        <label for = "">Data de nascimento</label>
        <input type="date" name="dtnasc" value="<?php echo isset($result) ? $result['dtnasc'] : ''; ?>">
        <br><br>
        <label for = "">Rua</label>
        <input type="text" name="rua" placeholder="Atualizar rua..." value="<?php echo isset($result) ? $result['rua'] : ''; ?>">
        <br><br>
        <label for = "">Complemento</label>
        <input type="text" name="complemento" placeholder="Atualizar complemento..." value="<?php echo isset($result) ? $result['complemento'] : ''; ?>">
        <br><br>
        <label for = "">Nº Casa</label>
        <input type="text" name="ncasa" placeholder="Atualizar número da casa..." value="<?php echo isset($result) ? $result['ncasa'] : ''; ?>">
        <br><br>
        <label for = "">CEP</label>
        <input type="text" name="cep" placeholder="Atualizar CEP..." value="<?php echo isset($result) ? $result['cep'] : ''; ?>">
        <br><br>
        <button type="submit" name="btnAlterar">ALTERAR E SALVAR</button>
    </form>

<?php 
if(isset($_POST['btnAlterar'])) {
    $nome = $_POST['nome'];
    $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']);
    $fone = preg_replace('/[^0-9]/', '', $_POST['fone']);
    $email = $_POST['email'];
    $dtnasc = $_POST['dtnasc'];
    $rua = $_POST['rua'];
    $complemento = $_POST['complemento'];
    $ncasa = $_POST['ncasa'];
    $cep = preg_replace('/[^0-9]/', '', $_POST['cep']);

    $sqlUp = "UPDATE tb_clientes SET nome = :nome, cpf = :cpf, fone = :fone, email = :email, dtnasc = :dtnasc, rua = :rua, complemento = :complemento, ncasa = :ncasa, cep = :cep WHERE codcliente = :codcliente";
    $stmtUp = $pdo->prepare($sqlUp);

    $stmtUp->bindParam(':nome', $nome);
    $stmtUp->bindParam(':cpf', $cpf);
    $stmtUp->bindParam(':fone', $fone);
    $stmtUp->bindParam(':email', $email);
    $stmtUp->bindParam(':dtnasc', $dtnasc);
    $stmtUp->bindParam(':rua', $rua);
    $stmtUp->bindParam(':complemento', $complemento);
    $stmtUp->bindParam(':ncasa', $ncasa);
    $stmtUp->bindParam(':cep', $cep);
    $stmtUp->bindParam(':codcliente', $codcliente);

    if($stmtUp->execute()) {
        $_SESSION['mensagem'] = "Usuário atualizado com sucesso!'";
        header('Location: ../View/consultUser.php');
        exit();
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar o usuário";
    }
}
?>
</body>
</html>
