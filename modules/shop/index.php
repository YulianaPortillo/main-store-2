<?php
// catalogo, lo ven todos los perfiles
require_once __DIR__ . '/../../includes/auth.php';
require_access('shop');

$page_title = 'Catalogo de productos';
$active_menu = 'shop';

$q = trim($_GET['q'] ?? '');
$storeId = filter_input(INPUT_GET, 'store', FILTER_VALIDATE_INT);
$order = $_GET['order'] ?? 'new';

// el order by no se puede pasar como parametro entonces se usa esta lista
$orders = [
    'new' => 'p.id DESC',
    'price_asc' => 'p.price ASC',
    'price_desc' => 'p.price DESC',
    'name' => 'p.name ASC',
];
if (!isset($orders[$order])) {
    $order = 'new';
}

// si el filtro biene vacio se manda null y no filtra
$products = db_all("SELECT p.id, p.name, p.description, p.price, p.stock, p.image_url, s.name AS store
    FROM products p
    JOIN stores s ON s.id = p.store_id
    WHERE p.deleted_at IS NULL AND p.is_active = TRUE
    AND s.deleted_at IS NULL AND s.is_active = TRUE
    AND ($1::text IS NULL OR p.name ILIKE '%' || $1 || '%' OR p.description ILIKE '%' || $1 || '%')
    AND ($2::int IS NULL OR p.store_id = $2)
    ORDER BY " . $orders[$order], [$q == '' ? null : $q, $storeId ? $storeId : null]);

$stores = db_all("SELECT id, name FROM stores WHERE deleted_at IS NULL AND is_active = TRUE ORDER BY name");
$noImage = BASE_URL . 'assets/img/no-image.svg';

require __DIR__ . '/../../includes/header.php';
?>
<form method="get">
    Buscar <input type="text" name="q" value="<?= e($q) ?>">
    Tienda
    <select name="store">
        <option value="">todas</option>
        <?php foreach ($stores as $s): ?>
            <option value="<?= $s['id'] ?>" <?= $s['id'] == $storeId ? 'selected' : '' ?>><?= e($s['name']) ?></option>
        <?php endforeach; ?>
    </select>
    Ordenar
    <select name="order">
        <option value="new">recientes</option>
        <option value="price_asc" <?= $order == 'price_asc' ? 'selected' : '' ?>>precio menor</option>
        <option value="price_desc" <?= $order == 'price_desc' ? 'selected' : '' ?>>precio mayor</option>
        <option value="name" <?= $order == 'name' ? 'selected' : '' ?>>nombre</option>
    </select>
    <button>Filtrar</button>
</form>
<br>

<p><?= count($products) ?> producto(s) encontrados</p>

<div class="row">
    <?php foreach ($products as $p): ?>
    <div class="col-xl-3 col-lg-4 col-sm-6 mb-4">
        <div class="card">
            <img src="<?= e($p['image_url'] ? $p['image_url'] : $noImage) ?>" class="foto" alt="" onerror="this.src='<?= $noImage ?>'">
            <div class="card-body">
                <b><?= e($p['name']) ?></b><br>
                <small><?= e($p['store']) ?></small>
                <p class="mb-1"><?= money($p['price']) ?></p>
                <?php if ($p['stock'] > 0): ?>
                    <p class="text-success">Disponibles: <?= $p['stock'] ?></p>
                <?php else: ?>
                    <p class="text-danger">Agotado</p>
                <?php endif; ?>
                <a href="view.php?id=<?= $p['id'] ?>">Ver detalle</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
