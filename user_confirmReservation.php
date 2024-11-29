
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
            header('Location: admin_visualizeAllCars.php');
            exit();
        } else {
            $_SESSION['error'] = "Por favor, faça login para reservar um carro" . pg_last_error($connection);
            header('Location: register.php');
            exit();
        }
        ?>
    </header>

    <main>
        <div class="infoFlex">
            <a href="#" class="back"> &lt; Voltar</a>
            <h2>Confirme a sua Reserva</h2>
        </div>

        <div class="layoutGrid">
            <div class="imgContainer">
                <img src="#" alt="img-alt">
            </div>
            <div class="infoFlex">
                <h3 value="car-name"></h3>
                <h6>Ano</h6>
                <h5 value="car-year"></h5>
                <h6>Numero de Lugares</h6>
                <h5 value="seats"></h5>
                <h6>Marca</h6>
                <h5 value="brand"></h5>
                <h6>Modelo</h6>
                <h5 value="model"></h5>
                <p>Data de Levantamento</p>
                <input type="date" id="pickupDate" name="pickupDate">
                <label for="pickupDate" placeholder="26/10/2024"></label>
                <p>Hora</p>
                <input type="date" id="pickupHour" name="pickupHour">
                <label for="pickupHour" placeholder="22:00"></label>
                <p>Data de Entrega</p>
                <input type="date" id="dropDate" name="dropDate">
                <label for="dropDate" placeholder="29/10/2024"></label>
                <p>Data de Levantamento</p>
                <input type="date" id="dropHour" name="dropHour">
                <label for="dropHour" placeholder="14:30"></label>
                <h4 value="total-price">Preço Total:</h4>
                <button type="submit" id="payment">Pagamento</button>
            </div>
        </div>
    </main>