<?php
include '../models/connect.php';

$lembrete = $_POST['lembrete'] ?? null;
$data = $_POST['data'] ?? null;

if ($lembrete && $data) {
    $stmt = $conn->prepare("DELETE FROM lembretes WHERE lembrete = ? AND data_lembrete = ?");
    $stmt->bind_param("ss", $lembrete, $data);
    
    if ($stmt->execute()) {
        echo "Lembrete removido com sucesso.";
    } else {
        echo "Erro ao remover lembrete.";
    }
    
    $stmt->close();
} else {
    echo "Lembrete ou data não informados.";
}

$conn->close();
?>
