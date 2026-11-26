<?php
require_once __DIR__ . '/../../includes/auth.php';
require_access('stores');

$page_title = 'Tiendas';
$active_menu = 'stores';

$stores = db_all("SELECT s.id, s.name, s.email, s.phone, s.is_active, b.name AS branch
    FROM stores s
    JOIN branches b ON b.id = s.branch_id
    WHERE s.deleted_at IS NULL
    ORDER BY s.name");

require __DIR__ . '/../../includes/header.php';
?>
<a href="form.php" class="btn btn-primary mb-3">Nueva tienda</a>

<table class="table table-bordered bg-white">
    <tr>
        <th>Nombre</th>
        <th>Sucursal</th>
        <th>Correo</th>
        <th>Telefono</th>
        <th>Activa</th>
        <th></th>
    </tr>
    <?php foreach ($stores as $s): ?>
    <tr>
        <td><?= e($s['name']) ?></td>
        <td><?= e($s['branch']) ?></td>
        <td><?= e($s['email']) ?></td>
        <td><?= e($s['phone']) ?></td>
        <td><?= pg_bool($s['is_active']) ? 'Si' : 'No' ?></td>
        <td>
            <a href="form.php?id=<?= $s['id'] ?>">Editar</a> |
            <form action="delete.php" method="post" class="d-inline" onsubmit="return confirm('Seguro que quiere eliminar?')">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <button class="btn btn-link p-0 text-danger">Eliminar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
