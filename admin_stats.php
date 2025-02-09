<?php
require 'checkSession.php';
require 'admin_stats_script.php';
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador Estatísticas</title>
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
        if (isset($_SESSION['admin'])) {
            $adminDetails = $sessionCheck['details'];
            echo $str = '
            <input id="burger" type="checkbox">
            <label for="burger" class="active"><p>' . $adminDetails['username'] . '</p><svg width="20" height="20" viewBox="0 0 684 484" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M42 42.031H642M42 242.03H642M42 442.03H642" stroke="#5A5A5A" stroke-width="83.3333"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg> </label>
      
             <nav class="nav-menu">
             <a href="index.php">Home</a>
             <a href="admin_addNewCar.php">Adicionar Carro</a>
             <a class="biggerWeight" href="admin_stats.php">Ver Estatísticas</a>
             <a href="logout.php" class="redFont">Terminar Sessão</a>
             </nav>';
        } else {
            $_SESSION['error'] = "Sem permissões suficientes para acessar esta página" . pg_last_error($connection);
            header('Location: logout.php');
            exit();
        }
        ?>

    </header>

    <main>
        <div class="container">
            <div class="infoFlex">
                <a href="admin_visualizeAllCars.php" class="back"> &lt; Voltar</a>
                <h2>Estatísticas</h2>
            </div>

            <div class="layoutGrid marginTop">
                <div class=" borderAround infoFlex column alignCenter maxWidth">
                    <p>Número Total de Carros</p>
                    <h5 value="total-cars"><?php echo $NumeroCarros; ?></h5>
                </div>
                <div class=" borderAround infoFlex column alignCenter maxWidth">
                    <p>Carros Disponíveis</p>
                    <h5 value="available-cars"><?php echo $NumeroCarrosDisponiveis; ?></h5>
                </div>
                <div class=" borderAround infoFlex column alignCenter maxWidth">
                    <p>Número Total de Reservas</p>
                    <h5 value="rented-cars"><?php echo $NumeroReservas; ?></h5>
                </div>
                <div class=" borderAround infoFlex column alignCenter maxWidth">
                    <p>Número Médio de Reservas por Utilizador</p>
                    <h5 value="averageRented-cars"><?php echo $MediaReservaUtilizador; ?></h5>
                </div>

                <div class=" borderAround infoFlex column alignCenter maxWidth">
                    <p>Número Total de Utilizadores</p>
                    <h5 value="total-users"><?php echo $NumeroUtilizadores; ?></h5>
                </div>
                <div class=" borderAround infoFlex column alignCenter maxWidth">
                    <p>Utilizadores que Reservaram</p>
                    <h5 value="thatRent-users"><?php echo $UtilizadoresQueReservaram; ?></h5>
                </div>
            </div>
        </div>
    </main>
</body>

</html>