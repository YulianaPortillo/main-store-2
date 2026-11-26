<?php
/*
use this endpoint to test request

http://localhost/main-store/src/update_user_status.php?userID=ANY-ID-NUMBER
*/

session_start();

// Only logged in users can change the status
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.html');
    exit();
}

include('../settings/database.php');

// get data
$user_id = $_GET['userID'] ?? '';

// The logged in user can not disable himself
if ($user_id == $_SESSION['user_id']) {
    echo "<script>alert('You can not disable your own user !!!'); window.location = 'list_users.php';</script>";
    exit();
}

// PREPARE QUERY (active -> inactive, inactive -> active)
$sql_update_user = "UPDATE users SET status = NOT status, updated_at = NOW() WHERE id = $1";
$res = pg_query_params($local_conn, $sql_update_user, [$user_id]);

// validacion
if ($res && pg_affected_rows($res) > 0) {
    echo "<script>alert('User status has been updated successfully !!!'); window.location = 'list_users.php';</script>";
} else {
    echo "<script>alert('Error: User status has not been updated !!!'); window.location = 'list_users.php';</script>";
}
