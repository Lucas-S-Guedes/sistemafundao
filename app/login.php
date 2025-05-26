<?php
session_start(); // Inicie a sessão no início do script

// Configurações de conexão
$host = "localhost"; 
$dbname = "ControleEstoque"; 
$username = "root"; 
$password = ""; 

// Criar a conexão
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Inicialize a variável de erro
$error_message = "";

// Verificar se o formulário de login foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar se as variáveis do formulário estão definidas
    if (isset($_POST['Username']) && isset($_POST['Password'])) {
        $username = $_POST['Username'];
        $password = $_POST['Password'];

        // Preparar e executar a consulta
        $stmt = $conn->prepare("SELECT idUsuario FROM Usuario WHERE Username = ? AND Password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $stmt->store_result();

        // Verificar se o usuário existe
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();
            
            // Armazenar o ID do usuário na sessão
            $_SESSION['user_id'] = $user_id;
            
            // Redirecionar para a página principal
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Usuário ou senha inválidos!";
        }

        $stmt->close();
    } else {
        $error_message = "Por favor, preencha todos os campos.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="background"></div>
    <div class="overlay"></div>
    <div class="login-container">
        <h2>Login</h2>
        <form action="" method="post">
            <label for="username">Usuário:</label>
            <input type="text" name="Username" required><br>
            
            <label for="password">Senha:</label>
            <input type="password" name="Password" required><br>
            
            <button type="submit">Entrar</button>
            <a href="register.php">Cadastrar</a>
        </form>

        <?php if ($error_message): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
