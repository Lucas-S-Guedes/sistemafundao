<?php
session_start(); // Inicie a sessão

// Configurações de conexão
include_once '../models/connect.php'; // Ajuste o caminho conforme necessário

// Incluir a classe Product
include_once '../models/Product.php'; // Ajuste o caminho conforme necessário

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // Redirecionar para o login se não estiver logado
    exit();
}

// Instanciar o objeto Product
$product = new Product($conn);

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Adicionar um novo produto
    if (isset($_POST['create'])) {
        $product->NomeProduto = $_POST['NomeProduto'];
        $product->ValorProduto = $_POST['ValorProduto'];
        $product->Tipo = $_POST['Tipo'];
        $product->QntProduto = $_POST['QntProduto'];
        $product->Usuario_idUsuario = $_SESSION['user_id']; // Usar o ID do usuário da sessão

        if ($product->create()) {
            echo "<p>Produto adicionado com sucesso!</p>";
        } else {
            echo "<p>Falha ao adicionar o produto.</p>";
        }
    }

    // Atualizar um produto
    if (isset($_POST['update'])) {
        $product->CodRefProduto = $_POST['CodRefProduto'];
        $product->NomeProduto = $_POST['NomeProduto'];
        $product->Usuario_idUsuario = $_SESSION['user_id']; // Usar o ID do usuário da sessão

        if ($product->update()) {
            echo "<p>Produto atualizado com sucesso!</p>";
        } else {
            echo "<p>Falha ao atualizar o produto.</p>";
        }
    }

    // Deletar um produto
    if (isset($_POST['delete'])) {
        $product->CodRefProduto = $_POST['CodRefProduto'];

        if ($product->delete()) {
            echo "<p>Produto deletado com sucesso!</p>";
        } else {
            echo "<p>Falha ao deletar o produto.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos</title>
    <style>
        
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        h1{
            color: #f4f4f4;
        }
        .lista{
            color: #f4f4f4;
        }
        
    </style>
    <link rel="stylesheet" href="../menu.css?v=1.0">
</head>
<body class="index-background">
<header>
<div class="index-background-content"></div>
    <nav class="navbar">
        <ul class="menu">
        <li><a href="../index.php">Home</a></li>
            <li><a href="viewproducts.php">Produtos</a></li>
            <li><a href="orcamento.php">Orçamento</a></li>
            <li><a href="funcionarios.php">Planejamento</a></li>
            <li><a href="../logout.php">Sair</a></li>
        </ul>
    </nav>
</header>
<div class="container">
    <h1>Gerenciar Produtos</h1>

    <!-- Formulário para Adicionar Produto -->
    <form method="POST" action="">
        <h2>Adicionar Novo Produto</h2>
        <input type="text" name="NomeProduto" placeholder="Nome do Produto" required>
        <input type="number" name="ValorProduto" placeholder="Preço do Produto" required>
        <input type="number" name="QntProduto"placeholder="Quantidade" required>
        <label for="Tipo">MT</label>
        <input type="radio" name="Tipo" value="MT" required>
        <label for="Tipo">UN</label>
        <input type="radio" name="Tipo" value="UN" required>

        <input type="submit" name="create" value="Adicionar Produto">
    </form>

    <!-- Formulário para Atualizar Produto -->
    <form method="POST" action="">
        <h2>Atualizar Produto</h2>
        <input type="number" name="CodRefProduto" placeholder="ID do Produto" required>
        <input type="text" name="NomeProduto" placeholder="Novo Nome do Produto" required>
        <input type="submit" name="update" value="Atualizar Produto">
    </form>

    <!-- Formulário para Deletar Produto -->
    <form method="POST" action="">
        <h2>Deletar Produto</h2>
        <input type="number" name="CodRefProduto" placeholder="ID do Produto" required>
        <input type="submit" name="delete" value="Deletar Produto">
    </form>

    <!-- Tabela para Mostrar Produtos -->
    <h2 class="lista">Lista de Produtos</h2>
    <?php
    $result = $product->read();

    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr><th>ID</th><th>Nome do Produto</th><th>ID do Usuário</th></tr>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['CodRefProduto'] . '</td>';
            echo '<td>' . $row['NomeProduto'] . '</td>';
            echo '<td>' . $row['Usuario_idUsuario'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>Nenhum produto encontrado.</p>';
    }
    ?>
</div>
</body>
</html>
