<?php
include '../models/connect.php';

$query = "SELECT lembrete, data_lembrete FROM lembretes";
$result = mysqli_query($conn, $query);

$eventos = [];

while ($row = mysqli_fetch_assoc($result)) {
    $eventos[] = [
        'title' => $row['lembrete'],
        'start' => $row['data_lembrete']
    ];
}

// Retorna os lembretes em formato JSON para o FullCalendar
echo json_encode($eventos);
?>
