<?php
include '../models/connect.php'; // Inclua seu arquivo de conexão

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'];
    $lucro = $_POST['lucro'];
    $gasto = $_POST['gasto'];

    $stmt = $conn->prepare("INSERT INTO lucros_gastos (data, lucro, gasto) VALUES (?, ?, ?)");
    $stmt->bind_param("sdd", $data, $lucro, $gasto);

    if ($stmt->execute()) {
        echo "Dados salvos com sucesso!";
    } else {
        echo "Erro ao salvar dados: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
