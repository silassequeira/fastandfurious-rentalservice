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
        if (isset($_SESSION['admin'])) {
            $adminDetails = $sessionCheck['details'];
            echo '<p>' . htmlspecialchars($adminDetails['username']) . '</p>';
            echo '<a href="admin_addNewCar.php">Adicionar Carro</a>';
            echo '<a href="admin_stats.php">Ver Estatísticas</a>';
            echo '<a href="logout.php">Terminar Sessão</a>';
        } else {
            $_SESSION['error'] = "Sem permissões suficientes para acessar esta página" . pg_last_error($connection);
            header('Location: logout.php');
            exit();
        }
        ?>
    </header>

    <main>

        <div class="infoFlex">
            <a href="#" class="back"> &gt; Adicionar Carro </a>
        </div>


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
                <button type="submit" name="modify">Alterar</button>
                <button type="submit" name="history">Historico</button>
                <button type="submit" name="hide">Ocultar</button>
            </div>
        </div>
        </div>
    </main>
</body>

</html>