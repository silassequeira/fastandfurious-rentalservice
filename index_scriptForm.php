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

    $idReserva = generateUniqueId($connection, 'reserva_', 'id_reserva');
    $_SESSION['idReserva'] = $idReserva;

    $sql = " INSERT INTO reserva_ (id_reserva, datainicio, datafim, cliente_username, cliente_email) VALUES ($1, $2, $3, $4, $5)";
    $params = array($idReserva, $datainicioInput, $datafimInput, $username, $email);
    $result = pg_query_params($connection, $sql, $params);

    if ($result) {
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
