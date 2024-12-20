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
    <link rel="stylesheet "type="text/css" href="../templates/CSS/userRegistrationAdmin.css">
    <script src="../templates/JS/mask.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <title>Cadastro de Administradores</title>
</head>
<body>
<div class="bebas-neue-regular">
 <div class="container">
    <div class="cadatrese">
    <form method="post" action="../Controller/userRegistrationAdminController.php">
        <h1>CADASTRE-SE</h1>
        </div>

<div class="playfair-display-uniquifier">
<div class="facalogin">
<h1>Ou faça <a href="../View/loginUser.php">login</a></h1>
</div>
</div>

<div class="input">
<label class="form-label">Nome:</label>
        <input type="text" name="nome" placeholder="Informe o seu nome..." required>
        <br><br>
        <label class="form-label" for="email">E-mail:</label>
        <input id="email" type="email" name="email" placeholder="Informe o seu e-mail..." required>
        <br><br>
        <label class="form-label" for="dtnasc">Data de nascimento:</label>
        <input id="dtnasc" class="date" type="text" name="dtnasc" placeholder="Informe a sua data de nascimento..." required oninput="mascaraData(this)">
        <br><br>
        <label class="form-label" for="fone">Contato:</label>
        <input id="fone" class="phone_with_ddd" type="text" name="fone" placeholder="Informe o seu número de telefone..." required oninput="mascaraTelefone(this)"> 
        <br><br>
        <label class="form-label" for="cpf">CPF:</label>
        <input id="cpf" class="cpf" type="text" name="cpf" placeholder="Informe o seu CPF" required oninput="mascaraCPF(this)">
        <br><br>
        <label class="form-label" for="senha">Senha:</label>
        <input id="senha" type="password" name="senha" placeholder="Informe a sua senha..." maxlength="32" required>
        <br><br>
        <label class="form-label" for="rua">Rua:</label>
        <input id="rua" type="text" name="rua" placeholder="Informe o nome da sua rua...">
        <br><br>
        <label class="form-label" for="complemento">Complemento:</label>
        <input id="complemento" type="text" name="complemento" placeholder="Informe o complemento...">
        <br><br>
        <label class="form-label" for="ncasa">Número da Casa:</label>
        <input id="ncasa" type="text" name="ncasa" placeholder="Informe o número da casa..." required>
        <br><br>
        <label class="form-label" for="cep">CEP:</label>
        <input id="cep" type="text" name="cep" placeholder="Informe o CEP..." required oninput="mascaraCEP(this)">
        <br><br>
        <label for="cidade">Cidade</label>
        <select id="cidade" name="codcid" required>
            <option value="">Selecione a cidade</option>
            </div>
</div>
            <?php
            foreach ($cidades as $cidade) {
                echo "<option value=\"{$cidade['codcid']}\">{$cidade['nomecidade']} - {$cidade['uf']}</option>";
            }
            ?>
            </div>
        </select>
        <br><br>
        <input type="hidden" name="tipo" value="A"> 
        <button type="submit" class="buttoncad" name="btnSalvar">CADASTRE-SE</button>
    </form>
    <script src = "../templates/JS/mask.js"></script>
    </div>
    <div class="img">   
<?php
        session_start();
        include_once '../bdConnection.php';
        include '../Controller/standardFunctionsController.php';

        echo getImgPath('mais', 2000, 1900, null);
?>
</div>
</body>
</html>
