<?php
session_start();

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Error Connecting to Database");
}

$submitInput = $_POST['submit'];
$usernameInput = $_POST['username'];
$emailInput = $_POST['email'];
$passwordInput = password_hash($_POST['password'], PASSWORD_DEFAULT);
$nameInput = $_POST['name'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $query = pg_query_params(
        $connection,
        "SELECT username FROM cliente WHERE username = $1 OR email = $2",
        array($usernameInput, $emailInput)
    );

    if (pg_num_rows($conf) > 0) {
        echo "Username ou email já existente";
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
    pg_close($connection);
}
?>