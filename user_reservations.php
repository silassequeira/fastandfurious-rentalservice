<?php
require 'checkSession.php';
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrador</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Days+One:wght@400;600;700;900&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <a href="index.php" class="logo">
            <h4>Fast & Furious Cars Inc.</h4>
        </a>
        <?php
        global $sessionCheck;
        global $connection;
        if (isset($_SESSION['user'])) {
            $userDetails = $sessionCheck['details'];
            echo $str = '
            <input id="burger" type="checkbox">
            <label for="burger" class="active"><p>' . $userDetails['username'] . '</p><svg width="20" height="20" viewBox="0 0 684 484" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M42 42.031H642M42 242.03H642M42 442.03H642" stroke="#5A5A5A" stroke-width="83.3333"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg> </label>
      
             <nav class="nav-menu">
             <a href="index.php">Home</a>
             <a class="biggerWeight" href="user_reservations.php">Reservas</a>
             <a href="logout.php" class="redFont">Terminar Sessão</a>
             </nav>';
        } elseif (isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Só consegue aceder a esta página com as credenciais de cliente" . pg_last_error($connection);
            header('Location: admin_visualizeAllCars.php');
            exit();
        } else {
            $_SESSION['error'] = "Por favor, faça login para reservar um carro" . pg_last_error($connection);
            header('Location: index.php');
            exit();
        }
        ?>
    </header>

    <main>
        <div class="container maxWidth">

            <div class="infoFlex">
                <a href="index.php" class="back"> &lt; Voltar</a>
                <h2 class="marginSides">Reservas</h2>
            </div>

            <div class="layoutGridAutoFit">

                <?php
                $connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
                if (!$connection) {
                    die("Error connecting to the database");
                }

                if (isset($_SESSION['error'])) {
                    echo '<p style="color:red;">' . $_SESSION['error'] . '</p>';
                    unset($_SESSION['error']);
                }
                ?>
                <?php
                global $connection;

                $sessionCheck = checkSession($connection);
                $username = $sessionCheck['details']['username'];

                // Checks if the user submitted to cancel a reservation and updates the car status to available
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitCancel']) && isset($_POST['idreserva'])) {
                    $reservaId = $_POST['idreserva'];

                    // Fetch the car ID associated with the reservation
                    $carIdSql = "SELECT carro_idcarro FROM reserva WHERE idreserva = $1";
                    $carIdResult = pg_query_params($connection, $carIdSql, [$reservaId]);

                    if (!$carIdResult) {
                        die("Erro ao buscar carro_idcarro: " . pg_last_error($connection));
                    }

                    $carId = pg_fetch_result($carIdResult, 0, 0);

                    // Delete the reservation
                    $deleteSql = 'DELETE FROM reserva WHERE idreserva = $1';
                    $result = pg_query_params($connection, $deleteSql, [$reservaId]);

                    if (!$result) {
                        die("Erro ao eliminar reserva: " . pg_last_error($connection));
                    }

                    // Fetch the current rental status of the car
                    $selectSql = "SELECT arrendado FROM carro WHERE idcarro = $1";
                    $selectResult = pg_query_params($connection, $selectSql, [$carId]);

                    if (!$selectResult) {
                        die("Erro ao buscar status do carro: " . pg_last_error($connection));
                    }

                    $currentStatus = pg_fetch_result($selectResult, 0, 'arrendado');

                    // Determine the new rental status
                    if ($currentStatus === 't' && $_SESSION['countReservations'] > 1) {
                        $rented = 't';
                    } else {
                        $rented = 'f';
                    }

                    // Update the rental status of the car
                    $updateSql = "UPDATE carro SET arrendado = $2 WHERE idcarro = $1";
                    $paramsRented = array($carId, $rented);
                    $resultRented = pg_query_params($connection, $updateSql, $paramsRented);

                    if ($resultRented) {
                        $_SESSION['success'] = "Reserva removida com sucesso!";
                        header('Location: user_reservations.php');
                        exit();
                    } else {
                        $_SESSION['error'] = "Erro ao atualizar status do carro: " . pg_last_error($connection);
                        header('Location: user_reservations.php');
                        exit();
                    }
                }

                # Fetches all reservations from the user and displays them
                $sql = "SELECT * FROM reserva WHERE cliente_username = $1";
                $result = pg_query_params($connection, $sql, [$username]);

                if ($result) {
                    $reservas = pg_fetch_all($result);
                    $carReservationsCount = [];

                    foreach ($reservas as $index => $reserva) {
                        $carId = $reserva['carro_idcarro'];
                        $sql = "SELECT * FROM carro WHERE idcarro = $1";
                        $resultCar = pg_query_params($connection, $sql, [$carId]);

                        if (!$resultCar) {
                            die("Erro ao buscar dados do carro: " . pg_last_error($connection));
                        } else {
                            $car = pg_fetch_assoc($resultCar);
                        }

                        // Increment the count for the current carId
                        if (!isset($carReservationsCount[$carId])) {
                            $carReservationsCount[$carId] = 0;
                        }
                        $carReservationsCount[$carId]++;
                        $_SESSION['countReservations'] = $carReservationsCount[$carId] ?? 'error';

                        $str = '<div class="car-item">' .
                            '<div class="imgContainer">' .
                            '<img src="' . $car['foto'] . '" alt="Imagem do carro">' .
                            '</div>' .
                            '<h3>' . $car['marca'] . '</h3>' .
                            '<p>Modelo: ' . $car['modelo'] . '</p>' .
                            '<p>Ano: ' . $car['ano'] . '</p>' .
                            '<p>Assentos: ' . $car['assentos'] . '</p>' .
                            '<p>Preço Diário: ' . $car['valordiario'] . '</p>' .
                            '<p>Data de Levantamento: ' . $reserva['datainicio'] . '' .
                            '<p>Data de Entrega: ' . $reserva['datafim'] . '' .
                            '<p>Total: ' . $reserva['custototal'] . '</p>' .
                            '<p>Reservas para este carro: ' . $carReservationsCount[$carId] . '</p>' .
                            '<form method="POST">' .
                            '<input type="hidden" name="idreserva" value="' . $reserva['idreserva'] . '">' .
                            '<input type="submit" class="button centered-marginTop redFont whiteBackground redStrokeColor" name="submitCancel" value="Cancelar Reserva" id="submitCancel">' .
                            '</form>' .
                            '</div>';

                        echo $str;
                    }
                }

                ?>

            </div>
        </div>
    </main>
</body>

</html>