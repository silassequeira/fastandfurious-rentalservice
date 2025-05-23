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
        <a href="index.php" class="logo">
            <h4>Fast & Furious Cars Inc.</h4>
        </a>

        <?php
        global $sessionCheck;
        global $connection;

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
             <a class="biggerWeight" href="admin_addNewCar.php">Adicionar Carro</a>
             <a href="admin_stats.php">Ver Estatísticas</a>
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
            <?php require 'admin_addNewCar_script.php'; ?>
        </div>
    </main>
    <script src="javascript/imagePreview.js"></script>
    <script src="javascript/enableSubmitButton.js"></script>
</body>

</html>