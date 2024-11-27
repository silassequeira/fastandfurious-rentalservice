<?php
function generateUniqueId($tabel, $idnameintable) {
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