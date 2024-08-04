<?php 
include_once '../bdConnection.php';
include '../Controller/standardFunctionsController.php';

if(isset($_POST['btnSalvar'])) {
    $nome = $_POST['nome'];
    $cpf = formatarCPFSQL($_POST['cpf']);
    $fone = formatarTelefoneSQL($_POST['fone']);
    $email = $_POST['email'];
    $senha = md5($_POST['senha']);
    $dtnasc = ($_POST['dtnasc']);
    $rua = $_POST['rua'];
    $complemento = $_POST['complemento'];
    $ncasa = $_POST['ncasa'];
    $cep = formatarCEPSQL($_POST['cep']);
    $codcid = $_POST['codcid']; 
    $tipo = 'A'; 
    $ativo = 'S';

    $dtnasc = DateTime::createFromFormat('d/m/Y', $dtnasc);
    if(!$dtnasc) {
        echo '<script>alert("Formato da data é inválido, use o formato dd/mm/aaaa");</script>';
    } else {
        $dtnasc = $dtnasc->format('Y-m-d');
        $fone = preg_replace('/[^0-9]/', '', $fone);

        if(strlen($fone) < 10) {
            echo '<script>alert("O número de telefone deve ter pelo menos 10 dígitos!");</script>';
        } else {
            $cpf = preg_replace('/[^0-9]/', '', $cpf);

            if(strlen($cpf) != 11) {
                echo '<script>alert("CPF deve conter 11 dígitos!");</script>';
            } else {
                try {
                    $pdo = conectar();

                    $stmt = $pdo->prepare("SELECT codcliente FROM tb_clientes WHERE email = :email");
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();
                    $emailR = $stmt->fetch(PDO::FETCH_ASSOC);

                    if($emailR) {
                        echo "<script>alert('O $email está sendo utilizado por outro usuário, tente outro email!');</script>";
                    } else {
                        $stmt = $pdo->prepare("SELECT codcliente FROM tb_clientes WHERE cpf = :cpf");
                        $stmt->bindParam(':cpf', $cpf);
                        $stmt->execute();
                        $cpfR = $stmt->fetch(PDO::FETCH_ASSOC);

                        if($cpfR) {
                            echo "<script>alert('O $cpf já está cadastrado em nosso sistema!');</script>";
                        } else {
                            $stmt = $pdo->prepare("INSERT INTO tb_clientes (nome, cpf, fone, email, senha, dtnasc, rua, complemento, ncasa, cep, tipo, ativo, codcid)
                                VALUES (:nome, :cpf, :fone, :email, :senha, :dtnasc, :rua, :complemento, :ncasa, :cep, :tipo, :ativo, :codcid)");
                            $stmt->bindValue(':nome', $nome);
                            $stmt->bindValue(':cpf', $cpf);
                            $stmt->bindValue(':fone', $fone);
                            $stmt->bindValue(':email', $email);
                            $stmt->bindValue(':senha', $senha);
                            $stmt->bindValue(':dtnasc', $dtnasc);
                            $stmt->bindValue(':rua', $rua);
                            $stmt->bindValue(':complemento', $complemento);
                            $stmt->bindValue(':ncasa', $ncasa);
                            $stmt->bindValue(':cep', $cep);
                            $stmt->bindValue(':tipo', $tipo);
                            $stmt->bindValue(':ativo', $ativo);
                            $stmt->bindValue(':codcid', $codcid);

                            if($stmt->execute()) {
                                session_start();
                                $_SESSION['nome'] = $nome;
                                header('Location: ../View/homeAdmin.php'); 
                                exit();
                            } else {
                                echo "<script>alert('Erro ao se cadastrar! Tente novamente!');</script>";
                            }
                        }
                    }
                } catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }
            }
        }
    }
}
?>
