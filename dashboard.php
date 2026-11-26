<?php
require_once __DIR__ . '/includes/auth.php';
require_access('dashboard');

$page_title = 'Inicio';
$active_menu = 'dashboard';

// contar registros
$users = db_one("SELECT COUNT(*) AS total FROM users WHERE deleted_at IS NULL");
$branches = db_one("SELECT COUNT(*) AS total FROM branches WHERE deleted_at IS NULL");
$stores = db_one("SELECT COUNT(*) AS total FROM stores WHERE deleted_at IS NULL");
$products = db_one("SELECT COUNT(*) AS total FROM products WHERE deleted_at IS NULL");

require __DIR__ . '/includes/header.php';
?>
<p>Hola, <?= e(current_user()['name']) ?>.</p>

<table class="table table-bordered bg-white" style="max-width: 300px">
    <tr><td>Usuarios</td><td><?= $users['total'] ?></td></tr>
    <tr><td>Sucursales</td><td><?= $branches['total'] ?></td></tr>
    <tr><td>Tiendas</td><td><?= $stores['total'] ?></td></tr>
    <tr><td>Productos</td><td><?= $products['total'] ?></td></tr>
</table>
<?php require __DIR__ . '/includes/footer.php'; ?>
