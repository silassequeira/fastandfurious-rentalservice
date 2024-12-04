<?php
$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Erro na conexão");
}

$sessionCheck = checkSession($connection);

$sql = "SELECT * FROM carro";
$result = pg_query($connection, $sql);

if (!$result) {
    die("Erro ao buscar dados do carro: " . pg_last_error($connection));
}

$cars = pg_fetch_all($result);


$_SESSION['cars'] = [];
foreach ($cars as $index => $car) {
    $_SESSION['cars'][$index] = $car;
}

pg_close($connection);

// Display cars with a form to select a specific car
foreach ($_SESSION['cars'] as $index => $car) {
    $str = '<div class="car-item">' .
    '<img src="' . $car['foto'] . '" alt="Imagem do carro">' .
    '<h3>' . $car['marca'] . '</h3>' .
    '<p>Modelo: ' . $car['modelo'] . '</p>' .
    '<p>Ano: ' . $car['ano'] . '</p>' .
    '<p>Assentos: ' . $car['assentos'] . '</p>' .
    '<p>Preço Diário: R$ ' . $car['valordiario'] . '</p>' .
    '<form method="post" action="admin_visualizeAllCars.php">' .
    '<input type="hidden" name="car_id" value="' . $car['idcarro'] . '">';
 
 if (isset($_SESSION['admin'])) {
    $str .= '<input type="submit" class="button centered-marginTop redFont whiteBackground" name="submitModify" value="Modificar" id="submitModify">' .
            '<input type="submit" class="button centered-marginTop redFont whiteBackground" name="submitHide" value="Ocultar" id="submitHide">';
 } else if (isset($_SESSION['user'])) {
    $str .= '<input type="submit" class="button centered-marginTop redFont whiteBackground" name="submitRent" value="Alugar" id="submitRent">';
 }
 
 $str .= '</form>' .
    '</div>';
 
 echo $str;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitModify']) && isset($_POST['car_id']))  {
    $carId = $_POST['car_id'];

    // Find the car in the session data
    foreach ($_SESSION['cars'] as $car) {
        if ($car['idcarro'] == $carId) {
            $_SESSION['selected_car'] = $car;
            break;
        }
    }

    header('Location: admin_addNewCar.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitRent']) && isset($_POST['car_id']))  {
    $carId = $_POST['car_id'];

    // Find the car in the session data
    foreach ($_SESSION['cars'] as $car) {
        if ($car['idcarro'] == $carId) {
            $_SESSION['selected_car'] = $car;
            break;
        }
    }
    header('Location: user_confirmReservation.php');
    exit();
}

