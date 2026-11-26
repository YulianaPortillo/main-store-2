<?php
// devuelve las ciudades de un departamento en json
require_once __DIR__ . '/../includes/auth.php';
require_login();
header('Content-Type: application/json; charset=utf-8');

$departmentId = filter_input(INPUT_GET, 'department_id', FILTER_VALIDATE_INT);
if (!$departmentId) {
    echo '[]';
    exit;
}

$rows = db_all("SELECT id, name FROM cities WHERE department_id = $1 AND deleted_at IS NULL ORDER BY name", [$departmentId]);
echo json_encode($rows);
