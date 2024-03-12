<?php
require('database_connection.php');

session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("location: login.php"); // Redirect to login page if not logged in as admin
    exit();
}

if (isset($_GET['id'])) {
    $userId = mysqli_real_escape_string($con, $_GET['id']);

    $query = "DELETE FROM register WHERE id = '$userId'";
    $result = mysqli_query($con, $query);

    if (!$result) {
        echo 'Error deleting user: ' . mysqli_error($con);
    } else {
        echo 'User deleted successfully.';
    }
} else {
    echo 'User ID not provided.';
}
?>
