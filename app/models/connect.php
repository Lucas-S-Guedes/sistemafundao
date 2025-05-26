<?php
// Configurações de conexão
$host = "localhost";
$dbname = "ControleEstoque";
$username = "root";
$password = "";

// Criar a conexão
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
