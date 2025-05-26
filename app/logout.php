<?php
session_start(); // Inicie a sessão no início do script

// Destruir todas as variáveis de sessão
$_SESSION = array();

// Se você deseja destruir o cookie de sessão, também deve
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir a sessão
session_destroy();

// Redirecionar para a página de login ou inicial
header("Location: login.php");
exit();
?>
