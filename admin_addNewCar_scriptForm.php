<?php
session_start();
include 'criar_Id.php';  // Include the ID generation script

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Erro na conexão");
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs to prevent SQL injection
    $name = pg_escape_string($connection, $_POST['car-name']);
    $brand = pg_escape_string($connection, $_POST['brand']);
    $model = pg_escape_string($connection, $_POST['model']);
    $seats = pg_escape_string($connection, $_POST['car-seats']);
    $year = pg_escape_string($connection, $_POST['year']);
    $price = pg_escape_string($connection, $_POST['price']);
    $user = $_SESSION['user'];

    // Generate unique ID using the function from criar_Id.php
    $id = generateUniqueId($connection, 'carro', 'id_carro');

    // Insert query
    $sql = "INSERT INTO carro (id_carro, marca, modelo, ano, assentos, valordiario, administrador_username) 
            VALUES ($1, $2, $3, $4, $5, $6, $7)";
    
    $result = pg_query_params($connection, $sql, [
        $id, $brand, $model, $year, $seats, $price, $user
    ]);

    if ($result) {
        echo "Carro adicionado com sucesso!";
    } else {
        echo "Erro ao adicionar carro: " . pg_last_error($connection);
    }
}

// Close connection
pg_close($connection);
?>