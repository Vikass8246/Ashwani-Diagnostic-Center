<?php
require('database_connection.php');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the appointment ID and updated values from the POST data
    $appointmentId = $_POST['id'];
    $newName = mysqli_real_escape_string($con, $_POST['name']);
    $newEmail = mysqli_real_escape_string($con, $_POST['email']);
    $newPhone = mysqli_real_escape_string($con, $_POST['phone']);
    $newDatetime = mysqli_real_escape_string($con, $_POST['datetime']);
    $newTests = mysqli_real_escape_string($con, $_POST['tests']);
    $newPrice = mysqli_real_escape_string($con, $_POST['price']);

    // Update the appointment details in the database
    $updateQuery = "UPDATE booking_appoinment SET 
                    name='$newName', 
                    email='$newEmail', 
                    phone='$newPhone', 
                    appointment_date_time='$newDatetime', 
                    selected_tests='$newTests', 
                    total_price='$newPrice' 
                    WHERE id='$appointmentId'";

    if (mysqli_query($con, $updateQuery)) {
        echo "Appointment updated successfully";
    } else {
        echo "Error updating appointment: " . mysqli_error($con);
    }
} else {
    // If the request is not a POST request, redirect or handle accordingly
    echo "Invalid request method";
}
?>
