<?php
require_once __DIR__ . '/../../includes/auth.php';
require_access('users');

$page_title = 'Usuarios';
$active_menu = 'users';

$users = db_all("SELECT u.id, u.name, u.email, u.photo_url, p.name AS profile
    FROM users u
    JOIN profiles p ON p.id = u.profile_id
    WHERE u.deleted_at IS NULL
    ORDER BY u.name");

require __DIR__ . '/../../includes/header.php';
?>
<a href="form.php" class="btn btn-primary mb-3">Nuevo usuario</a>

<table class="table table-bordered bg-white">
    <tr>
        <th>Foto</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Perfil</th>
        <th></th>
    </tr>
    <?php foreach ($users as $u): ?>
    <tr>
        <td><img src="<?= e($u['photo_url'] ? $u['photo_url'] : BASE_URL . 'assets/img/no-image.svg') ?>" class="thumb" alt=""></td>
        <td><?= e($u['name']) ?></td>
        <td><?= e($u['email']) ?></td>
        <td><?= e($u['profile']) ?></td>
        <td>
            <a href="form.php?id=<?= $u['id'] ?>">Editar</a>
            <?php if ($u['id'] != current_user()['id']): ?>
            | <form action="delete.php" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar este usuario?')">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <button class="btn btn-link p-0 text-danger">Eliminar</button>
            </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
