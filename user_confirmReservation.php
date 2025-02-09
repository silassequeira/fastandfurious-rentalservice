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
        if (isset($_SESSION['user'])) {
            $userDetails = $sessionCheck['details'];
            echo $str = '
            <p class="borderAround">Saldo: ' . $userDetails['saldo'] . ' €' . '</p>
            <input id="burger" type="checkbox">
            <label for="burger" class="active"><p>' . $userDetails['username'] . '</p><svg width="20" height="20" viewBox="0 0 684 484" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M42 42.031H642M42 242.03H642M42 442.03H642" stroke="#5A5A5A" stroke-width="83.3333"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg> </label>
      
             <nav class="nav-menu">
             <a href="index.php">Home</a>
             <a href="user_reservations.php">Reservas</a>
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
                <a href="user_selectCar.php" class="back"> &lt; Voltar</a>
                <h2>Confirme a sua Reserva</h2>
            </div>

            <?php
            if (isset($_SESSION['error'])) {
                echo '<p style="color:red;">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
            ?>

            <div class="centered-marginTop layoutGridAutoFit maxWidth">
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
                    '<div class="infoFlex column alignCenter marginRight">' .
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
                    '<input type="submit" class="button centered-marginTop redFont whiteBackground redStrokeColor" name="submitConfirmReservation" value="Confirmar" id="submitConfirmReservation">' .
                    '</form>' .
                    '</div>';

                echo $str;
                ?>

            </div>
        </div>
    </main>