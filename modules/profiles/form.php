<?php
require_once __DIR__ . '/../../includes/auth.php';
require_admin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$profile = ['id' => '', 'code' => '', 'name' => '', 'description' => ''];

if ($id) {
    $profile = db_one("SELECT id, code, name, description FROM profiles WHERE id = $1 AND deleted_at IS NULL", [$id]);
    if (!$profile) {
        redirect('modules/profiles/index.php');
    }
}

$page_title = $id ? 'Editar perfil' : 'Nuevo perfil';
$active_menu = 'profiles';
require __DIR__ . '/../../includes/header.php';
?>
<form action="save.php" method="post" style="max-width: 500px">
    <input type="hidden" name="id" value="<?= e($profile['id']) ?>">

    <div class="form-group">
        <label>Código</label>
        <input type="text" name="code" class="form-control" maxlength="20" value="<?= e($profile['code']) ?>" required>
    </div>
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="name" class="form-control" maxlength="80" value="<?= e($profile['name']) ?>" required>
    </div>
    <div class="form-group">
        <label>Descripción</label>
        <textarea name="description" class="form-control" maxlength="255"><?= e($profile['description']) ?></textarea>
    </div>

    <button class="btn btn-primary">Guardar</button>
    <a href="index.php">Cancelar</a>
</form>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
