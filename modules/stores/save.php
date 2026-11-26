<?php
require_once __DIR__ . '/../../includes/auth.php';
require_admin();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$branchId = filter_input(INPUT_POST, 'branch_id', FILTER_VALIDATE_INT);
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$active = isset($_POST['is_active']) ? 't' : 'f';

$branch = $branchId ? db_one("SELECT id FROM branches WHERE id = $1 AND deleted_at IS NULL", [$branchId]) : null;

if (!$branch || $name == '') {
    flash('danger', 'La sucursal y el nombre son obligatorios');
    redirect('modules/stores/form.php?id=' . $id);
}

if ($id) {
    db_exec("UPDATE stores SET branch_id = $1, name = $2, description = $3, email = $4, phone = $5, is_active = $6 WHERE id = $7",
        [$branchId, $name, $description, $email, $phone, $active, $id]);
    flash('success', 'Tienda actualizada');
} else {
    db_exec("INSERT INTO stores (branch_id, name, description, email, phone, is_active) VALUES ($1, $2, $3, $4, $5, $6)",
        [$branchId, $name, $description, $email, $phone, $active]);
    flash('success', 'Tienda creada');
}
redirect('modules/stores/index.php');
