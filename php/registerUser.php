<?php
// Include the database configuration file
if (!file_exists('db_config.php')) {
    die('Config file not found!');
}
require 'php/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newuser = $_POST['username'];
    $newpass = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $name = $_POST['name'];

    // Check if username exists
    $conf = pg_query_params($connection, "SELECT username FROM cliente WHERE username = $1", array($newuser));
    if (pg_num_rows($conf) != 0) {
        echo "username já existente";
    } else {
        // Insert new user
        $sql = "INSERT INTO cliente (username, password, name) VALUES ($1, $2, $3)";
        $result = pg_query_params($connection, $sql, array($newuser, $newpass, $name));
        if ($result) {
            echo "Conta criada com sucesso!";
        } else {
            echo "Erro ao criar a conta: " . pg_last_error($connection);
        }
    }
}

// Always close the connection when done
pg_close($connection);
?>
