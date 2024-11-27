<?php

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Error connecting to the database");
}

function generateUniqueId($connection, $tabel, $idnameintable) {
    global $connection;
    $id = 0;
    $valid = false;

    while (!$valid) {
        $id++;
        $query = "SELECT $idnameintable FROM $tabel WHERE id_carro = $1";
        $result = pg_query_params($connection, $query, array($id));

        // Se não encontrar nenhuma linha, o ID está disponível
        if (pg_num_rows($result) == 0) {
            $valid = true;
        }
    }

    return $id;
}
?>