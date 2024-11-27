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
        if (empty($datainicio) || empty($datafim)) {
            echo "Por favor, preencha as datas corretamente.";
            exit();
        }
        
        $idR=generateUniqueId('reserva_', 'id_reserva', $connection);
        $_SESSION['idR'] = $idR;
        $sql = "INSERT INTO reserva_ (id_reserva,datainicio, datafim, user_id)
                VALUES ($idR, $1, $2, (SELECT id FROM cliente WHERE username = $3 OR email = $3))";
        
        $result = pg_query_params(
            $connection, 
            $sql, 
            array($datainicio, $datafim, $user)
        );

    if ($resultUser) {
        $_SESSION['success'] = "Data Inicio e Data fim registradas com sucesso!";
        header('Location: user_selectCar.php');
        exit();
    }

}  else if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['admin']) {
    $_SESSION['error'] = "A conta está registada como administrador " . pg_last_error($connection);
    header('Location: logout.php');
    exit();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    $_SESSION['error'] = "Por favor, faça login para reservar um carro";
    header('Location: index.php');
    exit();
}

pg_close($connection);
