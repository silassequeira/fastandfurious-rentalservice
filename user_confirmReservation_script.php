<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Establish database connection
$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    error_log("Database connection failed: " . pg_last_error());
    $_SESSION['error'] = "Erro na conexão com o banco de dados.";
    header('Location: user_confirmReservation.php');
    exit();
}

// Retrieve session variables
$car = $_SESSION['selected_car'] ?? null;
$details = $_SESSION['reservation_data'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitConfirmReservation'])) {
    if (!$car || !$details) {
        error_log("Missing session data: " . json_encode(['car' => $car, 'details' => $details]));
        $_SESSION['error'] = "Dados de sessão ausentes.";
        header('Location: user_confirmReservation.php');
        exit();
    }

    if (!isset($details['saldo'], $details['custototal']) || $details['saldo'] < $details['custototal']) {
        $_SESSION['error'] = "Saldo insuficiente ou dados inválidos.";
        header('Location: user_confirmReservation.php');
        exit();
    }

    $newSaldo = $details['saldo'] - $details['custototal'];
    $sqlUpdateSaldo = "UPDATE cliente SET saldo = $1 WHERE username = $2";
    $paramsUpdateSaldo = [$newSaldo, $details['cliente_username']];

    error_log("Updating saldo with query: $sqlUpdateSaldo");
    error_log("Parameters: " . json_encode($paramsUpdateSaldo));

    $resultUpdateSaldo = pg_query_params($connection, $sqlUpdateSaldo, $paramsUpdateSaldo);

    if (!$resultUpdateSaldo) {
        error_log("Saldo update failed: " . pg_last_error());
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

    error_log("Executing reservation query: $sql");
    error_log("Parameters: " . json_encode($params));

    $result = pg_query_params($connection, $sql, $params);

    if ($result) {
        $_SESSION['success'] = "Reserva adicionada com sucesso!";
        $_SESSION['reservation_data']['saldo'] = $newSaldo;
        header('Location: user_reservations.php');
        exit();
    } else {
        $error = pg_last_error($connection);
        error_log("Insert query failed: " . $error);
        $_SESSION['error'] = "Erro ao inserir os dados.";
        header('Location: user_confirmReservation.php');
        exit();
    }
}
