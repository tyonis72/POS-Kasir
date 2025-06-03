<?php
session_start(); // Mulai session

// update status login di database
require_once '../config/config.php';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "UPDATE tbl_user SET status = 'offline' WHERE userid = '$user_id'";
    mysqli_query($connection, $query);
}

session_unset();      // Hapus semua data session
session_destroy();    // Hancurkan session

header("Location: login.php"); // Redirect ke halaman login
exit();
?>
