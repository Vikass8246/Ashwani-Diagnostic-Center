<?php
// Include your database connection code here

// Assuming you have a function to connect to the database, replace the following line accordingly
$con = mysqli_connect("sql206.infinityfree.com", "if0_36129170", "yNvjSzlVb7", "if0_36129170_registration");

if (mysqli_connect_errno()) {
    $response = array("error" => "Failed to connect to MySQL: " . mysqli_connect_error());
    echo json_encode($response);
    exit();
}

// Get the appointment ID from the GET request
$appointmentId = $_GET['id'];

// Retrieve appointment details from the database
$appointmentQuery = "SELECT * FROM booking_appoinment WHERE id = $appointmentId";
$appointmentResult = mysqli_query($con, $appointmentQuery);

if ($appointmentResult) {
    $appointmentData = mysqli_fetch_assoc($appointmentResult);

    // Return the data in JSON format
    echo json_encode($appointmentData);
} else {
    $response = array("error" => 'Error retrieving appointment details: ' . mysqli_error($con));
    echo json_encode($response);
}

// Close the database connection
mysqli_close($con);
?>
