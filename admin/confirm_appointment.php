<?php
// Your database connection code here
require('database_connection.php');

$appointmentId = $_GET['id'];

// Update the appointment status to confirmed in the database
$updateQuery = "UPDATE booking_appoinment SET status = 'confirmed' WHERE id = $appointmentId";
mysqli_query($con, $updateQuery);

// Send confirmation email (you can use your existing sendMail function)

header('Location: admin.php'); // Redirect back to the appointment details page
exit();
?>
