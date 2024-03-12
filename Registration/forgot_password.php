<?php
require('connection.php');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer-master/src/PHPMailer.php';
require_once 'PHPMailer-master/src/SMTP.php';
require_once 'PHPMailer-master/src/Exception.php';

// Your sendMail function goes here
function sendMail($to, $reset_token) {
    $mail = new PHPMailer(true);
  
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'vikasyadav826853073@gmail.com';
    $mail->Password = 'jwsd nuia txkt omlu';
    $mail->SMTPSecure = 'PHPMailer::ENCRYPTION_SMTP';
    $mail->Port = 587;

    // Email Configuration
    $mail->setFrom('vikasyadav826853073@gmail.com', 'Ashwani Diagnostic Center');
    $mail->addAddress($to);
    $mail->Subject = 'Password Reset Link From Ashwani Diagnostic Center';
    
    // Constructing the reset token link
    $reset_token = "http://adcenter.great-site.net/Registration/updatepassword.php?email=$to&reset_token=$reset_token";
    
    // Adding expiration information to the email body
    $expiration_time = date('Y-m-d H:i:s', strtotime('+10 minutes')); // Adjust the duration as needed
    $mail->Body = "We received a request from you to reset your password. Click the link below to reset your password:\n$reset_token\n\nThis link will expire on: $expiration_time";
    $mail->isHTML(false); // Using plain text for simplicity
    
    // Send the email
    if (!$mail->send()) {
        return false;
    } else {
        return true;
    }
}

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $reset_token = bin2hex(random_bytes(16));

    // Calculate expiration time (10 minutes from now)
    $expiration_date = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    $query = "UPDATE `register` SET `reset_token`='$reset_token', `resettokenexpire`='$expiration_date' WHERE `email`='$email'";
    $result = mysqli_query($con, $query);

    if ($result) {
        // Send the reset link to the user's email
        if (sendMail($email, $reset_token)) {
            echo "Email sent with password reset instructions. Check your inbox.";
        } else {
            echo "Error sending email. Please try again.";
        }
    } else {
        // Display MySQL error if any
        echo "Error updating reset token: " . mysqli_error($con);
    }
}
?>
<!-- The rest of your HTML form goes here -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';">


    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>

<!-- HTML form for users to enter their email -->
<form method="post" action="forgot_password.php">
    <label for="email">Email:</label>
    <input type="email" name="email" required>
    <button type="submit" name="submit">Reset Password</button>
</form>

</body>
</html>
