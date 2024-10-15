<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet "type="text/css" href="../templates/CSS/cityRegistration.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cidades</title>
</head>

<body>
    <?php
    session_start();
    if (isset($_GET['mensagem'])) {
        echo "<p>{$_GET['mensagem']}</p>";
    }
    ?>
    <form method="post" action="../Controller/cityRegistrationController.php">
        <h1>CADASTRO DE CIDADES</h1>
        <br>

        <label for="nomecidade">Nome da Cidade:</label>
        <input type="text" name="nomecidade" id="nomecidade" required placeholder="Digite o nome da cidade">

        <br><br>

        <label for="uf">UF:</label>
        <select name="uf" id="uf" required>
            <option value="">Selecione a UF</option>
            <?php
            include_once '../bdConnection.php';
            $ufs = array("PR"); // Adicione mais UFs conforme necessário

            foreach ($ufs as $uf) {
                echo "<option value='$uf'>$uf</option>";
            }
            ?>
        </select>
        
        <br><br>
        <button type="submit" class="buttoncad" name="btnSalvar">CADASTRAR CIDADE</button>
    </form>
</body>
</html>
