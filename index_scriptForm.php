<?php
require 'create_uniqueID.php';
require 'checkSession.php';

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Error connecting to the database");
}

$sessionCheck = checkSession($connection);


# Checks if form related to the date of the rental was submitted 
if (isset($_POST['submitDate']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    $datainicioInput = $_POST['datainicio'];
    $datafimInput = $_POST['datafim'];
    $userDetails = $sessionCheck['details'];
    $username = $userDetails['username'];
    $email = $userDetails['email'];
    $saldo = $userDetails['saldo'];

    $idReserva = generateUniqueId($connection, 'reserva', 'idreserva');

    $_SESSION['reservation_data'] = [
        'id' => $idReserva,
        'datainicio' => $datainicioInput,
        'datafim' => $datafimInput,
        'saldo' => $saldo,
        'cliente_username' => $username,
        'cliente_email' => $email
    ];

    if (isset($_SESSION['reservation_data'])) {
        $_SESSION['success'] = "Data de início e data de fim registradas com sucesso!";
        header('Location: user_selectCar.php');
        exit();
    } else {
        $_SESSION['errorIndex'] = "Erro ao registrar a data da reserva: " . pg_last_error($connection);
        header('Location: index.php');
        exit();
    }

    # Checks if its an admin, if so, it will logout
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['admin'])) {
    $_SESSION['errorIndex'] = "A conta está registada como administrador " . pg_last_error($connection);
    header('Location: logout.php');
    exit();

    # If no one has logged in yet a error message will be displayed
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    $_SESSION['errorIndex'] = "Por favor, faça login para reservar um carro";
    header('Location: index.php');
    exit();
}

pg_close($connection);
