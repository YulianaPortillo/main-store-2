<?php
$user = current_user();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($page_title) ?> - Main Store</title>
    <link href="<?= BASE_URL ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/app.css" rel="stylesheet">
</head>
<body>
<div id="wrapper">
    <?php require __DIR__ . '/sidebar.php'; ?>

    <div id="content-wrapper">
        <div id="content">
            <div class="bg-white border-bottom p-3 mb-3 text-right">
                <?php if (!empty($user['photo_url'])): ?>
                    <img src="<?= e($user['photo_url']) ?>" class="avatar" alt="">
                <?php endif; ?>
                <?= e($user['name']) ?> (<?= e($user['profile_name']) ?>)
                | <a href="<?= BASE_URL ?>logout.php">Salir</a>
            </div>

            <div class="container-fluid">
                <h3><?= e($page_title) ?></h3>
                <?php show_flash(); ?>
