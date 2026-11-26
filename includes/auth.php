<?php
// sesion y permisos, se pone en todas las paginas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/helpers.php';

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function require_login()
{
    if (!current_user()) {
        redirect('login.php');
    }
}

function is_admin()
{
    $user = current_user();
    return $user && $user['profile_code'] == 'ADMIN';
}

function deny()
{
    http_response_code(403);
    require __DIR__ . '/403.php';
    exit;
}

// solo el admin puede crear, editar y borrar
function require_admin()
{
    require_login();
    if (!is_admin()) {
        deny();
    }
}

// menus del perfil para el sidebar
function user_menus()
{
    $user = current_user();
    if (!$user) {
        return [];
    }
    return db_all("SELECT m.code, m.name, m.icon, m.url FROM menus m
        JOIN profile_menu pm ON pm.menu_id = m.id
        WHERE pm.profile_id = $1 AND m.deleted_at IS NULL AND pm.deleted_at IS NULL
        ORDER BY m.sort_order", [$user['profile_id']]);
}

// si el perfil no tiene el menu, muestra 403
function require_access($code)
{
    require_login();
    $row = db_one("SELECT m.id FROM menus m
        JOIN profile_menu pm ON pm.menu_id = m.id
        WHERE pm.profile_id = $1 AND m.code = $2 AND m.deleted_at IS NULL AND pm.deleted_at IS NULL",
        [current_user()['profile_id'], $code]);
    if (!$row) {
        deny();
    }
}

function home_path()
{
    if (is_admin()) {
        return 'dashboard.php';
    }
    return 'modules/shop/index.php';
}
