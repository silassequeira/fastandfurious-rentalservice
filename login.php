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
    <main class="centered-marginTop ">
        <div class="container">
            <form id="loginForm" method="POST" action="login_scriptForm.php">
                <h4>Login para aceder a Conta</h4>

                <div class="infoFlex column marginTop gap">
                    <span class="reference">
                        <label for="username">Username ou E-mail</label>
                        <input type="text" id="username" name="username" required>
                    </span>
                    <span class="reference">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </span>
                    <div class="infoFlex noMargin">
                        <span>
                            <a href="register.php">Criar Conta</a>
                        </span>
                        <span>
                            <input type="submit" class="noPadding" name="submitLogin" value="Iniciar Sessão" id="submitLogin" disabled>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <script src="javascript/enableSubmitButton.js"></script>
</body>

</html>