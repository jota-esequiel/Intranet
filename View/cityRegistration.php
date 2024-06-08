<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    session_start();
    if(isset($_GET['mensagem'])) {
        echo "<p>{$_GET['mensagem']}</p>";
    }
    ?>
    <form method="post" action="../Controller/cityRegistrationController.php">
                    <h1>CADASTRO DE CIDADES</h1>
                    <br>

                    <select name = "nomecidade" required>
                        <option value = "">Selecione a cidade</option>
                        <?php 
                            include_once '../bdConnection.php';
                            $city = array("Cascavel");

                            foreach($city as $cidade) {
                                echo "<option value = '$cidade'>$cidade</option>";
                            }
                        ?>
                    </select>

                    <select name="uf" required>
                        <option value="">Selecione a UF</option>
                        <?php
                        include_once '../bdConnection.php';
                            $ufs = array("PR");

                            foreach($ufs as $uf) {
                                echo "<option value='$uf'>$uf</option>";
                            }
                        ?>
                    </select>
                    <br><br>
                    <button type="submit" class="buttoncad" name="btnSalvar">CADASTRAR CIDADE</button>
    </form>
</body>
</html>