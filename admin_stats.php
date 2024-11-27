<?php
$str = "dbname=postgres user=postgres password=postgres host=localhost port=5432";
$connection = pg_connect($str);

if (!$connection) {
    die("Error Connecting to Database");
}

//pesquisas a base de dados------------
//numero de carros
$sqlNc = "SELECT count(*) FROM carro";
$result = pg_query($connection, $sqlNc);

if (!$result) {
    die("Error executing query");
}

// numero de carros disponiveis
$sqlNcd = "SELECT count(*) FROM carro WHERE status_ = true";
$result1 = pg_query($connection, $sqlNcd);

if (!$result1) {
    die("Error executing query");
}

// numero de reservas
$sqlNcr = "SELECT count(*) FROM reserva_";
$result2 = pg_query($connection, $sqlNcr);

if (!$result2) {
    die("Error executing query");
}

//numero de utilizadores
$sqlNcu = "SELECT count(*) FROM cliente";
$result3 = pg_query($connection, $sqlNcu);

if (!$result3) {
    die("Error executing query");
}

//numero de utilizadores que já fizeram reservas
$sqlNcur = "SELECT COUNT(DISTINCT cliente_username) FROM reserva_";
$result4 = pg_query($connection, $sqlNcur);

if (!$result4) {
    die("Error executing query");
}


// Extrai o valor do resultado------------------
$NumeroCarros = pg_fetch_result($result, 0 );
$NumeroCarrosDisponiveis = pg_fetch_result($result1, 0);
$NumeroReservas = pg_fetch_result($result2, 0);
$NumeroUtilizadores = pg_fetch_result($result3, 0);
$MediaReservaUtilizador= $NumeroReservas / $NumeroUtilizadores;
$UtilizadoresQueReservaram = pg_fetch_result($result4, 0);


// Fecha a conexão com o banco de dados
pg_close($connection);
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
    <?php
        session_start();
        $user=$_SESSION['user'];
        ?>
    <header>
        <a href="index.html" class="logo">Fast & Furious Cars Inc.</a>

        <nav>
            <label for="username-admin"><php echo $user></label>
        </nav>
    </header>

    <main>

        <div class="infoFlex">
            <a href="#" class="back"> &lt; Voltar</a>
            <h2>Estatísticas</h2>
        </div>

            <label for="date">Data</label>
            <input type="date" name="date" id="date">

            <div class="layoutGrid">
                <div class="infoFlex column">
                    <div class="container">
                        <h5>Número Total de Carros</h5>
                        <h5 value="total-cars"><?php echo $NumeroCarros; ?></h5>
                    </div>
                    <div class="container">
                        <h5>Carros Disponíveis</h5>
                        <h5 value="available-cars"><?phpphp echo $NumeroCarrosDisponiveis;?></h5>
                    </div>
                </div>
                <div class="infoFlex column">
                    <div class="container">
                        <h5>Número Total de Reservas</h5>
                        <h5 value="rented-cars"><?php echo $NumeroReservas;?></h5>
                    </div>
                    <div class="container">
                        <h5>Número Médio de Reservas por Utilizador</h5>
                        <h5 value="averageRented-cars"><?php echo $MediaReservaUtilizador;?></h5>
                    </div>
                </div>
                <div class="infoFlex column">
                    <div class="container">
                        <h5>Número Total de Utilizadores</h5>
                        <h5 value="total-users"><?php echo $NumeroUtilizadores;?></h5>
                    </div>
                    <div class="container">
                        <h5>Utilizadores que Reservaram</h5>
                        <h5 value="thatRent-users"><?php echo $UtilizadoresQueReservaram;?></h5>
                    </div>
                </div>

    </main>
</body>

</html>
