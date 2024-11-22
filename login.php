<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User</title>
    <link rel="stylesheet" href="style.css">
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
        <div class="container">
            <form id="loginForm" method="POST" action="login_scriptForm.php">
                <h2>Login de conta Pessoal</h2>
                <div class="inputContainer">
                    <label for="username">Username ou E-mail</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="inputContainer">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="buttonContainer">
                    <a href="register.php">Criar Conta</a>
                    <input type="submit" name="submitLogin" value="Iniciar Sessão" id="submitLogin" disabled>
                </div>
            </form>
        </div>
    </main>
    <script src="javascript/enableSubmitButton.js"></script>
</body>

</html>