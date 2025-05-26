<?php
include '../models/connect.php'; // Inclua seu arquivo de conexão

$result = $conn->query("SELECT data, lucro, gasto FROM lucros_gastos");
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
