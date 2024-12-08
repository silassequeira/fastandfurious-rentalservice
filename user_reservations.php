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
        <a href="#" class="logo">Fast & Furious Cars Inc.</a>

        <?php
        global $sessionCheck;
        global $connection;
        if (isset($_SESSION['user'])) {
            $userDetails = $sessionCheck['details'];
            echo '<p>Saldo: ' . htmlspecialchars($userDetails['saldo'] . ' €') . '</p>';
            echo '<p>' . htmlspecialchars($userDetails['username']) . '</p>';
            echo '<a href="logout.php">Terminar Sessão</a>';
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
        <div class="infoFlex">
            <a href="index.php" class="back"> &lt; Voltar</a>
            <h2>Reservas</h2>
        </div>

        <div class="layoutGrid">

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

            <div class="infoFlex">
                <?php
                global $connection;

                $sessionCheck = checkSession($connection);
                $username = $sessionCheck['details']['username'];


                $sql = "SELECT * FROM reserva WHERE cliente_username = $1";
                $result = pg_query_params($connection, $sql, [$username]);

                if ($result) {
                    $reservas = pg_fetch_all($result);



                    foreach ($reservas as $index => $reserva) {
                        $carId = $reserva['carro_idcarro'];
                        $sql = "SELECT * FROM carro WHERE idcarro = $carId";
                        $result = pg_query($connection, $sql);

                        if (!$result) {
                            die("Erro ao buscar dados do carro: " . pg_last_error($connection));
                        } else {
                            $car = pg_fetch_assoc($result);
                        }

                        $str = '<div class="car-item">' .
                            '<img src="' . $car['foto'] . '" alt="Imagem do carro">' .
                            '<h3>' . $car['marca'] . '</h3>' .
                            '<p>Modelo: ' . $car['modelo'] . '</p>' .
                            '<p>Ano: ' . $car['ano'] . '</p>' .
                            '<p>Assentos: ' . $car['assentos'] . '</p>' .
                            '<p>Preço Diário: ' . $car['valordiario'] . '</p>' .
                            '<p>Data de Levantamento: ' . $reserva['datainicio'] . '' .
                            '<p>Data de Entrega: ' . $reserva['datafim'] . '' .
                            '<p>Total: ' . $reserva['custototal'] . '</p>' .
                            '<form method="POST">' .
                            '<input type="hidden" name="idreserva" value="' . $reserva['idreserva'] . '">' .
                            '<input type="submit" class="button centered-marginTop redFont whiteBackground" name="submitCancel" value="Cancelar Reserva" id="submitCancel">' .
                            '</form>' .
                            '</div>';

                        echo $str;

                    }
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitCancel']) && isset($_POST['idreserva'])) {
                    $reservaId = $_POST['idreserva'];
                    $deleteSql = 'DELETE FROM reserva WHERE idreserva = $1';
                    $result = pg_query_params($connection, $deleteSql, [$reservaId]);

                    $selectSql = "SELECT arrendado FROM carro WHERE idcarro = $1";
                    $selectResult = pg_query_params($connection, $selectSql, array($car['idcarro']));

                    if (!$selectResult) {
                        die("Erro ao buscar status do carro: " . pg_last_error($connection));
                    }

                    $currentStatus = pg_fetch_result($selectResult, 0, 'arrendado');
                    $rented = $currentStatus === 't' ? 'f' : 't'; // Toggle value (PostgreSQL 't' for true, 'f' for false)
                
                    // Update 'arrendado' to its new value
                    $updateSql = "UPDATE carro SET arrendado = $2 WHERE idcarro = $1";
                    $paramsRented = array($car['idcarro'], $rented);
                    $resultRented = pg_query_params($connection, $updateSql, $paramsRented);

                    if ($result && $resultRented) {
                        $_SESSION['success'] = "Reserva removida com sucesso!";
                        header('Location: user_reservations.php');
                        exit();
                    } else {
                        $_SESSION['error'] = "Erro ao eliminar reserva: " . pg_last_error($connection);
                        header('Location: user_reservations.php');
                        exit();
                    }

                }

                ?>

            </div>
        </div>
    </main>
</body>

</html>