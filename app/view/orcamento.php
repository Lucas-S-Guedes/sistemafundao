<?php
session_start(); // Inicie a sessão no início do script

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Incluir arquivo de conexão
include_once '../models/connect.php'; // Ajuste o caminho conforme necessário

// Consultar todos os produtos
$query = 'SELECT CodRefProduto, NomeProduto, QntProduto, ValorProduto, Tipo FROM Produto';
$stmt = $conn->query($query);

// Verificar se há produtos
$num = $stmt->num_rows;

// Verificar se o formulário foi enviado para adicionar um produto à lista de orçamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Obter detalhes do produto
        $stmt_product = $conn->prepare('SELECT NomeProduto, ValorProduto, Tipo FROM Produto WHERE CodRefProduto = ?');
        $stmt_product->bind_param('i', $product_id);
        $stmt_product->execute();
        $result = $stmt_product->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            // Adicionar o produto à lista de orçamento na sessão
            $_SESSION['selected_products'][] = [
                'CodRefProduto' => $product_id,
                'NomeProduto' => $product['NomeProduto'],
                'QntProduto' => $quantity,
                'ValorProduto' => $product['ValorProduto'],
                'Tipo' => $product['Tipo'],
            ];
        }

        // Redirecionar para a página para evitar o reenvio do formulário
        header("Location: orcamento.php");
        exit();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'remove') {
        $product_id = $_POST['product_id'];

        // Filtrar os produtos selecionados, removendo o produto com o ID especificado
        $_SESSION['selected_products'] = array_filter($_SESSION['selected_products'], function($product) use ($product_id) {
            return $product['CodRefProduto'] != $product_id;
        });

        // Redirecionar para a página para evitar o reenvio do formulário
        header("Location: orcamento.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
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
        /* Estilos do Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        /* Estilos para a barra de pesquisa */
        #searchInput {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 2px;
            cursor: pointer;
        }
        button a {
            color: white;
            text-decoration: none;
        }
        .remove-button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .remove-button:hover {
            background-color: #d32f2f;
        }
        p{
            color: #f4f4f4;
        }
    </style>
    <link rel="stylesheet" href="../styles.css">
</head>
<body class="index-background">
<header>
    <nav class="navbar">
        <ul class="menu">
                 <li><a href="../index.php">Home</a></li>
                 <li><a href="viewproducts.php">Produtos</a></li>
                    <li><a href="">Orçamento</a></li>
                    <li><a href="funcionarios.php">Planejamento</a></li>
                    <li><a href="logout.php">Sair</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <?php if ($num > 0): ?>
        <table id="mainTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Valor</th>
                    <th>Tipo</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_SESSION['selected_products']) && !empty($_SESSION['selected_products'])):
                    $total = 0;
                    foreach ($_SESSION['selected_products'] as $product):
                        $subtotal = $product['QntProduto'] * $product['ValorProduto'];
                        $total += $subtotal;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['CodRefProduto']); ?></td>
                        <td><?php echo htmlspecialchars($product['NomeProduto']); ?></td>
                        <td><?php echo htmlspecialchars($product['QntProduto']); ?></td>
                        <td>R$ <?php echo number_format($product['ValorProduto'], 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($product['Tipo']); ?></td>
                        <td>
                            <form action="orcamento.php" method="post" style="display: inline;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['CodRefProduto']); ?>">
                                <button type="submit" class="remove-button">Remover</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="text-align: right;">Total</td>
                            <td>R$ <?php echo number_format($total, 2, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                <?php else: ?>
                    <p class="no-products">Nenhum produto selecionado.</p>
                <?php endif; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-products">Nenhum produto encontrado.</p>
    <?php endif; ?>

    <!-- Botão para abrir o modal -->
    <button id="openModalBtn">Adicionar produto</button>
    <br>
    <button style="margin-top: 20px;">
        <a href="../controller/gerar_orcamento.php" target="_blank">Gerar orçamento</a>
    </button>
</div>

<!-- Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Adicionar Produto</h2>

        <!-- Barra de pesquisa -->
        <input type="text" id="searchInput" placeholder="Buscar produtos..." onkeyup="filterProducts()">

        <table id="productsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Valor</th>
                    <th>Tipo</th>
                    <th>Adicionar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Voltar ao início dos resultados para exibir no modal
                $stmt->data_seek(0);
                while ($row = $stmt->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['CodRefProduto']); ?></td>
                        <td><?php echo htmlspecialchars($row['NomeProduto']); ?></td>
                        <td><?php echo htmlspecialchars($row['QntProduto']); ?></td>
                        <td><?php echo htmlspecialchars($row['ValorProduto']); ?></td>
                        <td><?php echo htmlspecialchars($row['Tipo'])?></td>
                        <td>
                            <form action="orcamento.php" method="post">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['CodRefProduto']); ?>">
                                <input type="number" name="quantity" value="1" min="1" style="width: 60px;">
                                <button type="submit">Adicionar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Abrir o modal
    var modal = document.getElementById("myModal");
    var btn = document.getElementById("openModalBtn");
    var span = document.getElementsByClassName("close")[0];

    btn.onclick = function() {
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Função para filtrar produtos
    function filterProducts() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("productsTable");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1]; // Busca na segunda coluna (nome do produto)
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }       
        }
    }
</script>

</body>
</html>
