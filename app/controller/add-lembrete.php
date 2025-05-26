<?php
include '../models/connect.php';

if (isset($_POST['lembrete']) && isset($_POST['data'])) {
    $lembrete = $_POST['lembrete'];
    $data = $_POST['data'];

    // Insere o lembrete no banco de dados
    $query = "INSERT INTO lembretes (lembrete, data_lembrete) VALUES ('$lembrete', '$data')";
    if (mysqli_query($conn, $query)) {
        echo "Lembrete adicionado com sucesso!";
    } else {
        echo "Erro ao adicionar lembrete: " . mysqli_error($conn);
    }
} else {
    echo "Erro: Dados do lembrete ou data estão ausentes.";
}
?>
