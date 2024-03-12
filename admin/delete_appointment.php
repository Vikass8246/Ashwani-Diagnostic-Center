<?php
// Your database connection code here
require('database_connection.php');

$appointmentId = $_GET['id'];

// Delete the appointment from the database
$deleteQuery = "DELETE FROM booking_appoinment WHERE id = $appointmentId";
mysqli_query($con, $deleteQuery);

header('Location: admin.php'); // Redirect back to the appointment details page
exit();
?>
