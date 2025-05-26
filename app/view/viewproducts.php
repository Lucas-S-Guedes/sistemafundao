<?php
session_start(); // Inicie a sessão no início do script

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


// Incluir arquivo de conexão
include_once '../models/connect.php'; // Ajuste o caminho conforme necessário

// Consultar todos os produtos
$query = 'SELECT CodRefProduto, NomeProduto, Usuario_idUsuario, QntProduto, ValorProduto, Tipo FROM Produto';
$stmt = $conn->query($query);

// Verificar se há produtos
$num = $stmt->num_rows;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <style>
        .body {
            font-family: Arial, sans-serif;
          
        }
        .container {
            width: 80%;
            margin: 20px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .no-products {
            text-align: center;
            padding: 20px;
        }
    </style>
    <link rel="stylesheet" href="../styles.css">
</head>
<body class="index-background">
<header>
<div class="index-background-content"></div>
        <nav class="navbar">
            <ul class="menu">
                <li><a href="../index.php">Home</a></li>
                <li><a href="">Produtos</a></li>
                <li><a href="orcamento.php">Orçamento</a></li>
                <li><a href="funcionarios.php">Planejamentos</a></li>
                <li><a href="../logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <?php if ($num > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Usuário</th>
                        <th>Qnt</th>
                        <th>Valor</th>
                        <th>Tipo</th>

                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['CodRefProduto']); ?></td>
                            <td><?php echo htmlspecialchars($row['NomeProduto']); ?></td>
                            <td><?php echo htmlspecialchars($row['Usuario_idUsuario']); ?></td>
                            <td><?php echo htmlspecialchars($row['QntProduto']); ?></td>
                            <td><?php echo htmlspecialchars($row['ValorProduto']); ?></td>
                            <td><?php echo htmlspecialchars($row['Tipo']); ?></td>
                            
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-products">Nenhum produto encontrado.</p>
        <?php endif; ?>
        <button><a href="produtoscdr.php" style="color:white;  margin-top:20px;">Cadastre um produto</a></button> 
    </div>
    </div>
</body>
</html>
