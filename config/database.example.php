<?php
// conexion a la base de datos (copiar como database.php y poner la clave)
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'mainstore_v2');
define('DB_USER', 'postgres');
define('DB_PASS', 'CAMBIAR');

// nombre de la carpeta en htdocs
define('BASE_URL', '/main-store-v2/');

function db()
{
    static $conn = null;
    if ($conn === null) {
        $conn = pg_connect("host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASS);
        if (!$conn) {
            die('Error de conexion a la base de datos');
        }
        pg_set_client_encoding($conn, 'UTF8');
    }
    return $conn;
}
