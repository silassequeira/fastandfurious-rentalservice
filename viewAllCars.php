<?php

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Erro na conexão");
}

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
    $_SESSION['cars'][$index]['arrendado'] = $car['arrendado'] === 't' ? 'Arrendado' : 'Por Arrendar';
}


// Display cars with a form to select a specific car
foreach ($_SESSION['cars'] as $index => $car) {

    if (isset($_SESSION['user'])) {
        $str = '<div class="car-item ' . $car['ocultado'] . '">';
    } else {
        $str = '<div class="car-item">';
    }
    $str .= '<img src="' . $car['foto'] . '" alt="Imagem do carro">' .
        '<h3>' . $car['marca'] . '</h3>' .
        '<p>Modelo: ' . $car['modelo'] . '</p>' .
        '<p>Ano: ' . $car['ano'] . '</p>' .
        '<p>Assentos: ' . $car['assentos'] . '</p>' .
        '<p>Preço Diário: ' . $car['valordiario'] . '</p>' .
        '<p>' . $car['arrendado'] . '</p>' .
        '<form method="POST">' .
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
function calculateTotalPrice($startDate, $endDate, $pricePerDay)
{
    try {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);

        // Calculate the interval in days
        $interval = $start->diff($end);
        $days = $interval->days + 1; // +1 to include both start and end days

        // Calculate total price
        $totalPrice = $days * $pricePerDay;

        return $totalPrice;
    } catch (Exception $e) {
        // Handle invalid date formats
        return "Erro: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitRent']) && isset($_POST['car_id']) && isset($_SESSION['reservation_data'])) {
    $carId = $_POST['car_id'];
    $_SESSION['reservation_data']['carro_idcarro'] = $carId;

    // Validate and find the car in the session
    if (!isset($_SESSION['cars']) || !is_array($_SESSION['cars'])) {
        $_SESSION['error'] = "Nenhum carro disponível para seleção.";
        header('Location: user_reservations.php');
        exit();
    }

    $selectedCar = null;
    foreach ($_SESSION['cars'] as $car) {
        if ($car['idcarro'] == $carId) {
            $selectedCar = $car;
            break;
        }
    }

    if (!$selectedCar) {
        $_SESSION['error'] = "Erro ao selecionar o carro";
        header('Location: user_reservations.php');
        exit();
    }

    $_SESSION['selected_car'] = $selectedCar;

    // Validate reservation data
    if (!isset($_SESSION['reservation_data']['datainicio'], $_SESSION['reservation_data']['datafim'])) {
        $_SESSION['error'] = "Datas de reserva não definidas.";
        header('Location: user_reservations.php');
        exit();
    }

    $datainicio = $_SESSION['reservation_data']['datainicio'];
    $datafim = $_SESSION['reservation_data']['datafim'];
    $pricePerDay = $selectedCar['valordiario'];

    // Calculate the total price
    $totalPrice = calculateTotalPrice($datainicio, $datafim, $pricePerDay);

    // Check for errors from the calculation
    if (is_string($totalPrice) && str_contains($totalPrice, "Erro")) {
        $_SESSION['error'] = $totalPrice; // Pass the error message from the function
        header('Location: user_reservations.php');
        exit();
    }

    $_SESSION['reservation_data']['custototal'] = $totalPrice;

    // Redirect to the confirmation page
    header('Location: user_confirmReservation.php');
    exit();
}

pg_close($connection);
