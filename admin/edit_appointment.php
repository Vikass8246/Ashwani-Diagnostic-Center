<?php
// Your database connection code here
require('database_connection.php');

$appointmentId = $_GET['id'];

// Fetch the appointment details based on the ID
$fetchQuery = "SELECT * FROM booking_appoinment WHERE id = $appointmentId";
$fetchResult = mysqli_query($con, $fetchQuery);
$appointment = mysqli_fetch_assoc($fetchResult);

// Your HTML form for editing the appointment details goes here
// ...

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Update the appointment details in the database
    // ...

    header('Location: admin.php'); // Redirect back to the appointment details page
    exit();
}
?>
