<?php
require_once __DIR__ . '/../../includes/auth.php';
require_access('products');

$page_title = 'Productos';
$active_menu = 'products';

$products = db_all("SELECT p.id, p.sku, p.name, p.price, p.stock, p.image_url, p.is_active, s.name AS store
    FROM products p
    JOIN stores s ON s.id = p.store_id
    WHERE p.deleted_at IS NULL
    ORDER BY p.id DESC");

$noImage = BASE_URL . 'assets/img/no-image.svg';

require __DIR__ . '/../../includes/header.php';
?>
<a href="form.php" class="btn btn-primary mb-3">Nuevo producto</a>

<table class="table table-bordered bg-white">
    <tr>
        <th>Foto</th>
        <th>SKU</th>
        <th>Nombre</th>
        <th>Tienda</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Visible</th>
        <th></th>
    </tr>
    <?php foreach ($products as $p): ?>
    <tr>
        <td><img src="<?= e($p['image_url'] ? $p['image_url'] : $noImage) ?>" class="thumb" alt="" onerror="this.src='<?= $noImage ?>'"></td>
        <td><?= e($p['sku']) ?></td>
        <td><?= e($p['name']) ?></td>
        <td><?= e($p['store']) ?></td>
        <td><?= money($p['price']) ?></td>
        <td><?= $p['stock'] ?></td>
        <td><?= pg_bool($p['is_active']) ? 'Si' : 'No' ?></td>
        <td>
            <a href="form.php?id=<?= $p['id'] ?>">Editar</a> |
            <form action="delete.php" method="post" class="d-inline" onsubmit="return confirm('Seguro que quiere eliminar?')">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button class="btn btn-link p-0 text-danger">Eliminar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
