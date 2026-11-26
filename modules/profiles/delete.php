<?php
require_once __DIR__ . '/../../includes/auth.php';
require_admin();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    redirect('modules/profiles/index.php');
}

$profile = db_one("SELECT code FROM profiles WHERE id = $1", [$id]);
$users = db_one("SELECT COUNT(*) AS total FROM users WHERE profile_id = $1 AND deleted_at IS NULL", [$id]);

if (!$profile || $profile['code'] == 'ADMIN') {
    flash('danger', 'El perfil ADMIN no se puede eliminar');
} elseif ($users['total'] > 0) {
    flash('warning', 'No se puede eliminar, hay usuarios con este perfil');
} else {
    // borrado logico
    db_exec("UPDATE profiles SET deleted_at = NOW() WHERE id = $1", [$id]);
    flash('success', 'Perfil eliminado');
}
redirect('modules/profiles/index.php');
