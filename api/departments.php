<?php
// devuelve los departamentos de un pais en json
require_once __DIR__ . '/../includes/auth.php';
require_login();
header('Content-Type: application/json; charset=utf-8');

$countryId = filter_input(INPUT_GET, 'country_id', FILTER_VALIDATE_INT);
if (!$countryId) {
    echo '[]';
    exit;
}

$rows = db_all("SELECT id, name FROM departments WHERE country_id = $1 AND deleted_at IS NULL ORDER BY name", [$countryId]);
echo json_encode($rows);
