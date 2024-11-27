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
    <php
        session_start();
        $user=$_SESSION['user'];
        >
    <header>
        <a href="#" class="logo">Fast & Furious Cars Inc.</a>

        <input id="burger" type="checkbox">
        <label for="burger" class="active">&#9776;</label>

        <nav>
            <a href="#" id="statistics">Ver Estatísticas</a>
            <label for="username-admin"> <php echo $user> </label>
        </nav>
    </header>

    <main>

        <div class="infoFlex">
            <a href="#" class="back"> &lt; Voltar</a>
            <h2>Estatísticas</h2>
        </div>

        <form>
            <label for="date">Data</label>
            <input type="date" name="date" id="date">

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
                <label for="two-doors">2 Portas</label>
                <input type="radio" name="two-doors" value="two-doors">

                <label for="four-doors">4 Portas</label>
                <input type="radio" name="four-doors" value="four-doors">

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
         <?php
    $str = "dbname=postgres user=postgres password=postgres host=localhost port=5432";
    $connection = pg_connect($str);

    if (!$connection) {
        die("Erro na conexão");
    }
    // get informação
    $name = $_GET['car-name'];
    $brand = $_GET['brand'];
    $model = $_GET['model'];
    $seats = $_GET['seats'];
    $year = $_GET['year'];
    $price = $_GET['price'];

    //criar um id
    $id=generateUniqueId('carro', 'id_carro');
    //adicionar carro á base de dados
    $sql = "INSERT INTO carro (id_carro, marca, modelo,ano,assentos,valordiario,administrador_username) VALUES ('$id', '$marca', '$model', '$year', '$seats', '$price', '$user')";
    $result = pg_query($connection, $sql);
    ?>
    </main>
</body>

</html>
