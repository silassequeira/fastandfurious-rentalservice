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
    $file_temporaryPath = $_FILES['foto']['tmp_name'];

    $target_dir = "uploads/" . $idCarro . "/";

    // Create directory if not exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_name = basename($_FILES['foto']['name']);
    $file_destination = $target_dir . $file_name;
    
    // Sanitize file path
    $file_destination = str_replace("/", DIRECTORY_SEPARATOR, $file_destination);
    $file_destination = str_replace("\\", DIRECTORY_SEPARATOR, $file_destination);
    
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $file_destination)) {
        // Successful upload
        $imageInput = $file_destination;
    } else {
        // Detailed error logging
        error_log("Move failed. Source: " . $_FILES['foto']['tmp_name']);
        error_log("Destination: " . $file_destination);
        error_log("Full error: " . print_r(error_get_last(), true));
        
        $_SESSION['error'] = "Erro ao salvar a imagem.";
        header('Location: admin_addNewCar.php');
        exit();
    }

    $sql = "INSERT INTO carro (id_carro_, marca, modelo, assentos, valordiario, administrador_username, foto, ano) VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";
    $params = array($idCarro, $brandInput, $modelInput, $seatsInput, $priceInput, $username, $imageInput, $yearInput);
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
