<?php
require_once __DIR__ . '/../../includes/auth.php';
require_admin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$product = ['id' => '', 'store_id' => '', 'sku' => '', 'name' => '', 'description' => '', 'price' => '', 'stock' => '0', 'image_url' => '', 'is_active' => 't'];

if ($id) {
    $product = db_one("SELECT id, store_id, sku, name, description, price, stock, image_url, is_active FROM products WHERE id = $1 AND deleted_at IS NULL", [$id]);
    if (!$product) {
        redirect('modules/products/index.php');
    }
}

$stores = db_all("SELECT id, name FROM stores WHERE deleted_at IS NULL AND is_active = TRUE ORDER BY name");
$noImage = BASE_URL . 'assets/img/no-image.svg';

$page_title = $id ? 'Editar producto' : 'Nuevo producto';
$active_menu = 'products';

// para ver la foto cuando se escrive el link
$extra_js = '<script>
document.getElementById("image_url").addEventListener("input", function () {
    document.getElementById("preview").src = this.value || "' . $noImage . '";
});
</script>';

require __DIR__ . '/../../includes/header.php';
?>
<form action="save.php" method="post">
    <input type="hidden" name="id" value="<?= e($product['id']) ?>">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Tienda</label>
                <select name="store_id" class="form-control" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($stores as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $s['id'] == $product['store_id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>SKU</label>
                <input type="text" name="sku" class="form-control" value="<?= e($product['sku']) ?>" required>
            </div>
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="name" class="form-control" value="<?= e($product['name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Descripcion</label>
                <textarea name="description" class="form-control"><?= e($product['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Precio</label>
                <input type="number" name="price" class="form-control" min="0" value="<?= e($product['price']) ?>" required>
            </div>
            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" class="form-control" min="0" value="<?= e($product['stock']) ?>" required>
            </div>
            <div class="form-group">
                <input type="checkbox" name="is_active" value="1" <?= pg_bool($product['is_active']) ? 'checked' : '' ?>> Visible en el catalogo
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Foto (URL)</label>
                <input type="url" id="image_url" name="image_url" class="form-control" value="<?= e($product['image_url']) ?>">
            </div>
            <img id="preview" src="<?= e($product['image_url'] ? $product['image_url'] : $noImage) ?>" alt="" onerror="this.src='<?= $noImage ?>'">
        </div>
    </div>

    <button class="btn btn-primary">Guardar</button>
    <a href="index.php">Cancelar</a>
</form>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
