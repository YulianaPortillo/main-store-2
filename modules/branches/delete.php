<?php
require_once __DIR__ . '/../../includes/auth.php';
require_admin();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    // si tiene tiendas no se deja borrar
    $stores = db_one("SELECT COUNT(*) AS total FROM stores WHERE branch_id = $1 AND deleted_at IS NULL", [$id]);
    if ($stores['total'] > 0) {
        flash('warning', 'No se puede eliminar porque tiene tiendas');
    } else {
        db_exec("UPDATE branches SET deleted_at = NOW() WHERE id = $1", [$id]);
        flash('success', 'Sucursal eliminada');
    }
}
redirect('modules/branches/index.php');
