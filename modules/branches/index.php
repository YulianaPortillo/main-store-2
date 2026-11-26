<?php
require_once __DIR__ . '/../../includes/auth.php';
require_access('branches');

$page_title = 'Sucursales';
$active_menu = 'branches';

// con join para sacar la ciudad, depto y pais
$branches = db_all("SELECT b.id, b.name, b.address, b.phone, ci.name AS city, d.name AS department, co.name AS country
    FROM branches b
    JOIN cities ci ON ci.id = b.city_id
    JOIN departments d ON d.id = ci.department_id
    JOIN countries co ON co.id = d.country_id
    WHERE b.deleted_at IS NULL
    ORDER BY b.name");

require __DIR__ . '/../../includes/header.php';
?>
<a href="form.php" class="btn btn-primary mb-3">Nueva sucursal</a>

<table class="table table-bordered bg-white">
    <tr>
        <th>Nombre</th>
        <th>Direccion</th>
        <th>Telefono</th>
        <th>Ciudad</th>
        <th></th>
    </tr>
    <?php foreach ($branches as $b): ?>
    <tr>
        <td><?= e($b['name']) ?></td>
        <td><?= e($b['address']) ?></td>
        <td><?= e($b['phone']) ?></td>
        <td><?= e($b['city']) ?>, <?= e($b['department']) ?>, <?= e($b['country']) ?></td>
        <td>
            <a href="form.php?id=<?= $b['id'] ?>">Editar</a> |
            <form action="delete.php" method="post" class="d-inline" onsubmit="return confirm('Seguro que quiere eliminar?')">
                <input type="hidden" name="id" value="<?= $b['id'] ?>">
                <button class="btn btn-link p-0 text-danger">Eliminar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
