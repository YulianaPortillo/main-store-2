<?php
require_once __DIR__ . '/../../includes/auth.php';
require_admin();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$photo = trim($_POST['photo_url'] ?? '');
$profileId = filter_input(INPUT_POST, 'profile_id', FILTER_VALIDATE_INT);
$password = $_POST['password'] ?? '';

$back = 'modules/users/form.php?id=' . $id;

// validaciones
if ($name == '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$profileId) {
    flash('danger', 'Revise el nombre, el correo y el perfil');
    redirect($back);
}
if (!$id && strlen($password) < 6) {
    flash('danger', 'La contraseña debe tener mínimo 6 caracteres');
    redirect($back);
}
// la foto debe ser un link http o https
if ($photo != '' && (!filter_var($photo, FILTER_VALIDATE_URL) || !preg_match('/^https?:\/\//', $photo))) {
    flash('danger', 'La URL de la foto no es válida');
    redirect($back);
}
if ($photo == '') {
    $photo = null;
}

try {
    if ($id) {
        db_exec("UPDATE users SET name = $1, email = $2, photo_url = $3, profile_id = $4 WHERE id = $5",
            [$name, $email, $photo, $profileId, $id]);
        if ($password != '') {
            db_exec("UPDATE users SET password = $1 WHERE id = $2", [password_hash($password, PASSWORD_DEFAULT), $id]);
        }
        flash('success', 'Usuario actualizado');
    } else {
        db_exec("INSERT INTO users (name, email, photo_url, profile_id, password) VALUES ($1, $2, $3, $4, $5)",
            [$name, $email, $photo, $profileId, password_hash($password, PASSWORD_DEFAULT)]);
        flash('success', 'Usuario creado');
    }
} catch (Exception $ex) {
    flash('danger', 'Ese correo ya está registrado');
    redirect($back);
}
redirect('modules/users/index.php');
