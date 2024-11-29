<?php
session_start();

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Error connecting to the database");
}

function checkSession($connection)
{
    if (isset($_SESSION['user']) && isset($_SESSION['logged_in'])) { 
        $usernameInput = $_SESSION['user'];
        $query = pg_query_params(
            $connection,
            "SELECT name, email, saldo, username FROM cliente WHERE username = $1 OR email = $1",
            array($usernameInput)
        );

        if ($query && pg_num_rows($query) === 1) {
            $results = pg_fetch_assoc($query);
            return [
                'type' => 'user',
                'details' => $results
            ];
        }
    } elseif (isset($_SESSION['admin']) && isset($_SESSION['logged_in'])) {
        $usernameInput = $_SESSION['admin'];
        $query = pg_query_params(
            $connection,
            "SELECT name, username FROM administrador WHERE username = $1 OR email = $1",
            array($usernameInput)
        );

        if ($query && pg_num_rows($query) === 1) {
            $results = pg_fetch_assoc($query);
            return [
                'type' => 'admin',
                'details' => $results
            ];
        }
    }

    return null;
}

$sessionCheck = checkSession($connection);

pg_close($connection);
