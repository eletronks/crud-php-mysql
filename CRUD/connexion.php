<?php
$host = "localhost";
$port = 3306;
$db_name = "facebook_clone";
$user = "root";
$pass = "";

try{
    $conn = new PDO("mysql:host=$host;port=$port;dbname=". $db_name, $user, $pass);
    // echo "Conexão feita com sucesso";
}catch(PDOException $e){
    echo "Erro: Conexão com base de dados não realizada. Erro gerado:" . $e->getMessage();
    die("Erro 001: Por favor tente novamente. Caso o problema persista, entre em
    contato com o suporte: nectar.code@suporte.com");
}