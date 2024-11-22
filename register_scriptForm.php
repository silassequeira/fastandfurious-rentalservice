<?php
session_start();

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Error Connecting to Database");
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
        echo "Username ou email já existente";
    } else {
        if (strpos($emailInput, '.admin') !== false) {
            $sql = "INSERT INTO administrador (username, email, password, name) VALUES ($1, $2, $3, $4)";
            $resultAdmin = pg_query_params($connection, $sql, array($usernameInput, $emailInput, $passwordInput, $nameInput));

            if ($resultAdmin) {
                echo "Conta de administrador criada com sucesso!";
                header('Location: admin_visualizeAllCars.php');
                exit();
            } else {
                echo "Erro ao criar a conta de administrador: " . pg_last_error($connection);
            }
        } else {
            $sql = "INSERT INTO cliente (username, email, password, name, saldo) VALUES ($1, $2, $3, $4, 0)";
            $resultUser = pg_query_params($connection, $sql, array($usernameInput, $emailInput, $passwordInput, $nameInput));

            if ($resultUser) {
                echo "Conta criada com sucesso!";
                header('Location: index.php');
                exit();
            } else {
                echo "Erro ao criar a conta: " . pg_last_error($connection);
            }
        }
    }
    pg_close($connection);
}
?>
