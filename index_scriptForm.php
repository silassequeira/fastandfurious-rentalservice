<?php
function handleReservationSubmission() {
    global $connection;
    if (session_status() !== PHP_SESSION_ACTIVE) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Location: loginUser.html');
            exit();
        }
        return; 
    }

    if (!isset($_SESSION['user'])) {
        header('Location: loginUser.html');
        exit();
    }

    $user = $_SESSION['user'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $datainicio = $_POST['datainicio'] ?? null;
        $datafim = $_POST['datafim'] ?? null;

        if (empty($datainicio) || empty($datafim)) {
            echo "Por favor, preencha as datas corretamente.";
            exit();
        }
        
        $idR=generateUniqueId('reserva_', 'id_reserva');
        $_SESSION['idR'] = $idR;
        $sql = "INSERT INTO reserva_ (id_reserva,datainicio, datafim, user_id)
                VALUES ($idR, $1, $2, (SELECT id FROM cliente WHERE username = $3 OR email = $3))";
        
        $result = pg_query_params(
            $connection, 
            $sql, 
            array($datainicio, $datafim, $user)
        );

        if ($result) {
            echo "Reserva registrada com sucesso!";
        } else {
            echo "Erro ao registrar a reserva: " . pg_last_error($connection);
        }
    }
}

handleReservationSubmission();
pg_close($connection);
?>