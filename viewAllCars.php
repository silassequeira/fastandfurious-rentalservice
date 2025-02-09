<?php

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Erro na conexão");
}

unset($_SESSION['selected_car']);
$details = $_SESSION['reservation_data'] ?? null;

# Filter cars based on user input
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitFilter'])) {

    $_SESSION['cars'] = [];

    $sql = "SELECT * FROM carro WHERE valordiario > 0 AND valordiario < 100000";
    $params = [];
    $placeholders = 1; // Placeholder counter

    if (isset($_POST['min-price']) && $_POST['min-price'] !== '') {
        $sql .= " AND valordiario > \${$placeholders}";
        $params[] = $_POST['min-price'];
        $placeholders++;
    }
    if (isset($_POST['max-price']) && $_POST['max-price'] !== '') {
        $sql .= " AND valordiario < \${$placeholders}";
        $params[] = $_POST['max-price'];
        $placeholders++;
    }
    if (isset($_POST['car-brand']) && $_POST['car-brand'] !== '') {
        $sql .= " AND LOWER(marca) = LOWER(\${$placeholders})";
        $params[] = $_POST['car-brand'];
        $placeholders++;
    }


    $sql .= " AND (ocultado = FALSE OR arrendado = FALSE)";

    $result = pg_query_params($connection, $sql, $params);
    if ($result === false) {
        error_log("Erro ao executar a consulta: " . pg_last_error($connection));
        $_SESSION['error'] = "Erro ao executar a consulta";
        header('Location: index.php');
        exit();
    }

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

    # Displays all cars
} else {

    $sql = "SELECT * FROM carro";
    $result = pg_query($connection, $sql);

    if (!$result) {
        die("Erro ao buscar dados do carro: " . pg_last_error($connection));
    }

    $cars = pg_fetch_all($result);

}

# Storing the reservation dates for each car in the session (admin)
$_SESSION['dates'] = [];
foreach ($cars as $car) {
    $sqlDates = "SELECT cliente_username, datainicio, datafim FROM reserva WHERE carro_idcarro = $1";
    $resultDates = pg_query_params($connection, $sqlDates, [$car['idcarro']]);

    if (!$resultDates) {
        die("Erro ao buscar dados do carro: " . pg_last_error($connection));
    }

    $dates = pg_fetch_all($resultDates);

    $_SESSION['dates'][$car['idcarro']] = $dates ? $dates : [];
}


# Function to check if two date intervals overlap (user)
function intervalosCoincidem($inicio1, $fim1, $inicio2, $fim2)
{
    // Converte as datas para timestamps
    $inicio1 = strtotime($inicio1);
    $fim1 = strtotime($fim1);
    $inicio2 = strtotime($inicio2);
    $fim2 = strtotime($fim2);

    // Verifica se há sobreposição
    return ($inicio1 <= $fim2) && ($inicio2 <= $fim1);
}


# Store car records in the session with additional display fields and reservation counts (admin)
foreach ($cars as $index => $car) {


    $_SESSION['cars'][$index] = $car;
    $_SESSION['cars'][$index]['ocultado'] = $car['ocultado'] === 't' ? 'Revelar' : 'Ocultar';
    $_SESSION['cars'][$index]['arrendado'] = $car['arrendado'] === 't' ? 'Arrendado' : 'Disponível';
    $_SESSION['cars'][$index]['arrendado_color'] = $car['arrendado'] === 't' ? 'redFont redStrokeColor' : 'greenFont greenStrokeColor';

    $_SESSION['cars'][$index]['reservation_count'] = 0;

    $sqlCount = "SELECT count(*) FROM reserva WHERE carro_idcarro = $1";
    $resultCount = pg_query_params($connection, $sqlCount, [$car['idcarro']]);

    if (!$resultCount) {
        die("Erro ao buscar dados do carro: " . pg_last_error($connection));
    }

    $countResult = pg_fetch_result($resultCount, 0, 0);
    $_SESSION['cars'][$index]['reservation_count'] = $countResult;


}

