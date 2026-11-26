<?php
// funciones de ayuda

// para escapar el texto y q no metan html
function e($text)
{
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

function redirect($path)
{
    header('Location: ' . BASE_URL . $path);
    exit;
}

// todas las consultas usan $1, $2...
function db_exec($sql, $params = [])
{
    $res = @pg_query_params(db(), $sql, $params);
    if (!$res) {
        throw new Exception(pg_last_error(db()));
    }
    return $res;
}

function db_all($sql, $params = [])
{
    $rows = pg_fetch_all(db_exec($sql, $params));
    return $rows ? $rows : [];
}

function db_one($sql, $params = [])
{
    $row = pg_fetch_assoc(db_exec($sql, $params));
    return $row ? $row : null;
}

// mensajes de exito o error
function flash($type, $msg)
{
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function show_flash()
{
    if (isset($_SESSION['flash'])) {
        echo '<div class="alert alert-' . e($_SESSION['flash']['type']) . '">' . e($_SESSION['flash']['msg']) . '</div>';
        unset($_SESSION['flash']);
    }
}

// postgres devuelve 't' o 'f'
function pg_bool($value)
{
    return $value === 't' || $value === true;
}

function money($value)
{
    return '$ ' . number_format((float) $value, 0, ',', '.');
}
