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

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username, email, and password from the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate the form fields
    if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
        echo "Invalid username.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
    } elseif (strlen($username) < 5) {
        echo "Username must be at least 5 characters long.";
    } elseif (strlen($password) < 8) {
        echo "Password must be at least 8 characters long.";
    } else {
        // Check if the username is already taken
        $stmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "
            <script>('Username already exists')window.location.href='admin_login.php';</script>";
        } else {
            // Generate a random verification code
            $verificationCode = bin2hex(random_bytes(16));

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new admin into the database with the verification code
            $stmt = $conn->prepare("INSERT INTO admin_users (username, email, password, verification_code, is_approved) VALUES (?, ?, ?, ?, 0)");
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $verificationCode);
            $stmt->execute();

            // Send verification email to the user
            sendVerificationEmail($username, $email, $verificationCode, 'user');

            // Send verification email to the organization
            sendVerificationEmail($username, $email, $verificationCode, 'organization');

            // Display an alert message using JavaScript
            echo "<script>alert('Your request has been sent to the organization. You will be notified once your admin status is approved. Please wait for approval before logging in.'); window.location.href='admin_login.php';</script>";
        }

        // Close the prepared statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();

// Function to send a verification email to the user or organization
function sendVerificationEmail($username, $email, $verificationCode, $recipientType)
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

        if ($recipientType == 'user') {
            // Send email to the user
            $mail->setFrom('vikasyadav826853073@gmail.com', 'Your Organization Name'); // Replace with your email and organization name
            $mail->addAddress($email); // Replace with user's email
            $mail->isHTML(true);
            $mail->Subject = 'Admin Registration Request';
            $mail->Body = "Thank you for registering as an admin.\n\nPlease wait for approval from the organization. You will be notified once your admin status is approved.";
        } elseif ($recipientType == 'organization') {
            // Send email to the organization
            $mail->setFrom('vikasyadav826853073@gmail.com', 'Your Organization Name'); // Replace with your email and organization name
            $mail->addAddress('vikasyadav826853073@gmail.com'); // Replace with organization's email
            $mail->isHTML(true);
            $mail->Subject = 'New Admin Registration Request';
            $mail->Body = "
            <p>A new admin registration request has been received.</p>
            <p>Username: $username</p>
            <p>Email: $email</p>
            <p>
                Verification Link:
                <a style='display:inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-align: center; text-decoration: none; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 10px background-color 0.3s;' href='http://adcenter.great-site.net//admin/verify.php?code=$verificationCode&action=approve'>Approve</a>
                <a style='display:inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-align: center; text-decoration: none; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 10px background-color 0.3s;' href='http://adcenter.great-site.net//admin/verify.php?code=$verificationCode&action=reject'>Reject</a>
            </p>
        ";}

        // Send Email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
