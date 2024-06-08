<?php
function conectar(){ //Função para se conectar com o banco de dados e permitir CRUD 
    $host = 'localhost';
    $db = 'tcc'; //Sempre criar a database com o nome que está aqui
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erro na conexão: " . $e->getMessage());
    }
}
?>
