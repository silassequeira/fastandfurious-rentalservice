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

        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color:red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        
        <div class="infoFlex">
            <a href="#" class="back"> &lt; Voltar</a>
            <h2>Adicionar Carro</h2>
        </div>

        <form method="POST" action="admin_addNewCar_scriptForm.php">
            <div class="layoutGrid">

                <div class="infoFlex column">
                    <div class="inputContainer">
                        <label for="foto">Adicionar Nova Imagem do Veículo</label>
                        <input type="file" id="foto" accept="image/png, image/jpg, image/jpeg" name="foto" required>
                        <div id="preview-container">
                            <p>No image selected</p>
                        </div>
                        <div id="error-message" style="color: red;"></div>
                    </div>
                    <div class="inputContainer">
                        <label for="marca">Marca</label>
                        <input type="text" id="marca" name="marca" required>
                    </div>
                    <div class="inputContainer">
                        <label for="modelo">Modelo</label>
                        <input type="text" id="modelo" name="modelo" required>
                    </div>
                    <div class="inputContainer">
                        <label for="ano">Ano</label>
                        <input type="number" id="ano" name="ano" min="1900" max="2100" required>
                    </div>
                    <div class="inputContainer">
                        <label for="assentos">Números de Lugares</label>
                        <select id="assentos" name="assentos" required>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="inputContainer">
                        <label for="valordiario">Preço Diário</label>
                        <input type="text" id="valordiario" name="valordiario" required>
                    </div>

                    <div class="buttonContainer">
                        <input type="submit" name="submitNewCar" value="Guardar" id="submitNewCar" disabled>
                    </div>
                </div>
        </form>
    </main>
    <script src="javascript/imagePreview.js"></script>
    <script src="javascript/enableSubmitButton.js"></script>
</body>

</html>