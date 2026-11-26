<?php
require_once __DIR__ . '/../../includes/auth.php';
require_admin();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

// no se puede borrar a si mismo
if ($id && $id != current_user()['id']) {
    db_exec("UPDATE users SET deleted_at = NOW() WHERE id = $1", [$id]);
    flash('success', 'Usuario eliminado');
}
redirect('modules/users/index.php');
