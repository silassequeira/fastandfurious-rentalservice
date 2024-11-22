<?php
    $str = "dbname=postgres user=postgres password=postgres host=localhost port=5432";
    $connection = pg_connect($str);

    if (!$connection) {
        die("Erro na conexão");
    }
    // get informação
    $name = $_GET['car-name'];
    $brand = $_GET['brand'];
    $model = $_GET['model'];
    $seats = $_GET['seats'];
    $year = $_GET['year'];
    $price = $_GET['price'];

    //criar um id
    $id=0;
    $valid=false;
    while (!$valid) {
        $id++;
        $query = "SELECT id_carro FROM carro WHERE id_carro = $1";
        $result = pg_query_params($connection, $query, array($id));

        // Se não encontrar nenhuma linha, o ID está disponível
        if (pg_num_rows($result) == 0) {
            $valid = true;
        }
    }
    //adicionar carro á base de dados
    $sql = "INSERT INTO carro (id_carro, marca, modelo,ano,assentos,valordiario,administrador_username) VALUES ('$id', '$marca', '$model', '$year', '$seats', '$price', '$user')";
    $result = pg_query($connection, $sql);
    ?>