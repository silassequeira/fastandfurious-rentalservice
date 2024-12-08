<?php

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Erro na conexão");
}

unset($_SESSION['selected_car']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitFilter'])) {
    $sql = "SELECT * FROM carro WHERE valordiario > 0 AND valordiario < 100000";
    $params = [];
    $placeholders = 1; // Placeholder counter
    
    if (isset($_POST['min-price']) && $_POST['min-price'] !== '') {
        $sql .= " AND valordiario > $" . $placeholders;
        $params[] = $_POST['min-price'];
        $placeholders++;
    }
    if (isset($_POST['max-price']) && $_POST['max-price'] !== '') {
        $sql .= " AND valordiario < $" . $placeholders;
        $params[] = $_POST['max-price'];
        $placeholders++;
    }
    if (isset($_POST['car-brand']) && $_POST['car-brand'] !== '') {
        $sql .= " AND marca = $" . $placeholders;
        $params[] = $_POST['car-brand'];
        $placeholders++;
    }
    
    $sql .= " AND ocultado = FALSE";
    
    // Now execute the query
    $result = pg_query_params($connection, $sql, $params);

    if (!$result) {
        error_log("Erro ao executar a consulta: " . pg_last_error($connection));
        $_SESSION['error'] = "Erro ao executar a consulta";
        header('Location: index.php');
        exit();
    } else {
        $cars = pg_fetch_all($result);
        header('user_selectCar.php');
        if ($cars === false) {
            $cars = [];
        }
    }
} else {

    $sql = "SELECT * FROM carro";
    $result = pg_query($connection, $sql);

    if (!$result) {
        die("Erro ao buscar dados do carro: " . pg_last_error($connection));
    }

    $cars = pg_fetch_all($result);

}

$_SESSION['cars'] = [];
foreach ($cars as $index => $car) {
    $_SESSION['cars'][$index] = $car;
    $_SESSION['cars'][$index]['ocultado'] = $car['ocultado'] === 't' ? 'Revelar' : 'Ocultar';
    $_SESSION['cars'][$index]['arrendado'] = $car['arrendado'] === 't' ? 'Arrendado' : 'Disponivel';
}


// Display cars with a form to select a specific car
foreach ($_SESSION['cars'] as $index => $car) {

    if (isset($_SESSION['user'])) {
        $str = '<div class="car-item ' . $car['ocultado'] . ' ' . $car['arrendado'] . '">';
    } else {
        $str = '<div class="car-item">' .
            '<p class="marginFlex">' . $car['arrendado'] . '</p>';
    }
    $str .=
        '<div class="imgContainer">' .
        '<img src="' . $car['foto'] . '" alt="Imagem do carro">' .
        '</div>' .

        '<h3 class="centered-marginTop">' . $car['marca'] . ' ' . $car['modelo'] . '</h3>' .

        '<div class="infoFlex column">' .

        '<div class="infoFlex marginFlex ">' .
        '<div class="infoFlex column alignCenter">' .
        '<p>Marca</p>' .
        '<h4>' . $car['marca'] . '</h4>' .
        '</div>' .

        '<div class="infoFlex column alignCenter">' .
        '<p>Modelo</p>' .
        '<h4>' . $car['modelo'] . '</h4>' .
        '</div>' .
        '</div>' .

        '<div class="infoFlex marginFlex">' .
        '<div class="infoFlex column alignCenter">' .
        '<p>Ano</p>' .
        '<h4>' . $car['ano'] . '</h4>' .
        '</div>' .

        '<div class="infoFlex column alignCenter">' .
        '<p>Assentos</p>' .
        '<h4>' . $car['assentos'] . '</h4>' .
        '</div>' .
        '</div>' .

        '<div class="infoFlex column alignCenter">' .
        '<p>Preço Diário</p>' .
        '<h4>' . $car['valordiario'] . '€</p>' .
        '</div>' .

        '</div>' .



        '<form method="POST">' .
        '<input type="hidden" name="car_id" value="' . $car['idcarro'] . '">';

    if (isset($_SESSION['admin'])) {
        $str .= '<div class="infoFlex centered-marginTop">' .
            '<input type="submit" class="button redFont whiteBackground" name="submitModify" value="Modificar" id="submitModify">' .
            '<input type="submit" class="button redFont whiteBackground" name="submitErase" value="Eliminar" id="submitErase">' .
            '<input type="submit" class="button redFont whiteBackground" name="submitHide" value="' . $car['ocultado'] . '">' .
            '</div>';
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
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);

    // Calculate the interval in days
    $interval = $start->diff($end);
    $days = $interval->days + 1; // +1 to include both start and end days

    // Calculate total price
    $totalPrice = $days * $pricePerDay;

    return $totalPrice;
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
