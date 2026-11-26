<?php
require_once __DIR__ . '/../../includes/auth.php';
require_admin();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$storeId = filter_input(INPUT_POST, 'store_id', FILTER_VALIDATE_INT);
$sku = strtoupper(trim($_POST['sku'] ?? ''));
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
$stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
$image = trim($_POST['image_url'] ?? '');
$active = isset($_POST['is_active']) ? 't' : 'f';

$back = 'modules/products/form.php?id=' . $id;

// validaciones
$store = $storeId ? db_one("SELECT id FROM stores WHERE id = $1 AND deleted_at IS NULL", [$storeId]) : null;
if (!$store || $sku == '' || $name == '') {
    flash('danger', 'Falta la tienda, el sku o el nombre');
    redirect($back);
}
if ($price === false || $price < 0 || $stock === false || $stock < 0) {
    flash('danger', 'El precio y el stock tienen que ser mayores o iguales a 0');
    redirect($back);
}
// la foto tiene que ser un link http o https
if ($image != '' && (!filter_var($image, FILTER_VALIDATE_URL) || !preg_match('/^https?:\/\//', $image))) {
    flash('danger', 'La URL de la imagen no es valida');
    redirect($back);
}
if ($image == '') {
    $image = null;
}

try {
    if ($id) {
        db_exec("UPDATE products SET store_id = $1, sku = $2, name = $3, description = $4, price = $5, stock = $6, image_url = $7, is_active = $8 WHERE id = $9",
            [$storeId, $sku, $name, $description, $price, $stock, $image, $active, $id]);
        flash('success', 'Producto actualizado');
    } else {
        db_exec("INSERT INTO products (store_id, sku, name, description, price, stock, image_url, is_active) VALUES ($1, $2, $3, $4, $5, $6, $7, $8)",
            [$storeId, $sku, $name, $description, $price, $stock, $image, $active]);
        flash('success', 'Producto creado');
    }
} catch (Exception $ex) {
    flash('danger', 'Ese sku ya existe');
    redirect($back);
}
redirect('modules/products/index.php');
