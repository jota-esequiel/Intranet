<?php
include_once '../bdConnection.php';

try {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT codcid, nomecidade, uf FROM tb_cidades");
    $stmt->execute();
    $cidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
    $cidades = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Administradores</title>
</head>
<body>
    <form method="post" action="../Controller/userRegistrationAdminController.php">
        <h1>CADASTRE-SE</h1>
        <label>Nome</label>
        <input type="text" name="nome" placeholder="Informe o seu nome..." required>
        <br><br>
        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" placeholder="Informe o seu e-mail..." required>
        <br><br>
        <label for="dtnasc">Data de nascimento</label>
        <input id="dtnasc" class="date" type="text" name="dtnasc" placeholder="Informe a sua data de nascimento..." required>
        <br><br>
        <label for="fone">Contato</label>
        <input id="fone" class="phone_with_ddd" type="text" name="fone" placeholder="Informe o seu número de telefone..." required>
        <br><br>
        <label for="cpf">CPF</label>
        <input id="cpf" class="cpf" type="text" name="cpf" placeholder="Informe o seu CPF" required>
        <br><br>
        <label for="senha">Senha</label>
        <input id="senha" type="password" name="senha" placeholder="Informe a sua senha..." maxlength="32" required>
        <br><br>
        <label for="rua">Rua</label>
        <input id="rua" type="text" name="rua" placeholder="Informe o nome da sua rua...">
        <br><br>
        <label for="complemento">Complemento</label>
        <input id="complemento" type="text" name="complemento" placeholder="Informe o complemento...">
        <br><br>
        <label for="ncasa">Número da Casa</label>
        <input id="ncasa" type="text" name="ncasa" placeholder="Informe o número da casa..." required>
        <br><br>
        <label for="cep">CEP</label>
        <input id="cep" type="text" name="cep" placeholder="Informe o CEP..." required>
        <br><br>
        <label for="cidade">Cidade</label>
        <select id="cidade" name="codcid" required>
            <option value="">Selecione a cidade</option>
            <?php
            foreach ($cidades as $cidade) {
                echo "<option value=\"{$cidade['codcid']}\">{$cidade['nomecidade']} - {$cidade['uf']}</option>";
            }
            ?>
        </select>
        <br><br>
        <input type="hidden" name="tipo" value="A"> 
        <button type="submit" class="buttoncad" name="btnSalvar">CADASTRE-SE</button>
    </form>
</body>
</html>
