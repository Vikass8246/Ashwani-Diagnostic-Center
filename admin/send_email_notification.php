<?php
include 'database_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $appointmentId = $_POST['id'];

    // Retrieve appointment details from the database
    $query = "SELECT * FROM booking_appoinment WHERE id = $appointmentId";
    $result = mysqli_query($con, $query);


    

    if ($result && mysqli_num_rows($result) > 0) {
        $appointment = mysqli_fetch_assoc($result);
        $email = $appointment['email'];
        $testDetails = $appointment['selected_tests'];
        $totalPrice = $appointment['total_price'];

        require './PHPMailer-master/src/PHPMailer.php';
        require './PHPMailer-master/src/SMTP.php';
        require './PHPMailer-master/src/Exception.php';

        

        // Send email notification
        $mail = new PHPMailer(true);

        try {
            // Configure SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'vikasyadav826853073@gmail.com'; // Update with your Gmail email
            $mail->Password = 'jwsd nuia txkt omlu'; // Update with your Gmail password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Set sender and recipient
            $mail->setFrom('vikasyadav826853073@gmail.com', 'Ashwani Diagnostic Center'); // Update with your name and email
            $mail->addAddress($email);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Appointment Confirmation';
            $mail->Body = 'Your appointment has been confirmed. <br>';
            $mail->Body .= 'Selected Tests:<br>';
            $mail->Body .= $testDetails;'<br>';// Include test details here
            $mail->Body .= 'Total Price: <br> $' . number_format($totalPrice, 2);

            // Send email
            $mail->send();
            $response = array('success' => true, 'message' => 'Email notification sent successfully.');
        } catch (Exception $e) {
            $response = array('success' => false, 'message' => 'Email notification could not be sent. Error: ' . $mail->ErrorInfo);
        }
    } else {
        $response = array('success' => false, 'message' => 'Appointment not found in the database.');
    }

    echo json_encode($response);
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request.'));
}
?>