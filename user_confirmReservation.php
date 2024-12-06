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



        <div class="layoutGrid">
            <div class="infoFlex">
                <?php
                $car = $_SESSION['selected_car'] ?? null;
                $details = $_SESSION['reservation_data'] ?? null;

                $str = '<div class="car-item">';

                if (isset($_SESSION['error'])) {
                    echo '<p style="color:red;">' . $_SESSION['error'] . '</p>';
                    unset($_SESSION['error']);
                }
                
                $str .= '<img src="' . $car['foto'] . '" alt="Imagem do carro">' .
                    '<h3>' . $car['marca'] . '</h3>' .
                    '<p>Modelo: ' . $car['modelo'] . '</p>' .
                    '<p>Ano: ' . $car['ano'] . '</p>' .
                    '<p>Assentos: ' . $car['assentos'] . '</p>' .
                    '<p>Preço Diário: ' . $car['valordiario'] . '</p>' .
                    '<p>id reserva: ' . $details['id'] . '' .
                    '<p>Data de Levantamento: ' . $details['datainicio'] . '' .
                    '<p>Data de Entrega: ' . $details['datafim'] . '' .
                    '<p>id carro: ' . $details['carro_idcarro'] . '' .
                    '<p>Total: ' . $details['custototal'] . '</p>' .
                    '<form method="POST" action="user_confirmReservation_script.php">' .
                    '<input type="hidden" name="car_id" value="' . $details['carro_idcarro'] . '">' .
                    '<input type="submit" class="button centered-marginTop redFont whiteBackground" name="submitConfirmReservation" value="Confirmar" id="submitConfirmReservation">' .
                    '</form>' .
                    '</div>';

                echo $str;
                ?>

                <?php

                ?>

            </div>
        </div>
    </main>