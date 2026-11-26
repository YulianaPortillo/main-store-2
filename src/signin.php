<?php
session_start();

// If the user is already logged in, go to home
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}

// This page only receives the login form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signin.html');
    exit();
}

// Get database connection
include('../settings/database.php');

// Get payload from frontend
$email = trim($_POST['email'] ?? '');
$passwd = $_POST['pswd'] ?? '';

$sql_check_email = "
    SELECT id, firstname, lastname, password, status
    FROM users
    WHERE email = $1
";

$res = pg_query_params($local_conn, $sql_check_email, [$email]);
$row = pg_fetch_assoc($res);

if ($row && $row['status'] === 't' && password_verify($passwd, $row['password'])) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user_firstname'] = $row['firstname'];
    $_SESSION['user_lastname'] = $row['lastname'];
    header('Location: home.php');
    exit();
}

echo "<script>alert('Invalid email or password. Try again !!!'); window.location = 'signin.html';</script>";
