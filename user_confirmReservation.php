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
            <h2>Confirme a sua Reserva</h2>
        </div>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color:red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>

        <div class="centered-marginTop">
            <?php
            $car = $_SESSION['selected_car'] ?? null;
            $details = $_SESSION['reservation_data'] ?? null;

            $str = '<div class="car-item">' .
                '<div class="imgContainer">' .
                '<img src="' . $car['foto'] . '" alt="Imagem do carro">' .
                '</div>' .

                '<h3 class="centered-marginTop">' . $car['marca'] . ' ' . $car['modelo'] . '</h3>' .

                '<div class="infoFlex column">' .

                '<div class="infoFlex marginFlex ">' .
                '<div class="infoFlex column alignCenter">' .
                '<p>Marca</p>' .
                '<h4>' . $car['marca'] . '</h4>' .
                '</div>' .

                '<div class="infoFlex column alignCenter">' .
                '<p>Modelo</p>' .
                '<h4>' . $car['modelo'] . '</h4>' .
                '</div>' .
                '</div>' .

                '<div class="infoFlex marginFlex">' .
                '<div class="infoFlex column alignCenter">' .
                '<p>Ano</p>' .
                '<h4>' . $car['ano'] . '</h4>' .
                '</div>' .

                '<div class="infoFlex column alignCenter">' .
                '<p>Assentos</p>' .
                '<h4>' . $car['assentos'] . '</h4>' .
                '</div>' .
                '</div>' .

                '<div class="infoFlex marginFlex ">' .
                '<div class="infoFlex column alignCenter">' .
                '<p>Data de Levantamento</p>' .
                '<h4>' . $details['datainicio'] . '</h4>' .
                '</div>' .

                '<div class="infoFlex column alignCenter">' .
                '<p>Data de Entrega</p>' .
                '<h4>' . $details['datafim'] . '</h4>' .
                '</div>' .
                '</div>' .

                '<div class="infoFlex column alignCenter">' .
                '<p>Total</p>' .
                '<h4>' . $details['custototal'] . '€</p>' .
                '</div>' .

                '</div>' .

                '<form method="POST" action="user_confirmReservation_script.php">' .
                '<input type="hidden" name="car_id" value="' . $details['carro_idcarro'] . '">' .
                '<input type="submit" class="button centered-marginTop redFont whiteBackground" name="submitConfirmReservation" value="Confirmar" id="submitConfirmReservation">' .
                '</form>' .
                '</div>';

            echo $str;
            ?>

        </div>
    </main>