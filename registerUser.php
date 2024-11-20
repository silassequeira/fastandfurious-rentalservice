<?php
$str = "dbname=postgres user=postgres password=postgres host=localhost port=5432";
$connection = pg_connect($str);

if (!$connection) {
    die("Error Connecting to Database");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newuser = $_POST['username'];
    $email = $_POST['email'];  // Assuming you've added an email field to the HTML form
    $newpass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];

    // Check if username or email already exists
    $conf = pg_query_params($connection, 
        "SELECT username FROM cliente WHERE username = $1 OR email = $2", 
        array($newuser, $email)
    );

    if (pg_num_rows($conf) > 0) {
        echo "Username ou email já existente";
    } else {
        // Insert new user
        $sql = "INSERT INTO cliente (username, email, password, name, saldo) VALUES ($1, $2, $3, $4, 0)";
        $result = pg_query_params($connection, $sql, array($newuser, $email, $newpass, $name));

        if ($result) {
            echo "Conta criada com sucesso!";
        } else {
            echo "Erro ao criar a conta: " . pg_last_error($connection);
        }
    }
    
}
?>