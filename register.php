<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar Utilizador</title>
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
        if (isset($_SESSION['error'])) {
            echo '<p style="color:red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['success'])) {
            echo '<p style="color:green;">' . $_SESSION['success'] . '</p>';
            unset($_SESSION['success']);
        }
        ?>
        
    </header>
    <main>
        <a href="index.php" class="back"> &lt; Voltar</a>
        <div class="container">
            <form method="POST" action="register_scriptForm.php">
                <h2>Regista a tua Conta</h2>
                <div class="inputContainer">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="inputContainer">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="inputContainer">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="inputContainer">
                    <label for="name">Nome Próprio</label>
                    <input type="text" id="name" name="name">
                </div>
                <div class="buttonContainer">
                    <input type="submit" name="submitRegister" value="Registar" id="submitRegister" disabled>
                </div>
            </form>
        </div>
    </main>
    <script src="javascript/enableSubmitButton.js"></script>
</body>

</html>