foreach ($cars as $index => $car) {
    $_SESSION['cars'][$index]['disponivel'] = $indiponivelnadata ?? [];

    $indiponivelnadata = false;

    if (isset($_SESSION['user']) && $countResult > 0) {
        $sqlReservas = "SELECT datainicio, datafim FROM reserva WHERE carro_idcarro = $1";
        $resultReservas = pg_query_params($connection, $sqlReservas, [$car['idcarro']]);

        if (!$resultReservas) {
            die("Erro ao buscar dados das reservas: " . pg_last_error($connection));
        }

        $reservas = pg_fetch_all($resultReservas);

        if ($reservas) {
            foreach ($reservas as $reserva) {
                $dataInicioReserva = $reserva['datainicio'];
                $dataFimReserva = $reserva['datafim'];
                $dataInicioUser = $_SESSION['reservation_data']['datainicio'];
                $dataFimUser = $_SESSION['reservation_data']['datafim'];

                if (intervalosCoincidem($dataInicioReserva, $dataFimReserva, $dataInicioUser, $dataFimUser)) {
                    $indiponivelnadata = true;
                    break; // Stop checking once one conflict is found
                }
            }
        }
    }
    $_SESSION['cars'][$index]['disponivel'] = $indiponivelnadata ? 'Invisivel' : 'Visivel';

}

#  Handles car modification (admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitModify']) && isset($_POST['car_id'])) {

    $carId = $_POST['car_id'];

    // Find the car in the session data
    foreach ($_SESSION['cars'] as $car) {
        if ($car['idcarro'] == $carId) {
            $_SESSION['selected_car'] = $car;
            break; // Exit the loop once the target car is found
        }
    }
    header('Location: admin_addNewCar.php');
    exit();
}


# Handles car hide/reveal (admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitHide']) && isset($_POST['car_id'])) {

    $carId = $_POST['car_id'];

    $selectSql = "SELECT ocultado FROM carro WHERE idcarro = $1";
    $selectResult = pg_query_params($connection, $selectSql, array($carId));

    if (!$selectResult) {
        die("Erro ao buscar status do carro: " . pg_last_error($connection));
    }

    $currentStatus = pg_fetch_result($selectResult, 0, 'ocultado');
    $hidden = $currentStatus === 't' ? 'f' : 't'; // Toggle value (assuming PostgreSQL 't' for true, 'f' for false)

    $updateSql = "UPDATE carro SET ocultado = $2 WHERE idcarro = $1";
    $params = array($carId, $hidden);
    $result = pg_query_params($connection, $updateSql, $params);

    if (!$result) {
        die("Erro ao ocultar carro: " . pg_last_error($connection));
    }

    header('Location: admin_visualizeAllCars.php');
    exit();
}


# Handles car deletion (admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitErase']) && isset($_POST['car_id'])) {

    $carId = $_POST['car_id'];
    $deleteSql = 'DELETE FROM carro WHERE idcarro = $1';
    $result = pg_query_params($connection, $deleteSql, array($carId));
    if (!$result) {
        die("Erro ao eliminar carro: " . pg_last_error($connection));
    } else {
        // Remove the car from the session variables
        foreach ($_SESSION['cars'] as $index => $car) {
            if ($car['idcarro'] == $carId) {
                unset($_SESSION['cars'][$index]);
                break;
            }
        }
        unset($_SESSION['dates'][$carId]);
        echo "Car deleted successfully.";
    }

    header("Location: admin_visualizeAllCars.php");
    exit();
}


# Calculation function for the total price of a reservation (user)
function calculateTotalPrice($startDate, $endDate, $pricePerDay)
{
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);

    $interval = $start->diff($end);
    $days = $interval->days + 1;

    $totalPrice = $days * $pricePerDay;

    return $totalPrice;
}


