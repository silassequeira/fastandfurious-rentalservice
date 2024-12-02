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
            $reservationDetails = $_SESSION['reservation_data'];
            echo '<p>Saldo: ' . htmlspecialchars($userDetails['saldo'] . ' €') . '</p>';
            echo '<p>' . htmlspecialchars($userDetails['username']) . '</p>';
            echo '<p>' . htmlspecialchars($reservationDetails['id']) . '</p>';
            echo '<p>' . htmlspecialchars($reservationDetails['datainicio']) . '</p>';
            echo '<p>' . htmlspecialchars($reservationDetails['datafim']) . '</p>';
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
            <h2>Selecione o seu veículo</h2>
        </div>

        <div class="layoutGrid">
            <div class="infoFlex column">
                <div class="carContainer layoutGrid">
                    <?php
                    require 'viewAllCars.php';
                    if (!empty($cars)) {
                        foreach ($cars as $car) {
                            echo '<div class="car-item">';
                            echo '<img src="' . htmlspecialchars($car['foto']) . '" alt="Imagem do carro">';
                            echo '<h3>' . htmlspecialchars($car['marca']) . '</h3>';
                            echo '<p>Modelo: ' . htmlspecialchars($car['modelo']) . '</p>';
                            echo '<p>Ano: ' . htmlspecialchars($car['ano']) . '</p>';
                            echo '<p>Assentos: ' . htmlspecialchars($car['assentos']) . '</p>';
                            echo '<p>Preço Diário: R$ ' . htmlspecialchars($car['valordiario']) . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>Nenhum carro encontrado.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
        </div>

    </main>
</body>

</html>