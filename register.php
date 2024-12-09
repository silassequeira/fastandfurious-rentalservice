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
    </header>

    <main class="centered-marginTop ">
        <div class="container">
            <form id="loginForm" method="POST" action="register_scriptForm.php">
                <h4>Regista a tua Conta</h4>

                <div class="infoFlex column marginTop gap">
                    <?php
                    if (isset($_SESSION['errorRegister'])) {
                        echo '<p class="redFont">&#9888; ' . $_SESSION['errorRegister'] . '</p>';
                        unset($_SESSION['errorRegister']);
                    }
                    ?>
                    <span class="reference">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
                    </span>
                    <span class="reference">
                        <label for="email">E-mail</label>
                        <input type="text" id="email" name="email" required>
                    </span>
                    <span class="reference">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </span>
                    <span class="reference">
                        <label for="name">Nome Próprio</label>
                        <input type="text" id="name" name="name" required>
                    </span>
                    <div class="infoFlex noMargin">
                        <span>
                            <a class="text-light underline" href="login.php">Já tenho Conta - Log in</a>
                        </span>
                        <span>
                            <input type="submit" class="noPadding" name="submitRegister" value="Registar" id="submitRegister" disabled>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <script src="javascript/enableSubmitButton.js"></script>
</body>

</html>