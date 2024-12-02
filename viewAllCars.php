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

pg_close($connection);
