<?php
session_start();

$user = $_GET['username'];
$pass = $_GET['password'];

$resultados = pg_query_params($connection, 
    "SELECT password FROM cliente WHERE username = $1 OR email = $1", 
    array($user)
);

if (pg_num_rows($resultados) != 0) {
    $row = pg_fetch_assoc($resultados);
    
    if (password_verify($pass, $row['password'])) {
        $_SESSION['user'] = $user;
        echo "Autenticação bem-sucedida";
        header('Location: index.php');
        exit();
    } else {
        echo "Senha ou usuário incorretos";
    }
} else {
    echo "Usuário não encontrado";
}

pg_close($connection);
?>