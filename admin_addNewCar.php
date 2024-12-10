<?php
require 'checkSession.php';
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador Adicionar Carro</title>
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
        global $connection;

        if (isset($_SESSION['admin'])) {
            $adminDetails = $sessionCheck['details'];
            echo '<p>' . htmlspecialchars($adminDetails['username']) . '</p>';
            echo '<a href="admin_visualizeAllCars.php">Visualizar Carros</a>';
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
            <a href="admin_visualizeAllCars.php" class="back"> &lt; Voltar</a>
            <h2>Adicionar Carro</h2>
        </div>

        <?php require 'admin_addNewCar_script.php'; ?>
    </main>
    <script src="javascript/imagePreview.js"></script>
    <script src="javascript/enableSubmitButton.js"></script>
</body>

</html>