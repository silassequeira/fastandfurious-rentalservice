<?php
include 'checkSession.php';
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


<style>
    .date-input-container {
        max-width: 300px;
        margin: 20px auto;
        position: relative;
    }

    .date-input {
        width: 100%;
        padding: 10px 40px 10px 10px;
        border: 2px solid #3498db;
        border-radius: 8px;
        font-size: 16px;
        outline: none;
        transition: all 0.3s ease;
    }

    .date-input:focus {
        border-color: #2980b9;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
    }

    .date-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #7f8c8d;
    }
</style>

<body>
    <header>
        <a href="index.php" class="logo">Fast & Furious Cars Inc.</a>

        <?php
        if ($sessionCheck) {
            if ($sessionCheck['type'] === 'user') {
                $userDetails = $sessionCheck['details'];
                echo '<p>Saldo: ' . htmlspecialchars($userDetails['saldo'] . ' €') . '</p>';
                echo '<p>' . htmlspecialchars($userDetails['username']) . '</p>';
                echo '<a href="logout.php">Terminar Sessão</a>';
            } elseif ($sessionCheck['type'] === 'admin') {
                header('Location: admin_visualizeAllCars.php');
                exit();
            }
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
            <form method="GET" action="index_scriptForm.php">
                <h2 class="centered-marginTop whiteFont">Encontre as melhores ofertas para alugar carros</h2>

                <div class="infoFlex">
                    <div class="infoFlex column">
                        <h5 class="centered-marginTop whiteFont">Data de Levantamento</h5>
                        <button class="date-input-btn input" name="datainicio">Pick a date</button>
                        <label for="datainicio" id="datainicio"></label>
                    </div>
                    <div class="infoFlex column">
                        <h5 class="centered-marginTop whiteFont">Data de Entrega</h5>
                        <div class="input-container">
                            <input
                                type="date"
                                id="localDate"
                                class="input"
                                max="">
                        </div>
                    </div>
                </div>

                <button class="button centered-marginTop redFont whiteBackground" type="submit"
                    id="buttonSearch">Pesquisar</button>
            </form>
        </div>
    </main>
    <script src="javascript/switchToDateInput.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dateInput = document.getElementById('localDate');

            // Set max date to today's date
            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('max', today);

            // Set default value to today
            dateInput.value = today;

            dateInput.addEventListener('change', (e) => {
                console.log('Selected date:', e.target.value);
            });
        });
    </script>
</body>

</html>