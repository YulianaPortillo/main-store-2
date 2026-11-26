<?php
require_once __DIR__ . '/../../includes/auth.php';
require_admin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$branch = ['id' => '', 'name' => '', 'address' => '', 'phone' => '', 'city_id' => '', 'department_id' => '', 'country_id' => ''];

if ($id) {
    // se trae tambien el depto y el pais pa los selects
    $branch = db_one("SELECT b.id, b.name, b.address, b.phone, b.city_id, ci.department_id, d.country_id
        FROM branches b
        JOIN cities ci ON ci.id = b.city_id
        JOIN departments d ON d.id = ci.department_id
        WHERE b.id = $1 AND b.deleted_at IS NULL", [$id]);
    if (!$branch) {
        redirect('modules/branches/index.php');
    }
}

$countries = db_all("SELECT id, name FROM countries WHERE deleted_at IS NULL ORDER BY name");

$page_title = $id ? 'Editar sucursal' : 'Nueva sucursal';
$active_menu = 'branches';
$extra_js = '<script>var BASE_URL = "' . BASE_URL . '";</script><script src="' . BASE_URL . 'assets/js/location-cascade.js"></script>';
require __DIR__ . '/../../includes/header.php';
?>
<form action="save.php" method="post" style="max-width: 600px">
    <input type="hidden" name="id" value="<?= e($branch['id']) ?>">

    <div class="form-group">
        <label>Pais</label>
        <select id="country_id" class="form-control" required>
            <option value="">Seleccione</option>
            <?php foreach ($countries as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id'] == $branch['country_id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Departamento</label>
        <select id="department_id" class="form-control" data-selected="<?= e($branch['department_id']) ?>" required disabled>
            <option value="">Seleccione un pais</option>
        </select>
    </div>
    <div class="form-group">
        <label>Ciudad</label>
        <select id="city_id" name="city_id" class="form-control" data-selected="<?= e($branch['city_id']) ?>" required disabled>
            <option value="">Seleccione un departamento</option>
        </select>
    </div>
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="name" class="form-control" value="<?= e($branch['name']) ?>" required>
    </div>
    <div class="form-group">
        <label>Direccion</label>
        <input type="text" name="address" class="form-control" value="<?= e($branch['address']) ?>" required>
    </div>
    <div class="form-group">
        <label>Telefono</label>
        <input type="text" name="phone" class="form-control" value="<?= e($branch['phone']) ?>">
    </div>

    <button class="btn btn-primary">Guardar</button>
    <a href="index.php">Cancelar</a>
</form>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
