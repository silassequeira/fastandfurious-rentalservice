<?php
session_start();

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Database connection failed: " . pg_last_error());
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameInput = $_POST['username'];
    $emailInput = $_POST['email'];
    $passwordInput = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nameInput = $_POST['name'];

    $queryUser = pg_query_params(
        $connection,
        "SELECT username FROM cliente WHERE username = $1 OR email = $2",
        array($usernameInput, $emailInput)
    );

    $queryAdmin = pg_query_params(
        $connection,
        "SELECT username FROM administrador WHERE username = $1 OR email = $2",
        array($usernameInput, $emailInput)
    );

    if (pg_num_rows($queryUser) > 0 || pg_num_rows($queryAdmin) > 0) {
        $_SESSION['error'] = "Username ou email já existente";
        header('Location: register.php');
        exit();
    }

    if (strpos($emailInput, '.admin') !== false) {
        $sql = "INSERT INTO administrador (username, email, password, name) VALUES ($1, $2, $3, $4)";
        $resultAdmin = pg_query_params($connection, $sql, array($usernameInput, $emailInput, $passwordInput, $nameInput));

        if ($resultAdmin) {
            $_SESSION['success'] = "Conta de administrador criada com sucesso!";
            header('Location: admin_visualizeAllCars.php');
            exit();
        } else {
            $_SESSION['error'] = "Erro ao criar a conta de administrador: " . pg_last_error($connection);
            header('Location: register.php');
            exit();
        }
    } else {
        $sql = "INSERT INTO cliente (username, email, password, name, saldo) VALUES ($1, $2, $3, $4, 0)";
        $resultUser = pg_query_params($connection, $sql, array($usernameInput, $emailInput, $passwordInput, $nameInput));

        if ($resultUser) {
            $_SESSION['success'] = "Conta criada com sucesso!";
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['error'] = "Erro ao criar a conta: " . pg_last_error($connection);
            header('Location: register.php');
            exit();
        }
    }

    pg_close($connection);
    exit();
}
?>
