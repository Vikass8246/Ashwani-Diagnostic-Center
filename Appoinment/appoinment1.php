<?php
// Get form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$selectedTests = $_POST['tests'] ?? [];
$appointmentdatetime = $_POST['appointment_date_time'] ?? '';

$con = mysqli_connect('sql206.infinityfree.com', 'if0_36129170', 'yNvjSzlVb7', 'if0_36129170_registration');

// // Check if the email and phone already exist
// $nameCheckQuery = "SELECT id FROM booking_appoinment WHERE name = '$name'";
// $nameCheckResult = mysqli_query($con, $nameCheckQuery);

// if (mysqli_num_rows($nameCheckResult) > 0) {
//   // Record with this name and phone already exists, display alert
//   echo "<script>alert('Appointment with this same name  already exists.'); window.location.href='index.php';</script>";
// } else {
  // Serialize selected tests and store them in the same row
  $serializedTests = implode(', ', $selectedTests);

  // Calculate total price based on selected tests
  $totalPriceQuery = "SELECT SUM(price) AS total_price FROM tests WHERE test_name IN ('$serializedTests')";
  $totalPriceResult = mysqli_query($con, $totalPriceQuery);
  $totalPriceRow = mysqli_fetch_assoc($totalPriceResult);
  $totalPrice = $totalPriceRow['total_price'] ?? 0;
  $totalPrice = $_POST['total_price'] ?? 0;

  // File upload handling (if a file is uploaded)
  if (!empty($_FILES['file']['name'])) {
    $uploadedFileName = $_FILES['file']['name'];
    $uploadedFilePath = $_FILES['file']['tmp_name'];
    $fileExtension = pathinfo($uploadedFileName, PATHINFO_EXTENSION);

    // Convert file content to binary for database storage
    $fileContent = file_get_contents($uploadedFilePath);
    $fileContent = mysqli_real_escape_string($con, $fileContent);
  } else {
    $uploadedFileName = null;
    $fileExtension = null;
    $fileContent = null;
  }

  // Insert appointment data into the database, including file content
  $query = "INSERT INTO booking_appoinment (name, email, phone, appointment_date_time, selected_tests, total_price, file_name, file_extension, file_content) VALUES ('$name', '$email', '$phone', '$appointmentdatetime', '$serializedTests', '$totalPrice', '$uploadedFileName', '$fileExtension', '$fileContent')";
  mysqli_query($con, $query);
  $appointmentId = mysqli_insert_id($con); // Get the last inserted appointment ID

  // Set the appointment ID in the session
  $_SESSION['appointment_id'] = $appointmentId;

  if ($totalPriceRow !== null) {
    // Update total price in the booking_appointment table
    $updateTotalPriceQuery = "UPDATE booking_appoinment SET total_price = '$totalPrice' WHERE id = '$appointmentId'";
    mysqli_query($con, $updateTotalPriceQuery);
  } else {
    echo 'Error calculating total price';
  }

  if ($appointmentId) {
    if (sendMail($email, $name, $appointmentdatetime, $selectedTests, $totalPrice)) {
      // Redirect to a confirmation page
      header('Location: confirmation.html');
    } else {
      echo 'Error sending email';
    }
  } else {
    echo 'Error generating Appointment ID';
  }


// Send email notification
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($email, $name, $appointmentdatetime, $selectedTests, $totalPrice)
{
  require './PHPMailer-master/src/PHPMailer.php';
  require './PHPMailer-master/src/SMTP.php';
  require './PHPMailer-master/src/Exception.php';

  $mail = new PHPMailer(true);

  try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'vikasyadav826853073@gmail.com';
    $mail->Password = 'jwsd nuia txkt omlu';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('vikasyadav826853073@gmail.com', 'Ashwani Diagnostic Center');
    $mail->addAddress($email);
    $mail->Subject = 'Appointment Booking Confirmation';
    $message = "Dear $name,\n\nYour appointment has been booked on $appointmentdatetime.\n\nSelected Tests:\n";
    foreach ($selectedTests as $test) {
      $message .= "- $test\n";
    }
    $message .= "\nTotal Price: ₹ $totalPrice. /--\n\nThank you for choosing our service.";

    $mail->Body = $message;

    // Send the email
    $mail->send();

    return true;
  } catch (Exception $e) {
    return false;
  }
}
?>