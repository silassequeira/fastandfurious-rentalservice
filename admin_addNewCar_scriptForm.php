<?php
require 'create_uniqueID.php';
require 'checkSession.php';

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Erro na conexão");
}

$sessionCheck = checkSession($connection);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitNewCar']) && isset($_SESSION['admin'])) {

    $brandInput = $_POST['marca'];
    $modelInput = $_POST['modelo'];
    $yearInput = $_POST['ano'];
    $seatsInput = $_POST['assentos'];
    $priceInput = $_POST['valordiario'];
    $adminDetails = $sessionCheck['details'];
    $username = $adminDetails['username'];

    $idCarro = generateUniqueId($connection, 'carro', 'id_carro_');

    $file_name = $_FILES['foto']['name'];
    $file_tmp = $_FILES['foto']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $target_dir = "uploads/{$idCarro}/";

    $unique_file_name = uniqid('car_', true) . '.' . $file_ext;
    $file_destination = "{$target_dir}{$unique_file_name}";

    if (move_uploaded_file($file_tmp, $file_destination)) {
        $imageInput = $file_destination;
    } else {
        $_SESSION['error'] = "Erro ao salvar a imagem.";
        header('Location: admin_addNewCar.php');
        exit();
    }

    $sql = "INSERT INTO carro (id_carro, marca, modelo, ano, assentos, valordiario, administrador_username, foto) VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";
    $params = array($idCarro, $brandInput, $modelInput, $yearInput, $seatsInput, $priceInput, $username, $imageInput);
    $result = pg_query_params($connection, $sql, $params);

    if ($result) {
        $_SESSION['success'] = "Carro adicionado com sucesso!";
        header('Location: admin_visualizeAllCars.php');
        exit();
    } else {
        error_log("Error inserting data: " . pg_last_error($connection));
        $_SESSION['error'] = "Erro ao adicionar o carro: " . pg_last_error($connection);
        header('Location: admin_addNewCar.php');
        exit();
    }
}

pg_close($connection);
