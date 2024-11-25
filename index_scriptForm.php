<?php
session_start();

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Error connecting to the database");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user']) {
    $datainicio = $_POST['datainicio'];
    $datafim = $_POST['datafim'];
    $user = $_SESSION['user'];

    if (empty($datainicio) || empty($datafim)) {
        $_SESSION['error'] = "Por favor, preencha as datas corretamente." . pg_last_error($connection);
        header('Location: index.php');
        exit();
    }

    $sql = "INSERT INTO reservas (datainicio, datafim, user_id)
                VALUES ($1, $2, (SELECT id FROM cliente WHERE username = $3 OR email = $3))";

    $resultUser = pg_query_params(
        $connection,
        $sql,
        array($datainicio, $datafim, $user)
    );

    if ($resultUser) {
        $_SESSION['success'] = "Data Inicio e Data fim registradas com sucesso!";
        header('Location: user_selectCar.php');
        exit();
    }

} else {
    $_SESSION['error'] = "A conta está registada como administrador " . pg_last_error($connection);
    header('Location: admin_visualizeAllCars.php');
    exit();
}


pg_close($connection);
