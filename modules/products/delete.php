<?php
require_once __DIR__ . '/../../includes/auth.php';
require_admin();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    // borrado logico
    db_exec("UPDATE products SET deleted_at = NOW() WHERE id = $1", [$id]);
    flash('success', 'Producto eliminado');
}
redirect('modules/products/index.php');
