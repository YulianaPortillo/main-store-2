<?php
require_once __DIR__ . '/../../includes/auth.php';
require_admin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$user = ['id' => '', 'name' => '', 'email' => '', 'photo_url' => '', 'profile_id' => ''];

if ($id) {
    $user = db_one("SELECT id, name, email, photo_url, profile_id FROM users WHERE id = $1 AND deleted_at IS NULL", [$id]);
    if (!$user) {
        redirect('modules/users/index.php');
    }
}

$profiles = db_all("SELECT id, name FROM profiles WHERE deleted_at IS NULL ORDER BY name");

$page_title = $id ? 'Editar usuario' : 'Nuevo usuario';
$active_menu = 'users';
require __DIR__ . '/../../includes/header.php';
?>
<form action="save.php" method="post" style="max-width: 500px">
    <input type="hidden" name="id" value="<?= e($user['id']) ?>">

    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="name" class="form-control" value="<?= e($user['name']) ?>" required>
    </div>
    <div class="form-group">
        <label>Correo</label>
        <input type="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required>
    </div>
    <div class="form-group">
        <label>Foto (URL)</label>
        <input type="url" name="photo_url" class="form-control" value="<?= e($user['photo_url']) ?>">
    </div>
    <div class="form-group">
        <label>Perfil</label>
        <select name="profile_id" class="form-control">
            <?php foreach ($profiles as $p): ?>
                <option value="<?= $p['id'] ?>" <?= $p['id'] == $user['profile_id'] ? 'selected' : '' ?>><?= e($p['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Contraseña <?= $id ? '(dejar vacía para no cambiarla)' : '' ?></label>
        <input type="password" name="password" class="form-control" <?= $id ? '' : 'required' ?>>
    </div>

    <button class="btn btn-primary">Guardar</button>
    <a href="index.php">Cancelar</a>
</form>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