# Handles car rental (user)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitRent']) && isset($_POST['car_id']) && isset($_SESSION['reservation_data'])) {

    $carId = $_POST['car_id'];
    $_SESSION['reservation_data']['carro_idcarro'] = $carId;

    // Check if the car is already rented during the requested period
    $datainicio = $_SESSION['reservation_data']['datainicio'];
    $datafim = $_SESSION['reservation_data']['datafim'];

    $sqlCheck = "SELECT datainicio, datafim FROM reserva WHERE carro_idcarro = $1";
    $resultCheck = pg_query_params($connection, $sqlCheck, [$carId]);

    if ($resultCheck && pg_num_rows($resultCheck) > 0) {
        while ($row = pg_fetch_assoc($resultCheck)) {
            if (intervalosCoincidem($row['datainicio'], $row['datafim'], $datainicio, $datafim)) {
                $_SESSION['error'] = "Este carro já está reservado para o período selecionado.";
                header('Location: user_selectCar.php');
                exit();
            }
        }
    }

    // Proceed with rental if no conflicts
    $selectedCar = null;
    foreach ($_SESSION['cars'] as $car) {
        if ($car['idcarro'] == $carId) {
            $selectedCar = $car;
            break;
        }
    }

    $_SESSION['selected_car'] = $selectedCar;
    $pricePerDay = $selectedCar['valordiario'];
    $totalPrice = calculateTotalPrice($datainicio, $datafim, $pricePerDay);
    $_SESSION['reservation_data']['custototal'] = $totalPrice;

    header('Location: user_confirmReservation.php');
    exit();
}


if (!isset($_SESSION['cars'])) {
    echo "Não há carros disponíveis";
} else {

    # Displays all cars and applies different tags for the admin and the user
    foreach ($_SESSION['cars'] as $index => $car) {
        $carId = $car['idcarro'];
        $dates = $_SESSION['dates'][$carId] ?? [];

        if (isset($_SESSION['user'])) {
            $str = '<div class="car-item ' . $car['ocultado'] . ' ' . $car['disponivel'] . ' ">';
        } else {
            $str = '<div class="car-item">' .
                '<div class="infoFlex marginFlex">' .
                '<p class="borderAround ' . $car['arrendado_color'] . '">' . $car['arrendado'] . '</p>';

            if ($car['reservation_count'] == 0) {
                $str .= '<p class="marginFlex Revelar"></p>';
            } else {
                $str .= '<p class="text-light">n&#176; de reservas:<b> ' . $car['reservation_count'] . '</b></p>';
            }
            $str .= '</div>';

            $str .= '<div class="layoutGridAutoFit marginFlex noGap borderTop">';

            if (!empty($dates)) {
                foreach ($dates as $date) {
                    $str .= '<span>' .
                        '<p class="greenFont">' . $date['cliente_username'] . '</p>' .
                        '<p class="">Levantamento: ' . $date['datainicio'] . '</p>' .
                        '<p class="">Entrega ' . $date['datafim'] . '</p>' .
                        '</span>';
                }
            } else {
                $str .= '<span>' .
                    '<p class="Revelar"></p>' .
                    '</span>';

            }
            $str .= '</div>';
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
                '<input type="submit" class="button greenFont whiteBackground greenStrokeColor ' . $car['arrendado'] . '" name="submitModify" value="Modificar" id="submitModify">' .
                '<input type="submit" class="button greenFont whiteBackground greenStrokeColor ' . $car['arrendado'] . '" name="submitErase" value="Eliminar" id="submitErase">' .
                '<input type="submit" class="button greenFont whiteBackground greenStrokeColor ' . $car['arrendado'] . '" name="submitHide" value="' . $car['ocultado'] . '">' .
                '</div>';
        } else if (isset($_SESSION['user'])) {
            $str .= '<input type="submit" class="button centered-marginTop redFont whiteBackground redStrokeColor" name="submitRent" value="Alugar" id="submitRent">';
        }

        $str .= '</form>' .
            '</div>';

        echo $str;
    }
}

pg_close($connection);