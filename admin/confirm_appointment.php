<?php
include 'database_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $appointmentId = $_POST['id'];

    // Update the confirmed column in the database
    $updateQuery = "UPDATE booking_appoinment SET confirmed = 1 WHERE id = $appointmentId";
    $result = mysqli_query($con, $updateQuery);

    if ($result) {
        $response = array('success' => true, 'message' => 'Appointment confirmed successfully.');
    } else {
        $response = array('success' => false, 'message' => 'Failed to confirm appointment.');
    }

    echo json_encode($response);
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request.'));
}
?>
