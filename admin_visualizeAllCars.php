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
        <a href="index.php" class="logo">Fast & Furious Cars Inc.</a>

        <?php
        global $sessionCheck;
        global $connection;
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
        <form action="admin_visualizeAllCars.php" method="post">
            <input type="submit" name="deleteUsers" value="Delete Users">
        </form>

        <?php
        $connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");


        if (isset($_POST['deleteUsers'])) {
            // SQL command to delete all rows in the "cliente" table
            $sql = "DELETE FROM cliente";

            $result = pg_query($connection, $sql);

            if (!$result) {
                die("Error deleting rows: " . pg_last_error($connection));
            } else {
                echo "All rows in the 'cliente' table have been deleted successfully.";
            }

        }
        pg_close($connection);

        ?>
        <div class="layoutGrid">
            <?php
            require 'viewAllCars.php';
            ?>
        </div>
    </main>
</body>

</html>