<?php
session_start();

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Error Connecting to Database");
}

$usernameInput = $_POST['username']; 
$passwordInput = $_POST['password'];

$resultUser = pg_query_params($connection, 
    "SELECT password FROM cliente WHERE username = $1 OR email = $1", 
    array($usernameInput)
);

$resultAdmin = pg_query_params($connection, 
    "SELECT password FROM administrador WHERE username = $1 OR email = $1", 
    array($usernameInput)
);

if (pg_num_rows($resultUser) > 0) {
    $userRow = pg_fetch_assoc($resultUser);
    
    if (password_verify($passwordInput, $userRow['password'])) {
        $_SESSION['user'] = $usernameInput;
        echo "Autenticação bem-sucedida como cliente";
        header('Location: index.php');
        exit();
    } else {
        echo "Senha ou usuário incorretos";
    }
}
elseif (pg_num_rows($resultAdmin) > 0) {
    $adminRow = pg_fetch_assoc($resultAdmin);
    
    if (password_verify($passwordInput, $adminRow['password'])) {
        $_SESSION['admin'] = $usernameInput;
        echo "Autenticação bem-sucedida como administrador";
        header('Location: admin_dashboard.php'); 
        exit();
    } else {
        echo "Senha ou administrador incorretos";
    }
} else {
    echo "Usuário ou administrador não encontrado";
}

pg_close($connection);
?>
