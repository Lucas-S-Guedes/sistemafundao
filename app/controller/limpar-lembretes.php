<?php
// Conexão com o banco de dados
include '../models/connect.php';

// Limpar todos os lembretes
$stmt = $conn->prepare("DELETE FROM lembretes");

if ($stmt->execute()) {
    echo "Todos os lembretes foram removidos com sucesso!";
} else {
    echo "Erro ao remover os lembretes.";
}

$stmt->close();
$conn->close();
