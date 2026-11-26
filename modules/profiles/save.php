<?php
require_once __DIR__ . '/../../includes/auth.php';
require_admin();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$code = strtoupper(trim($_POST['code'] ?? ''));
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($code == '' || $name == '') {
    flash('danger', 'El código y el nombre son obligatorios');
    redirect('modules/profiles/form.php?id=' . $id);
}

try {
    if ($id) {
        db_exec("UPDATE profiles SET code = $1, name = $2, description = $3 WHERE id = $4",
            [$code, $name, $description, $id]);
        flash('success', 'Perfil actualizado');
    } else {
        db_exec("INSERT INTO profiles (code, name, description) VALUES ($1, $2, $3)",
            [$code, $name, $description]);
        flash('success', 'Perfil creado');
    }
} catch (Exception $ex) {
    flash('danger', 'Ese código ya existe');
}
redirect('modules/profiles/index.php');
