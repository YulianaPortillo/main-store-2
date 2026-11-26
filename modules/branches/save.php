<?php
require_once __DIR__ . '/../../includes/auth.php';
require_admin();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$cityId = filter_input(INPUT_POST, 'city_id', FILTER_VALIDATE_INT);
$name = trim($_POST['name'] ?? '');
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');

// revisar q la ciudad si exista
$city = $cityId ? db_one("SELECT id FROM cities WHERE id = $1", [$cityId]) : null;

if (!$city || $name == '' || $address == '') {
    flash('danger', 'Faltan datos, llene pais, departamento, ciudad, nombre y direccion');
    redirect('modules/branches/form.php?id=' . $id);
}

if ($id) {
    db_exec("UPDATE branches SET city_id = $1, name = $2, address = $3, phone = $4 WHERE id = $5",
        [$cityId, $name, $address, $phone, $id]);
    flash('success', 'Sucursal actualizada');
} else {
    db_exec("INSERT INTO branches (city_id, name, address, phone) VALUES ($1, $2, $3, $4)",
        [$cityId, $name, $address, $phone]);
    flash('success', 'Sucursal creada');
}
redirect('modules/branches/index.php');
