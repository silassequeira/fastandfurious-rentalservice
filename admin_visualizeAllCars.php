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
                </div>
                <div class="infoFlex">
                    <h6>Preço</h6>
                    <h5 value="price"></h5>
                    <button type="submit" name="modify">Alterar</button>
                    <button type="submit" name="history">Historico</button>
                    <button type="submit" name="hide">Ocultar</button>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
