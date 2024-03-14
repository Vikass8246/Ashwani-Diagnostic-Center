<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // Redirect to the login page
  header("Location: /admin-login.php");
  exit;
}

// Get the username from the session
$username = $_SESSION['username'];

// Display the admin panel
echo "Welcome, " . $username . "! This is the admin panel.";



// Log out button
echo '<br><br><a href="admin_logout.php">Log Out</a>';
?>
