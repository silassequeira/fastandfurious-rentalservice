<?php
require 'create_uniqueID.php';
require 'checkSession.php';

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Error connecting to the database");
}

$sessionCheck = checkSession($connection);

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
        'saldo'=> $saldo,
        'cliente_username' => $username,
        'cliente_email'=> $email
    ];

    if (isset($_SESSION['reservation_data'])) {
        $_SESSION['success'] = "Data de início e data de fim registradas com sucesso!";
        header('Location: user_selectCar.php');
        exit();
    } else {
        $_SESSION['error'] = "Erro ao registrar a data da reserva: " . pg_last_error($connection);
        header('Location: index.php');
        exit();
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['admin'])) {
    $_SESSION['error'] = "A conta está registada como administrador " . pg_last_error($connection);
    header('Location: logout.php');
    exit();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    $_SESSION['error'] = "Por favor, faça login para reservar um carro";
    header('Location: index.php');
    exit();
}

pg_close($connection);
