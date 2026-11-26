<?php
// This page only receives the register form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signup.html');
    exit();
}

// Get database connection
include('../settings/database.php');

// Get payload from frontend
$first_name = trim($_POST['fname'] ?? '');
$last_name = trim($_POST['lname'] ?? '');
$ident_number = trim($_POST['idnumber'] ?? '');
$mobile_phone = trim($_POST['mphone'] ?? '');
$email = trim($_POST['email'] ?? '');
$passwd = password_hash($_POST['pswd'] ?? '', PASSWORD_DEFAULT);
$photo_url = 'photos/photo_defaul.png';

// Email and identification number can not be repeated
$sql_check_user = "SELECT id FROM users WHERE email = $1 OR ident_number = $2";
$res = pg_query_params($local_conn, $sql_check_user, [$email, $ident_number]);

if (pg_num_rows($res) > 0) {
    echo "<script>alert('Email or identification number already exists. Try again !!!'); window.location = 'signup.html';</script>";
    exit();
}

// Prepare query
$sql = "
    INSERT INTO users (firstname, lastname, ident_number, email, password, url_photo, mobile_phone)
    VALUES ($1, $2, $3, $4, $5, $6, $7)
";

// Execute query
$res = pg_query_params($local_conn, $sql, [
    $first_name,
    $last_name,
    $ident_number,
    $email,
    $passwd,
    $photo_url,
    $mobile_phone
]);

if ($res) {
    echo "<script>alert('User has been created successfully !!!'); window.location = 'signin.html';</script>";
} else {
    echo "<script>alert('Error: User has not been created !!!'); window.location = 'signup.html';</script>";
}
