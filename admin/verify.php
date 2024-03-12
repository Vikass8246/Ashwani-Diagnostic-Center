<?php
// Include PHPMailer autoload file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer-master/src/PHPMailer.php';
require_once 'PHPMailer-master/src/SMTP.php';
require_once 'PHPMailer-master/src/Exception.php';

// Connect to the database
$conn = new mysqli("sql206.infinityfree.com", "if0_36129170", "yNvjSzlVb7", "if0_36129170_registration");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if code parameter is present in the URL
if (isset($_GET['code'])) {
    $verificationCode = $_GET['code'];

    // Retrieve user information based on verification code
    $stmt = $conn->prepare("SELECT id, username, email, is_approved FROM admin_users WHERE verification_code = ?");
    $stmt->bind_param("s", $verificationCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userId = $row['id'];
        $username = $row['username'];
        $email = $row['email'];
        $isApproved = $row['is_approved'];

        // Check if the admin is not already approved
        if (!$isApproved) {
            // Check if the organization clicked on Approve or Reject
            if (isset($_GET['action']) && ($_GET['action'] === 'approve' || $_GET['action'] === 'reject')) {
                $action = $_GET['action'];

                // Update the user's approval status based on the action
                $stmt = $conn->prepare("UPDATE admin_users SET is_approved = ? WHERE id = ?");
                $approvalStatus = ($action === 'approve') ? 1 : 2;
                $stmt->bind_param("ii", $approvalStatus, $userId);
                $stmt->execute();

                // Send approval or rejection email to the admin
                if ($action === 'approve') {
                    sendApprovalEmail($username, $email);
                } elseif ($action === 'reject') {
                    sendRejectionEmail($username, $email);
                }

                // Display an alert message using JavaScript
                echo "<script>alert('Admin $action successful. The user has been notified.');</script>";
            } else {
                // Display an alert message using JavaScript for invalid action
                echo "<script>alert('Invalid action.');</script>";
            }
        } else {
            // Display an alert message using JavaScript
            echo "<script>alert('Admin has already been approved.');</script>";
        }
    } else {
        // Display an alert message using JavaScript
        echo "<script>alert('Invalid verification code.');</script>";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();

// Function to send an approval email to the admin
function sendApprovalEmail($username, $email)
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
        $mail->Subject = 'Admin Approval';
        $mail->Body = "Congratulations, $username!\n\nYour admin status has been approved. You can now log in.";

        // Send Email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Function to send a rejection email to the admin
function sendRejectionEmail($username, $email)
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
        $mail->Subject = 'Admin Rejection';
        $mail->Body = "Dear $username,\n\nWe regret to inform you that your admin status has been rejected. If you have any questions, please contact us.";

        // Send Email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
