<?php
require_once __DIR__ . '/../../includes/auth.php';
require_access('shop');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// producto con su tienda, sucursal y ubicacion
$p = $id ? db_one("SELECT p.*, s.name AS store, b.name AS branch, b.address,
        ci.name AS city, d.name AS department, co.name AS country
    FROM products p
    JOIN stores s ON s.id = p.store_id
    JOIN branches b ON b.id = s.branch_id
    JOIN cities ci ON ci.id = b.city_id
    JOIN departments d ON d.id = ci.department_id
    JOIN countries co ON co.id = d.country_id
    WHERE p.id = $1 AND p.deleted_at IS NULL AND p.is_active = TRUE", [$id]) : null;

if (!$p) {
    flash('warning', 'El producto no esta disponible');
    redirect('modules/shop/index.php');
}

$page_title = $p['name'];
$active_menu = 'shop';
$noImage = BASE_URL . 'assets/img/no-image.svg';
require __DIR__ . '/../../includes/header.php';
?>
<a href="index.php">&lt;- Volver</a>

<div class="row mt-3">
    <div class="col-md-5">
        <img src="<?= e($p['image_url'] ? $p['image_url'] : $noImage) ?>" class="img-fluid" alt="" onerror="this.src='<?= $noImage ?>'">
    </div>
    <div class="col-md-7">
        <p>SKU: <?= e($p['sku']) ?></p>
        <h4><?= money($p['price']) ?></h4>
        <p><?= nl2br(e($p['description'])) ?></p>
        <p>
            Disponibles: <?= $p['stock'] ?><br>
            Tienda: <?= e($p['store']) ?><br>
            Sucursal: <?= e($p['branch']) ?> - <?= e($p['address']) ?><br>
            Ubicacion: <?= e($p['city']) ?>, <?= e($p['department']) ?>, <?= e($p['country']) ?>
        </p>
    </div>
</div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
