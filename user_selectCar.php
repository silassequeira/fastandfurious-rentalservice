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
$user = $_SESSION['user'];
$str = "dbname=postgres user=postgres password=postgres host=localhost port=5432";
        
$connection = pg_connect($str);
if (!$connection) {
    die("Erro na conexão");
}
        
$saldo = pg_query($connection, "SELECT saldo FROM cliente WHERE username='$user'");
$idR = $_SESSION['idR'];

>
    <header>
        <a href="#" class="logo">Fast & Furious Cars Inc.</a>

        <nav>
            <label for="saldo" id="saldo">Saldo:<php echo $saldo></label>
            <a href="#" id="reservas">Gerir Reservas</a>
            <label for="username"><php echo $user></label>
        </nav>
    </header>

    <main>

        <div class="infoFlex">
            <a href="#" class="back"> &lt; Voltar</a>
            <h2>Selecione o seu veículo</h2>
        </div>

        <div class="layoutGrid">
            <div class="infoFlex column">
                <h5>Filtros</h5>
                <h6>Preço Total</h6>
                <div class="infoFlex">
                    <label for="min-price">Preço Mínimo</label>
                    <input type="number" id="min-price" name="min-price" placeholder="Preço Mínimo">
                    <label for="max-price">Preço Máximo</label>
                    <input type="number" id="max-price" name="max-price" placeholder="Preço Máximo">
                </div>
                <h6>Características</h6>
                <label for="car-brand">Marca</label>
                <select name="car-brand" required>
                    <option value="brand"></option>
                    <option value="brand"></option>
                    <option value="brand"></option>
                    <option value="brand"></option>
                </select><br>
                <label for="car-seats">Números de Lugares</label>
                <select name="car-seats" required>
                    <option value="seats"></option>
                    <option value="seats"></option>
                    <option value="seats"></option>
                    <option value="seats"></option>
                </select><br>

                <label for="two-doors">2 Portas</label>
                <input type="radio" name="two-doors" value="two-doors">

                <label for="four-doors">4 Portas</label>
                <input type="radio" name="four-doors" value="four-doors">

                <button type="submit" name="filter">Filtrar</button>
            </div>

        <?php
        $sqrMinPrice= 'where valordiario > 0 AND';
        $sqrMaxPrice= 'valordiario < 100000';
        $sqrBrand= '';
        $sqrSeats= '';
        if (isset($_GET['min-price']){
            $minprice = $_GET['min-price'];
            $sqrMinPrice = 'WHERE valordiario > ' . $minprice . ' AND ';
        }
        if (isset($_GET['max-price']){
            $maxprice = $_GET['max-price'];
            $sqrMaxPrice= 'valordiario < ' . $maxprice;
        }
        if (isset($_GET['car-brand']){
            $brand = $_GET['car-brand'];
            $sqrBrand= 'marca LIKE ' . "'" . $brand . "' AND ";
        }
        if (isset($_GET['seats']){
            $seats = $_GET['seats'];
            $sqrSeats= 'assentos = ' . $seats . ' AND ';
        }

       $sqrFiltro= 'SELECT * FROM carro ' . $sqrMinPrice . $sqrBrand . $sqrSeats .  $sqrMaxPrice . 'AND ocultado = FALSE';
       $ID_filtro = pg_query($connection, $sqrFiltr);
       
       if ($ID_filtro) {
        foreach (pg_fetch_all($ID_filtro) as $row) {
    ?>
        <div class="infoFlex column">
            <div class="carContainer layoutGrid">
                <div class="imgContainer">
                    <img src="#" alt="img-alt">
                </div>
                <div class="infoFlex">
                    <h3><?php echo htmlspecialchars($row['nome']); ?></h3>
                    <h6>Ano</h6>
                    <h5><?php echo htmlspecialchars($row['ano']); ?></h5>
                    <h6>Numero de Lugares</h6>
                    <h5><?php echo htmlspecialchars($row['assentos']); ?></h5>
                    <h6>Marca</h6>
                    <h5><?php echo htmlspecialchars($row['marca']); ?></h5>
                    <h6>Modelo</h6>
                    <h5><?php echo htmlspecialchars($row['modelo']); ?></h5>
                </div>
                <div class="infoFlex">
                    <h6>Preço</h6>
                    <h5><?php echo htmlspecialchars($row['valordiario']); ?></h5>
                    <button type="submit" name="rent">Reservar</button>
                </div>
            </div>
        </div>
    <?php 
        }
    } else {
        echo "Erro na consulta: " . pg_last_error($connection);
    }
    ?>
    </main> 
</body>

</html>
