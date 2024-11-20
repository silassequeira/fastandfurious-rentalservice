<?php
$str = "dbname=postgres user=postgres password=postgres host=localhost port=5432";
$connection = pg_connect($str);

if (!$connection) {
    die("Erro na conexão");
            }
            
$user = $_GET['username'];
$pass = $_GET['password'];

// Consulta correta com aspas simples ao redor de $user
$resultados = pg_query($connection, "SELECT password FROM cliente WHERE username='$user'") or die("Erro na consulta");

if (pg_num_rows($resultados) != 0) {
    $row = pg_fetch_assoc($resultados);
    if ($pass === $row['password']) {
        echo "Autenticação bem-sucedida";
    } else {
        echo "Senha ou usuário incorretos";
    }
} else {
    echo "Usuário não encontrado";
}

//exporta a informação para utilizar em outras paginas
session_start();
$_SESSION['user'] = $user;

pg_close($connection);
?>