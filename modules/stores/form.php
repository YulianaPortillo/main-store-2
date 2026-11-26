<?php
require_once __DIR__ . '/../../includes/auth.php';
require_admin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$store = ['id' => '', 'branch_id' => '', 'name' => '', 'description' => '', 'email' => '', 'phone' => '', 'is_active' => 't'];

if ($id) {
    $store = db_one("SELECT id, branch_id, name, description, email, phone, is_active FROM stores WHERE id = $1 AND deleted_at IS NULL", [$id]);
    if (!$store) {
        redirect('modules/stores/index.php');
    }
}

$branches = db_all("SELECT id, name FROM branches WHERE deleted_at IS NULL ORDER BY name");

$page_title = $id ? 'Editar tienda' : 'Nueva tienda';
$active_menu = 'stores';
require __DIR__ . '/../../includes/header.php';
?>
<?php if (!$branches): ?>
    <p class="text-danger">Primero hay que crear una sucursal.</p>
<?php endif; ?>

<form action="save.php" method="post" style="max-width: 500px">
    <input type="hidden" name="id" value="<?= e($store['id']) ?>">

    <div class="form-group">
        <label>Sucursal</label>
        <select name="branch_id" class="form-control" required>
            <option value="">Seleccione</option>
            <?php foreach ($branches as $b): ?>
                <option value="<?= $b['id'] ?>" <?= $b['id'] == $store['branch_id'] ? 'selected' : '' ?>><?= e($b['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="name" class="form-control" value="<?= e($store['name']) ?>" required>
    </div>
    <div class="form-group">
        <label>Descripcion</label>
        <input type="text" name="description" class="form-control" value="<?= e($store['description']) ?>">
    </div>
    <div class="form-group">
        <label>Correo</label>
        <input type="email" name="email" class="form-control" value="<?= e($store['email']) ?>">
    </div>
    <div class="form-group">
        <label>Telefono</label>
        <input type="text" name="phone" class="form-control" value="<?= e($store['phone']) ?>">
    </div>
    <div class="form-group">
        <input type="checkbox" name="is_active" value="1" <?= pg_bool($store['is_active']) ? 'checked' : '' ?>> Activa
    </div>

    <button class="btn btn-primary">Guardar</button>
    <a href="index.php">Cancelar</a>
</form>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
