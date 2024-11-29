<?php

$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");
if (!$connection) {
    die("Error connecting to the database");
}

function generateUniqueId($connection, $table, $idColumn) {
    $id = 1; 
    $valid = false;

    while (!$valid) {
        $query = "SELECT $idColumn FROM $table WHERE $idColumn = $1";
        $result = pg_query_params($connection, $query, array($id));

        if (pg_num_rows($result) == 0) {
            $valid = true;
        } else {
            $id++;
        }
    }

    return $id;
}
