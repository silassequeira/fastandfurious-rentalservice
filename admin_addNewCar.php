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

        <input id="burger" type="checkbox">
        <label for="burger" class="active">&#9776;</label>

        <nav>
            <a href="#" id="statistics">Add New Car</a>
            <label for="username-admin">
                <php echo $user>
            </label>
        </nav>
    </header>

    <main>

        <div class="infoFlex">
            <a href="#" class="back"> &lt; Voltar</a>
            <h2>Estatísticas</h2>
        </div>

        <form method="GET" action="admin_addNewCar_scriptForm">
            <div class="layoutGrid">
                <div class="infoFlex column">
                    <input type="image" alt="image">
                </div>

                <div class="inputContainer">
                    <label for="car-name">Veículo</label>
                    <input type="text" id="car-name" name="car-name">
                </div>
                <div class="inputContainer">
                    <label for="brand">Marca</label>
                    <input type="text" id="brand" name="brand">
                </div>
                <div class="inputContainer">
                    <label for="model">Modelo</label>
                    <input type="text" id="model" name="model">
                </div>
                <div class="inputContainer">
                    <label for="seats">Números de Lugares</label>
                    <select id="model" name="car-seats" required>
                        <option value="seats">2</option>
                        <option value="seats">3</option>
                        <option value="seats">4</option>
                        <option value="seats">5</option>
                    </select><br>
                </div>
                <div class="inputContainer">
                    <label for="year">Ano</label>
                    <input type="text" id="year" name="year">
                </div>

                <div class="inputContainer">
                    <label for="price">Preço Diário</label>
                    <input type="text" id="price" name="price">
                </div>

                <div class="buttonContainer">
                    <label for="submit">Submeter</label>
                    <button type="submit" name="submit" id="submit">Submeter</button>
                </div>
            </div>
        </form>
    </main>
</body>

</html>