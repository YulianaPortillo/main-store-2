<?php
/*
use this endpoint to test request

http://localhost/main-store/src/delete_user.php?userID=ANY-ID-NUMBER
*/

session_start();

// Only logged in users can delete users
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.html');
    exit();
}

include('../settings/database.php');

// get data
$user_id = $_GET['userID'] ?? '';

// The logged in user can not delete himself
if ($user_id == $_SESSION['user_id']) {
    echo "<script>alert('You can not delete your own user !!!'); window.location = 'list_users.php';</script>";
    exit();
}

// PREPARE QUERY
$sql_delete_user = "DELETE FROM users WHERE id = $1";
$res = pg_query_params($local_conn, $sql_delete_user, [$user_id]);

// validacion
if ($res && pg_affected_rows($res) > 0) {
    echo "<script>alert('User has been deleted successfully !!!'); window.location = 'list_users.php';</script>";
} else {
    echo "<script>alert('Error: User has not been deleted !!!'); window.location = 'list_users.php';</script>";
}
