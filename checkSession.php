<?php
session_start();

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Error connecting to the database");
}

function checkSession($connection) {
    if (isset($_SESSION['user'])) {
        $usernameInput = $_SESSION['user'];
        
        $query = pg_query_params(
            $connection,
            "SELECT saldo, username FROM cliente WHERE username = $1 OR email = $1",
            array($usernameInput)
        );

        if ($query && pg_num_rows($query) == 1) {
            $resultsUser = pg_fetch_assoc($query);
            return [
                'userDetails' => $resultsUser,
                'saldo' => $resultsUser['saldo']
            ];
        }
    } elseif (isset($_SESSION['admin'])) {
        $usernameInput = $_SESSION['admin'];
        
        $query = pg_query_params(
            $connection,
            "SELECT username FROM administrador WHERE username = $1 OR email = $1",
            array($usernameInput)
        );

        if ($query && pg_num_rows($query) == 1) {
            $resultsAdmin = pg_fetch_assoc($query);
            return [
                'adminDetails' => $resultsAdmin
            ];
        }
    }
    return null;
}

if (session_status() === PHP_SESSION_ACTIVE) {
    $sessionCheck = checkSession($connection);
}

pg_close($connection);
?>
