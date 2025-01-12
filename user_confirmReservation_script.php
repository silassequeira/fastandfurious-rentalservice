<?php
session_start();

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Database connection failed: " . pg_last_error());
}

$car = $_SESSION['selected_car'] ?? null;
$details = $_SESSION['reservation_data'] ?? null;

# Checks if the reservation form was submitted, updates the current balance and inserts the reservation into the database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitConfirmReservation'])) {

    if (!isset($details['saldo'], $details['custototal']) || $details['saldo'] < $details['custototal']) {
        $_SESSION['error'] = "Saldo insuficiente ou dados inválidos.";
        header('Location: user_confirmReservation.php');
        exit();
    }

    $newSaldo = $details['saldo'] - $details['custototal'];
    $sqlUpdateSaldo = "UPDATE cliente SET saldo = $1 WHERE username = $2";
    $paramsUpdateSaldo = [$newSaldo, $details['cliente_username']];

    $resultUpdateSaldo = pg_query_params($connection, $sqlUpdateSaldo, $paramsUpdateSaldo);

    if (!$resultUpdateSaldo) {
        $_SESSION['error'] = "Erro ao atualizar saldo.";
        header('Location: user_confirmReservation.php');
        exit();
    }

    $sql = "INSERT INTO reserva (idreserva, datainicio, datafim, custototal, carro_idcarro, cliente_username, cliente_email) 
            VALUES ($1, $2, $3, $4, $5, $6, $7)";
    $params = [
        $details['id'],
        $details['datainicio'],
        $details['datafim'],
        $details['custototal'],
        $car['idcarro'],
        $details['cliente_username'],
        $details['cliente_email']
    ];

    $result = pg_query_params($connection, $sql, $params);

    $selectSql = "SELECT arrendado FROM carro WHERE idcarro = $1";
    $selectResult = pg_query_params($connection, $selectSql, array($car['idcarro']));

    if (!$selectResult) {
        die("Erro ao buscar status do carro: " . pg_last_error($connection));
    }

    $currentStatus = pg_fetch_result($selectResult, 0, 'arrendado');

    if ($currentStatus === 't' && $_SESSION['countReservations'] >= 1) {
        $rented = $currentStatus === 't';
    } else {
        $rented = $currentStatus === 't' ? 'f' : 't'; // Toggle value (PostgreSQL 't' for true, 'f' for false)
    }

    $updateSql = "UPDATE carro SET arrendado = $2 WHERE idcarro = $1";
    $paramsRented = array($car['idcarro'], $rented);
    $resultRented = pg_query_params($connection, $updateSql, $paramsRented);

    if ($result && $resultRented) {
        $_SESSION['success'] = "Reserva adicionada com sucesso!";
        $_SESSION['reservation_data']['saldo'] = $newSaldo;
        header('Location: user_reservations.php');
        exit();
    } else {
        $error = pg_last_error($connection);
        $_SESSION['error'] = "Erro ao inserir os dados.";
        header('Location: user_confirmReservation.php');
        exit();
    }
}