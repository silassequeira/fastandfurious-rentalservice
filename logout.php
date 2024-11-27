<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['logged_in'] = false;
$_SESSION['user'] = null;
$_SESSION['admin'] = null;
session_unset();     // remove all session variables
session_destroy();   // destroy the session

// Remove o cookie de sessão (opcional, para garantir que o cliente não guarda informações antigas)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

header("Location: index.php");
exit;