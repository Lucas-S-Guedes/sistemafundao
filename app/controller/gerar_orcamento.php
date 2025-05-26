<?php
session_start(); // Inicie a sessão no início do script

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Incluir o autoload do Composer para mPDF
require_once __DIR__ . '/../vendor/autoload.php'; // Ajuste o caminho conforme necessário

use Mpdf\Mpdf;

// Criar uma instância do mPDF
$mpdf = new Mpdf();

// Configuração do título e corpo do PDF
$mpdf->SetTitle('Orçamento');

// Conectar ao banco de dados
include_once '../models/connect.php'; // Ajuste o caminho conforme necessário

// Verificar se há produtos selecionados na sessão
if (!isset($_SESSION['selected_products']) || empty($_SESSION['selected_products'])) {
    die('Nenhum produto selecionado para o orçamento.');
}

// Criar o conteúdo HTML para o PDF
$html = '
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Orçamento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1, h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        thead {
            background-color: #4CAF50;
            color: white;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tbody tr:hover {
            background-color: #ddd;
        }
        tfoot {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Fundão Poços Artesianos</h1>
    <h2>Orçamento</h2>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Tipo</th>
                <th>Valor da Unidade</th>
                <th>Valor Total</th>
            </tr>
        </thead>
        <tbody>';

// Inicializar o total
$total = 0;

// Adicionar produtos selecionados à tabela
foreach ($_SESSION['selected_products'] as $product) {
    $produto = htmlspecialchars($product['NomeProduto']);
    $quantidade = htmlspecialchars($product['QntProduto']);
    $tipo = htmlspecialchars($product['Tipo']);
    $valor_unidade = htmlspecialchars($product['ValorProduto']);
    $valor_total = $quantidade * $valor_unidade;
    $total += $valor_total;
    
    $html .= "<tr>
                <td>{$produto}</td>
                <td>{$quantidade}</td>
                <td>{$tipo}</td>
                <td>R$ " . number_format($valor_unidade, 2, ',', '.') . "</td>
                <td>R$ " . number_format($valor_total, 2, ',', '.') . "</td>
              </tr>";
}

$html .= '
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;">Total</td>
                <td>R$ ' . number_format($total, 2, ',', '.') . '</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>';

// Adicionar o HTML ao mPDF
$mpdf->WriteHTML($html);

// Enviar o PDF para o navegador
$mpdf->Output('orcamento.pdf', 'D');
?>
