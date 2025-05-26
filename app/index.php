<?php
session_start(); // Inicie a sessão no início do script

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="index-background">
    <div class="index-background-content">
        <header>
            <nav class="navbar">
                <ul class="menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="../app/view/viewproducts.php">Produtos</a></li>
                    <li><a href="../app/view/orcamento.php">Orçamento</a></li>
                    <li><a href="../app/view/funcionarios.php">Planejamento</a></li>
                    <li><a href="logout.php">Sair</a></li>
                </ul>
            </nav>
        </header>
        <main>
            <h1>Bem-vindo ao Sistema de Controle de Estoque</h1>
            <p>Esta é a página inicial.</p>
        </main>
    </div>
</body>
</html>

