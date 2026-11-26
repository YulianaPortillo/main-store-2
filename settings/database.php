<?php
// Conexion a la base de datos

$host = "localhost";
$port = "5432";
$dbname = "main_store";
$user = "postgres";
$password = "Abc123";

$local_conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$local_conn) {
    die("Error de conexion");
}
?>
