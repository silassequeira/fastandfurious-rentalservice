<?php
$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Erro na conexão");
}

$sessionCheck = checkSession($connection);

error_reporting(E_ALL);
ini_set('display_errors', 1);

$sql = "SELECT * FROM carro";
$result = pg_query($connection, $sql);

if (!$result) {
    die("Erro ao buscar dados do carro: " . pg_last_error($connection));
}

$cars = pg_fetch_all($result);

$_SESSION['cars'] = [];
foreach ($cars as $index => $car) {
    $_SESSION['cars'][$index] = $car;
    $_SESSION['cars'][$index]['ocultado'] = $car['ocultado'] === 't' ? 'Revelar' : 'Ocultar';
    $_SESSION['cars'][$index]['status'] = $car['ocultado'] === 't' ? 'Arrendado' : 'Por Arrendar';
}

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
        $str .= '<input type="submit" class="button centered-marginTop redFont whiteBackground" name="submitModify" value="Modificar" id="submitModify">';
        $str .= '<input type="submit" class="button centered-marginTop redFont whiteBackground" name="submitErase" value="Eliminar" id="submitErase">';
        $str .= '<input type="submit" class="button centered-marginTop redFont whiteBackground" name="submitHide" value="' . $car['ocultado'] . '">';
    } else if (isset($_SESSION['user'])) {
        $str .= '<input type="submit" class="button centered-marginTop redFont whiteBackground" name="submitRent" value="Alugar" id="submitRent">';
    }

    $str .= '</form>' .
        '</div>';

    echo $str;
}




if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitModify']) && isset($_POST['car_id'])) {
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


// Hide or Reveal the car for the user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitHide']) && isset($_POST['car_id'])) {
    $carId = $_POST['car_id'];

    // Fetch the current status of 'ocultado'
    $selectSql = "SELECT ocultado FROM carro WHERE idcarro = $1";
    $selectResult = pg_query_params($connection, $selectSql, array($carId));

    if (!$selectResult) {
        die("Erro ao buscar status do carro: " . pg_last_error($connection));
    }

    $currentStatus = pg_fetch_result($selectResult, 0, 'ocultado');
    $hidden = $currentStatus === 't' ? 'f' : 't'; // Toggle value (assuming PostgreSQL 't' for true, 'f' for false)

    // Update 'ocultado' to its new value
    $updateSql = "UPDATE carro SET ocultado = $2 WHERE idcarro = $1";
    $params = array($carId, $hidden);
    $result = pg_query_params($connection, $updateSql, $params);
    

    if (!$result) {
        die("Erro ao ocultar carro: " . pg_last_error($connection));
    }

    // Refresh the page to reflect changes
    header('Location: admin_visualizeAllCars.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitErase']) && isset($_POST['car_id'])) {
    $carId = $_POST['car_id'];
    $deleteSql = 'DELETE FROM carro WHERE idcarro = $1';
    $result = pg_query_params($connection, $deleteSql, [$carId]);
    if (!$result) {
        die("Erro ao eliminar carro: " . pg_last_error($connection));
    } else {
        echo "Car deleted successfully.";
    }

    header("Location: admin_visualizeAllCars.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitRent']) && isset($_POST['car_id'])) {
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


pg_close($connection);
