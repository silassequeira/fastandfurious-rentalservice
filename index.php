<?php
require 'checkSession.php';
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
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
            <input id="burger" type="checkbox">
            <label for="burger" class="active"><p>' . $userDetails['username'] . '</p><svg width="20" height="20" viewBox="0 0 684 484" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M42 42.031H642M42 242.03H642M42 442.03H642" stroke="#5A5A5A" stroke-width="83.3333"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg> </label>
      
             <nav class="nav-menu">
             <a class="biggerWeight" href="index.php">Home</a>
             <a href="user_reservations.php">Reservas</a>
             <a href="logout.php" class="redFont">Terminar Sessão</a>
             </nav>';
        } elseif (isset($_SESSION['admin'])) {
            header('Location: admin_visualizeAllCars.php');
            exit();
        } else {
            echo $str = '
            <div>
                <a class="button blackStrokeColor" href="register.php">Criar Conta</a>
                <a class="button redBackground whiteFont red-button" href="login.php">Login</a>
            </div>';
        }
        ?>
    </header>

    <main>
        <?php
        if (isset($_SESSION['errorIndex'])) {
            echo '<p class="marginFlex redFont">&#9888; ' . $_SESSION['errorIndex'] . '</p>';
            unset($_SESSION['errorIndex']);
        }
        ?>
        <div class="container redBackground maxWidth">
            <form method="POST" action="index_scriptForm.php">
                <h3 class="centered-marginTop whiteFont">Encontre as melhores ofertas para alugar carros</h3>

                <div class="infoFlex">
                    <div class="infoFlex column marginSides">
                        <label class="centered-marginTop whiteFont" for="datainicio">Data de Levantamento</label>
                        <input type="date" id="datainicio" class="input disableSelect" name="datainicio"
                            onkeydown="return false;" required>
                    </div>
                    <div class="infoFlex column marginSides">
                        <label class="centered-marginTop whiteFont" for="datafim">Data de Entrega</label>
                        <input type="date" id="datafim" class="input disableSelect" name="datafim"
                            onkeydown="return false;" required>
                    </div>
                </div>
                <input type="submit" class="button centered-marginTop redFont whiteBackground" name="submitDate"
                    value="Pesquisar" id="submitDate">
            </form>
        </div>
    </main>
    <script src="javascript/dateInputFormatter.js"></script>
</body>

</html>