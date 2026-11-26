<?php
require_once __DIR__ . '/includes/auth.php';

if (current_user()) {
    redirect(home_path());
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // buscar el usuario con su perfil
    $user = db_one("SELECT u.id, u.name, u.email, u.password, u.photo_url, u.profile_id,
            p.code AS profile_code, p.name AS profile_name
        FROM users u
        JOIN profiles p ON p.id = u.profile_id
        WHERE u.email = $1 AND u.deleted_at IS NULL AND p.deleted_at IS NULL", [$email]);

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        unset($user['password']); // la clave no va en la sesion
        $_SESSION['user'] = $user;
        redirect(home_path());
    }
    $error = 'Correo o contraseña incorrecta';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingresar - Main Store</title>
    <link href="<?= BASE_URL ?>assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/app.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 400px">
    <h3 class="mb-4">Main Store 2.0</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>Correo</label>
            <input type="email" name="email" class="form-control" value="<?= e($email) ?>" required>
        </div>
        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary">Ingresar</button>
    </form>
</div>
</body>
</html>
