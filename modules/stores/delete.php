<?php
require_once __DIR__ . '/../../includes/auth.php';
require_admin();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    // si tiene productos no se borra
    $products = db_one("SELECT COUNT(*) AS total FROM products WHERE store_id = $1 AND deleted_at IS NULL", [$id]);
    if ($products['total'] > 0) {
        flash('warning', 'No se puede eliminar porque tiene productos');
    } else {
        db_exec("UPDATE stores SET deleted_at = NOW() WHERE id = $1", [$id]);
        flash('success', 'Tienda eliminada');
    }
}
redirect('modules/stores/index.php');
