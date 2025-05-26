<?php
include '../models/connect.php'; // Inclua seu arquivo de conexão

if ($conn->query("DELETE FROM lucros_gastos") === TRUE) {
    echo "Dados limpos com sucesso.";
} else {
    echo "Erro ao limpar dados: " . $conn->error;
}

$conn->close();
?>
