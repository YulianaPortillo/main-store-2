<?php
// asignar menus a un perfil
require_once __DIR__ . '/../../includes/auth.php';
require_admin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$profile = $id ? db_one("SELECT id, name FROM profiles WHERE id = $1 AND deleted_at IS NULL", [$id]) : null;
if (!$profile) {
    redirect('modules/profiles/index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $menus = $_POST['menus'] ?? [];

    db_exec('BEGIN');
    // se quitan todos y se activan los marcados
    db_exec("UPDATE profile_menu SET deleted_at = NOW() WHERE profile_id = $1", [$id]);
    foreach ($menus as $menuId) {
        db_exec("INSERT INTO profile_menu (profile_id, menu_id) VALUES ($1, $2)
            ON CONFLICT (profile_id, menu_id) DO UPDATE SET deleted_at = NULL", [$id, (int) $menuId]);
    }
    db_exec('COMMIT');

    flash('success', 'Menús guardados');
    redirect('modules/profiles/index.php');
}

$menus = db_all("SELECT m.id, m.name, m.url,
        (SELECT COUNT(*) FROM profile_menu pm WHERE pm.menu_id = m.id AND pm.profile_id = $1 AND pm.deleted_at IS NULL) AS assigned
    FROM menus m
    WHERE m.deleted_at IS NULL
    ORDER BY m.sort_order", [$id]);

$page_title = 'Menús del perfil ' . $profile['name'];
$active_menu = 'profiles';
require __DIR__ . '/../../includes/header.php';
?>
<form method="post">
    <table class="table table-bordered bg-white" style="max-width: 500px">
        <tr>
            <th></th>
            <th>Menú</th>
            <th>Ruta</th>
        </tr>
        <?php foreach ($menus as $m): ?>
        <tr>
            <td><input type="checkbox" name="menus[]" value="<?= $m['id'] ?>" <?= $m['assigned'] > 0 ? 'checked' : '' ?>></td>
            <td><?= e($m['name']) ?></td>
            <td><?= e($m['url']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <button class="btn btn-primary">Guardar</button>
    <a href="index.php">Volver</a>
</form>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
