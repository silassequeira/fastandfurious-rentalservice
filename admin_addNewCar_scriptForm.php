<?php
require 'create_uniqueID.php';
require 'checkSession.php';

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Erro na conexão");
}

$carSelected = $_SESSION['selected_car'] ?? null;

$sessionCheck = checkSession($connection);

$brandInput = $_POST['marca'];
$modelInput = $_POST['modelo'];
$yearInput = $_POST['ano'];
$seatsInput = $_POST['assentos'];
$priceInput = $_POST['valordiario'];
$hidden = 'false';
$rented = 'false';
$adminDetails = $sessionCheck['details'];
$username = $adminDetails['username'];
$email = $adminDetails['email'];

# Code to add a new car
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitNewCar'])) {

    $carId = generateUniqueId($connection, 'carro', 'idcarro');

    $file_name = $_FILES['foto']['name'];
    $file_temporaryPath = $_FILES['foto']['tmp_name']; # Contains the temporary path where the file is stored on the server

    $target_dir = "uploads/" . $carId . "/"; 

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, recursive: true); #code ensures that the uploads directory and the specific subdirectory for the car (based on carId) are created
    }
    
    $file_name = basename($_FILES['foto']['name']);
    $file_destination = $target_dir . $file_name;

    $file_destination = str_replace("/", DIRECTORY_SEPARATOR, $file_destination);
    $file_destination = str_replace("\\", DIRECTORY_SEPARATOR, $file_destination);

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $file_destination)) {
        $imageInput = $file_destination;
    } else {
        error_log("Move failed. Source: " . $_FILES['foto']['tmp_name']);
        error_log("Destination: " . $file_destination);
        error_log("Full error: " . print_r(error_get_last(), true));

        $_SESSION['errorNewCar'] = "Erro ao salvar a imagem.";
        header('Location: admin_addNewCar.php');
        exit();
    }

    $sql = "INSERT INTO carro (idcarro, foto, marca, modelo, ano, assentos, valordiario, ocultado, arrendado, administrador_username, administrador_email) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)";
    $params = array($carId, $imageInput, $brandInput, $modelInput, $yearInput, $seatsInput, $priceInput, $hidden, $rented, $username, $email);
    $result = pg_query_params($connection, $sql, $params);

    if ($result) {
        $_SESSION['success'] = "Carro adicionado com sucesso!";
        header('Location: admin_visualizeAllCars.php');
        exit();
    } else {
        error_log("Error inserting data: " . pg_last_error($connection));
        $_SESSION['errorNewCar'] = "Erro ao adicionar o carro: " . pg_last_error($connection);
        header('Location: admin_addNewCar.php');
        exit();
    }

    # Code to Update the selected car
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitUpdateCar']) && isset($carSelected)) {

    $selectedCar = $_SESSION['selected_car'] ?? null;
    $carId = $selectedCar['idcarro'];

    if (isset($_FILES['foto']) && $_FILES['foto']['errorNewCar'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['foto']['name'];
        $file_temporaryPath = $_FILES['foto']['tmp_name'];

        $target_dir = "uploads/" . $carId . "/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = basename($_FILES['foto']['name']);
        $file_destination = $target_dir . $file_name;

        $file_destination = str_replace("/", DIRECTORY_SEPARATOR, $file_destination);
        $file_destination = str_replace("\\", DIRECTORY_SEPARATOR, $file_destination);

        if (move_uploaded_file($file_temporaryPath, $file_destination)) {
            $imageInput = $file_destination;
        } else {
            error_log("Move failed. Source: " . $file_temporaryPath);
            error_log("Destination: " . $file_destination);
            error_log("Full error: " . print_r(error_get_last(), true));

            $_SESSION['errorNewCar'] = "Erro ao salvar a imagem.";
            header('Location: admin_addNewCar.php');
            exit();
        }
    } else {
        $query = "SELECT foto FROM carro WHERE idcarro = $1";
        $result = pg_query_params($connection, $query, [$carId]);
        if ($result) {
            $existingCar = pg_fetch_assoc($result);
            $imageInput = $existingCar['foto'];
        }
    }

    $sql = "UPDATE carro SET 
            foto = $2, 
            marca = $3, 
            modelo = $4, 
            ano = $5, 
            assentos = $6, 
            valordiario = $7 
        WHERE idcarro = $1";

    $params = array($carId, $imageInput, $brandInput, $modelInput, $yearInput, $seatsInput, $priceInput);
    $result = pg_query_params($connection, $sql, $params);

    if ($result) {
        $_SESSION['success'] = "Carro atualizado com sucesso!";
        header('Location: admin_visualizeAllCars.php');
        exit();
    } else {
        error_log("Error updating data: " . pg_last_error($connection));
        $_SESSION['errorNewCar'] = "Erro ao atualizar o carro: " . pg_last_error($connection);
        header('Location: admin_addNewCar.php');
        exit();
    }
}


pg_close($connection);