<?php
// Include PHPMailer autoload file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer-master/src/PHPMailer.php';
require_once 'PHPMailer-master/src/SMTP.php';
require_once 'PHPMailer-master/src/Exception.php';

if (isset($_GET['username']) && isset($_GET['action'])) {
    $username = $_GET['username'];
    $action = $_GET['action'];

    // Function to send an email to the admin
    function sendEmail($username, $email, $action)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'vikasyadav826853073@gmail.com';
            $mail->Password = 'jwsd nuia txkt omlu';
            $mail->SMTPSecure = 'PHPMailer::ENCRYPTION_SMTP';
            $mail->Port = 587;

            $mail->setFrom('vikasyadav826853073@gmail.com', 'Your Organization Name'); // Replace with your email and organization name
            $mail->addAddress($email); // Replace with admin's email
            $mail->isHTML(true);

            if ($action === 'approve') {
                $mail->Subject = 'Admin Approval';
                $mail->Body = "Congratulations, $username!\n\nYour admin status has been approved. You can now log in.";
            } elseif ($action === 'reject') {
                $mail->Subject = 'Admin Rejection';
                $mail->Body = "Dear $username,\n\nWe regret to inform you that your admin status has been rejected. If you have any questions, please contact us.";
            }

            // Send Email
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Retrieve user email based on username
    $conn = new mysqli("localhost", "root", "", "registration");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT email FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        // Send email to the admin
        sendEmail($username, $email, $action);
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
}
?>
