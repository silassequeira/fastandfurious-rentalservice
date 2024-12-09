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
        <a href="index.php" class="logo">Fast & Furious Cars Inc.</a>

        <?php
        global $sessionCheck;
        if (isset($_SESSION['user'])) {
            $userDetails = $sessionCheck['details'];
            echo '<p>Saldo: ' . $userDetails['saldo'] . ' €' . '</p>';
            echo '<p>' . $userDetails['username'] . '</p>';
            echo '<a href="user_reservations.php">Reservas</a>';
            echo '<a href="logout.php">Terminar Sessão</a>';
        } elseif (isset($_SESSION['admin'])) {
            header('Location: admin_visualizeAllCars.php');
            exit();
        } else {
            echo $str = '
            <nav>
                <a class="button" href="register.php">Criar Conta</a>
                <a class="button redBackground whiteFont" href="login.php">Login</a>
            </nav>';
        }
        ?>
    </header>

    <main>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color:red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <div class="container redBackground">
            <form method="POST" action="index_scriptForm.php">
                <h3 class="centered-marginTop whiteFont">Encontre as melhores ofertas para alugar carros</h3>

                <div class="infoFlex">
                    <div class="infoFlex column marginSides">
                        <label class="centered-marginTop whiteFont" for="datainicio">Data de Levantamento</label>
                        <input type="date" id="datainicio" class="input disableSelect" name="datainicio" onkeydown="return false;" required>
                    </div>
                    <div class="infoFlex column marginSides">
                        <label class="centered-marginTop whiteFont" for="datafim">Data de Entrega</label>
                        <input type="date" id="datafim" class="input disableSelect" name="datafim" onkeydown="return false;" required>
                    </div>
                </div>
                <input type="submit" class="button centered-marginTop redFont whiteBackground" name="submitDate" value="Pesquisar" id="submitDate">
            </form>
        </div>
    </main>
    <script src="javascript/dateInputFormatter.js"></script>
</body>

</html>