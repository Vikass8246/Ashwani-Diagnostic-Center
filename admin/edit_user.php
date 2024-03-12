<?php
require('database_connection.php');

session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("location: login.php"); // Redirect to login page if not logged in as admin
    exit();
}

if (isset($_GET['id'])) {
    $userId = mysqli_real_escape_string($con, $_GET['id']);

    $query = "SELECT * FROM register WHERE id = '$userId'";
    $result = mysqli_query($con, $query);

    if (!$result) {
        echo 'Error retrieving user details: ' . mysqli_error($con);
        exit();
    }

    $user = mysqli_fetch_assoc($result);

    // Rest of the edit_user.php code
} else {
    echo 'User ID not provided.';
}
?>
