<?php

class Product {
    private $conn;
    private $table = 'Produto'; // Nome da tabela de produtos

    // Propriedades do produto
    public $CodRefProduto;
    public $NomeProduto;
    public $Usuario_idUsuario;
    public $QntProduto;
    public $ValorProduto;
    public $Tipo;

    // Construtor com DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Criar um novo produto
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (NomeProduto, Usuario_idUsuario, QntProduto, ValorProduto, Tipo) VALUES (?, ?, ?, ?, ?)';

        $stmt = $this->conn->prepare($query);

        // Limpar dados
        $this->NomeProduto = htmlspecialchars(strip_tags($this->NomeProduto));
        $this->Usuario_idUsuario = htmlspecialchars(strip_tags($this->Usuario_idUsuario));
        $this->QntProduto = htmlspecialchars(strip_tags($this->QntProduto));
        $this->ValorProduto = htmlspecialchars(strip_tags($this->ValorProduto));
        $this->Tipo = htmlspecialchars(strip_tags($this->Tipo));

        // Bind dados
        $stmt->bind_param('siids', $this->NomeProduto, $this->Usuario_idUsuario, $this->QntProduto, $this->ValorProduto, $this->Tipo);

        // Executar consulta
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Deletar um produto
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE CodRefProduto = ?';

        $stmt = $this->conn->prepare($query);

        // Limpar dados
        $this->CodRefProduto = htmlspecialchars(strip_tags($this->CodRefProduto));

        // Bind dados
        $stmt->bind_param('i', $this->CodRefProduto); // 'i' significa integer

        // Executar consulta
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Atualizar um produto
    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET NomeProduto = ?, Usuario_idUsuario = ?, QntProduto = ?, ValorProduto = ?, Tipo = ? WHERE CodRefProduto = ?';

        $stmt = $this->conn->prepare($query);

        // Limpar dados
        $this->CodRefProduto = htmlspecialchars(strip_tags($this->CodRefProduto));
        $this->NomeProduto = htmlspecialchars(strip_tags($this->NomeProduto));
        $this->Usuario_idUsuario = htmlspecialchars(strip_tags($this->Usuario_idUsuario));
        $this->QntProduto = htmlspecialchars(strip_tags($this->QntProduto));
        $this->ValorProduto = htmlspecialchars(strip_tags($this->ValorProduto));
        $this->Tipo = htmlspecialchars(strip_tags($this->Tipo));

        // Bind dados
        $stmt->bind_param('siidsi', $this->NomeProduto, $this->Usuario_idUsuario, $this->QntProduto, $this->ValorProduto, $this->Tipo, $this->CodRefProduto);

        // Executar consulta
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Ler todos os produtos
    public function read() {
        $query = 'SELECT CodRefProduto, NomeProduto, Usuario_idUsuario, QntProduto, ValorProduto, Tipo FROM ' . $this->table;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->get_result(); // get_result() para obter o resultado da consulta
    }

    // Ler um produto por ID
    public function read_single() {
        $query = 'SELECT CodRefProduto, NomeProduto, Usuario_idUsuario, QntProduto, ValorProduto, Tipo FROM ' . $this->table . ' WHERE CodRefProduto = ?';

        $stmt = $this->conn->prepare($query);

        // Limpar dados
        $this->CodRefProduto = htmlspecialchars(strip_tags($this->CodRefProduto));

        // Bind dados
        $stmt->bind_param('i', $this->CodRefProduto); // 'i' significa integer

        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Set propriedades
        $this->NomeProduto = $row['NomeProduto'];
        $this->Usuario_idUsuario = $row['Usuario_idUsuario'];
        $this->QntProduto = $row['QntProduto'];
        $this->ValorProduto = $row['ValorProduto'];
        $this->Tipo = $row['Tipo'];
    }
}
