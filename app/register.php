<?php
session_start();
require_once "models/connect.php"; // Importar a conexão com o banco

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            // Verificar se o nome de usuário já existe
            $sql = "SELECT * FROM usuario WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            

            if ($result->num_rows > 0) {
                $error = "Usuário já existe. Tente outro nome.";
            } else {
                // Inserir o novo usuário
                $sql = "INSERT INTO Usuario (Username, Email, Password, Dataregistro, Permissao) VALUES (?, ?, ?, CURDATE(), 1)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $username, $email, $password); // Senha não criptografada por simplicidade, mas recomendo usar password_hash()
                
                if ($stmt->execute()) {
                    $_SESSION['username'] = $username;
                    header("Location: login.php"); // Redireciona para o login após cadastro
                    exit();
                } else {
                    $error = "Erro ao criar conta. Tente novamente.";
                }
            }
        } else {
            $error = "As senhas não coincidem.";
        }
    } else {
        $error = "Por favor, preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="background"></div>
    <div class="overlay"></div>
    <div class="login-container">
        <h2>Cadastro</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form action="register.php" method="post">
            <label for="username">Usuário:</label>
            <input type="text" name="username" id="username" required><br>
            
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br>
            
            <label for="password">Senha:</label>
            <input type="password" name="password" id="password" required><br>

            <label for="confirm_password">Confirme a Senha:</label>
            <input type="password" name="confirm_password" id="confirm_password" required><br>
            
            <button type="submit">Cadastrar</button>
        </form>
        <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
    </div>
</body>
</html>

