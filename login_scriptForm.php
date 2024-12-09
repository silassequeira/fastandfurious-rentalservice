<?php
session_start();

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Database connection failed: " . pg_last_error());
}

# Checks if the login form was submitted and defines the $_SESSION variable accordingly
if (isset($_POST['submitLogin']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameInput = $_POST['username']; 
    $passwordInput = $_POST['password'];

    $resultUser = pg_query_params(
        $connection, 
        "SELECT password FROM cliente WHERE username = $1 OR email = $1", 
        array($usernameInput)
    );

    $resultAdmin = pg_query_params(
        $connection, 
        "SELECT password FROM administrador WHERE username = $1 OR email = $1", 
        array($usernameInput)
    );

    if (pg_num_rows($resultUser) > 0) {
        $userRow = pg_fetch_assoc($resultUser);
        
        if (password_verify($passwordInput, $userRow['password'])) {
            $_SESSION['user'] = $usernameInput;
            $_SESSION['logged_in'] = true;
            $_SESSION['success'] = "Autenticação bem-sucedida como cliente";
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['error'] = "Senha ou usuário incorretos" . pg_last_error($connection);
            header('Location: login.php');
            exit();
        }
    }
    elseif (pg_num_rows($resultAdmin) > 0) {
        $adminRow = pg_fetch_assoc($resultAdmin);
        
        if (password_verify($passwordInput, $adminRow['password'])) {
            $_SESSION['admin'] = $usernameInput;
            $_SESSION['logged_in'] = true;
            $_SESSION['success'] = "Autenticação bem-sucedida como administrador";
            header('Location: admin_visualizeAllCars.php');
            exit();
        } else {
            $_SESSION['error'] = "Senha ou administrador incorretos" . pg_last_error($connection);
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Usuário ou administrador não encontrado" . pg_last_error($connection);
        header('Location: login.php');
        exit();
    }
}

pg_close($connection);
