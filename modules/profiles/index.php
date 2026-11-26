<?php
require_once __DIR__ . '/../../includes/auth.php';
require_access('profiles');

$page_title = 'Perfiles';
$active_menu = 'profiles';

$profiles = db_all("SELECT p.id, p.code, p.name, p.description,
        (SELECT COUNT(*) FROM users u WHERE u.profile_id = p.id AND u.deleted_at IS NULL) AS users,
        (SELECT COUNT(*) FROM profile_menu pm WHERE pm.profile_id = p.id AND pm.deleted_at IS NULL) AS menus
    FROM profiles p
    WHERE p.deleted_at IS NULL
    ORDER BY p.id");

require __DIR__ . '/../../includes/header.php';
?>
<a href="form.php" class="btn btn-primary mb-3">Nuevo perfil</a>

<table class="table table-bordered bg-white">
    <tr>
        <th>Código</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Usuarios</th>
        <th>Menús</th>
        <th></th>
    </tr>
    <?php foreach ($profiles as $p): ?>
    <tr>
        <td><?= e($p['code']) ?></td>
        <td><?= e($p['name']) ?></td>
        <td><?= e($p['description']) ?></td>
        <td><?= $p['users'] ?></td>
        <td><?= $p['menus'] ?></td>
        <td>
            <a href="menus.php?id=<?= $p['id'] ?>">Menús</a> |
            <a href="form.php?id=<?= $p['id'] ?>">Editar</a> |
            <form action="delete.php" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar este perfil?')">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button class="btn btn-link p-0 text-danger">Eliminar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
