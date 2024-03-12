<?php


// require_once 'vendor/autoload.php'; // Adjust the path if necessary

// require_once '../Registration/google-cloud-php-recaptcha-enterprise-main/src/V1/RecaptchaEnterpriseServiceClient.php';
// require_once '../Registration/google-cloud-php-recaptcha-enterprise-main/src/V1/RecaptchaEnterpriseServiceGrpcClient.php';
require_once('connection.php');
session_start();


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include Google Cloud dependencies using Composer
use Google\Cloud\RecaptchaEnterprise\V1\RecaptchaEnterpriseServiceClient;

// ... (Your existing code)

use Google\Cloud\RecaptchaEnterprise\V1\Event;
use Google\Cloud\RecaptchaEnterprise\V1\Assessment;
use Google\Cloud\RecaptchaEnterprise\V1\TokenProperties\InvalidReason;


require_once 'PHPMailer-master/src/PHPMailer.php';
require_once 'PHPMailer-master/src/SMTP.php';
require_once 'PHPMailer-master/src/Exception.php';


function sendMail($to, $v_code, $fullname)
{

    $mail = new PHPMailer;
    try {
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
        $mail->Subject = 'Verification Code';
        $verificationLink = "http://http://adcenter.great-site.net/Registration/verify.php?email=$to&verification_code=$v_code";
        $mail->Body = " Hello, $fullname \n Click the following link to verify your email: $verificationLink ";


        // Send Email
        if (!$mail->send()) {
            throw new Exception($mail->ErrorInfo);
        } else {
            return true;
        }
    } catch (Exception $e) {
        return false;
    }

}





#for login
if (isset($_POST['login'])) {
    $emailOrUsername = $_POST['email_username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM `register` WHERE `email`=? OR `username`=?";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ss", $emailOrUsername, $emailOrUsername);

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            if (mysqli_num_rows($result) == 1) {
                $result_fetch = mysqli_fetch_assoc($result);
                if ($result_fetch['is_verified'] == 1) {
                    if (password_verify($password, $result_fetch['password'])) {
                        #if password matched
                        session_start();
                        $_SESSION['logged_in'] = true;
                        $_SESSION['username'] = $result_fetch['username'];
                        header("Location: ../Registration/User/User Login.php");
                        exit();
                    } else {
                        #if password incorrect
                        echo "<script>alert('Incorrect Password'); window.location.href='index.php';</script>";
                    }
                } else {
                    echo "<script>alert('Email Not Verified'); window.location.href='index.php';</script>";
                }
            } else {
                echo "<script>alert('Email or Username Not Registered'); window.location.href='index.php';</script>";
            }
        } else {
            echo "<script>alert('Cannot Run Query'); window.location.href='index.php';</script>";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }
}



if (isset($_POST['register'])) {

    // Check for empty fields
    if (empty($_POST['fullname']) || empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
        echo "<script>alert('All fields are required'); window.location.href='index.php';</script>";
        exit();
    }

    // Check email format
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format'); window.location.href='index.php';</script>";
        exit();
    }

    // Check for existing email
    $user_exist_query = "SELECT * FROM `register` WHERE `email`='$_POST[email]'";
    $result = mysqli_query($con, $user_exist_query);

    if ($result && mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already taken'); window.location.href='index.php';</script>";
        exit();
    }

    // Check password length
    $password = $_POST['password'];
    if (strlen($password) < 8 || strlen($password) > 16) {
        echo "<script>alert('Password must be between 8 and 16 characters'); window.location.href='index.php';</script>";
        exit();
    }



    $user_exist_query = "SELECT * FROM `register`  WHERE `username`='$_POST[username]' OR `email`='$_POST[email]'";
    // $stmt = mysqli_prepare($con, $query);
    // mysqli_stmt_bind_param($stmt, "ss", $_POST['email_username'], $_POST['email_username']);
    // mysqli_stmt_execute($stmt);
    // $result = mysqli_stmt_get_result($stmt);
    $result = mysqli_query($con, $user_exist_query);


        // Replace 'TOKEN', 'USER_ACTION', and 'YOUR_API_KEY' with actual values
// $token = 'TOKEN';
// $userAction = 'USER_ACTION';
// $apiKey = 'AIzaSyBJwpzFS3rO6b_hDsz74SpSGir0eUvOPGI';

//     // Create the request body
// $requestData = array(
//     'event' => array(
//         'token' => $token,
//         'expectedAction' => $userAction,
//         'siteKey' => '6LcHlUwpAAAAAIg9OC2HCJG2r5BnwkU5SpQEMDRd',
//     )
// );

//     // Convert the array to JSON format
// $jsonData = json_encode($requestData, JSON_PRETTY_PRINT);

//     // Save the JSON data to a file named 'request.json'
// file_put_contents('request.json', $jsonData);

//     // URL for sending the HTTP POST request
// $url = "https://recaptchaenterprise.googleapis.com/v1/projects/ashwani-diagnost-1704825814483/assessments?key=$apiKey";

//     // Set up cURL options
// $curlOptions = array(
//     CURLOPT_URL => $url,
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_POST => true,
//     CURLOPT_POSTFIELDS => $jsonData,
//     CURLOPT_HTTPHEADER => array(
//         'Content-Type: application/json',
//     ),
// );

//     // Initialize cURL session
// $ch = curl_init();
// curl_setopt_array($ch, $curlOptions);

//     // Execute cURL session and get the response
// $response = curl_exec($ch);
// $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

//     // Check the HTTP response code
// if ($httpCode == 200) {
//     echo "Request successfully sent.\n";
//     echo "Response: $response\n";
// } else {
//     echo "Failed to send the request.\n";
//     echo "HTTP Response Code: $httpCode\n";
//     echo "Response: $response\n";
// }

//     // Close cURL session
// curl_close($ch);

//         function create_assessment(
//         string $recaptchaKey,
//         string $token,
//         string $project,
//         string $action
//       ): void {
//         // Create the reCAPTCHA client.
//         // TODO: Cache the client generation code (recommended) or call client.close() before exiting the method.
//         $client = new RecaptchaEnterpriseServiceClient();
//         $projectName = $client->projectName($project);

//             // Set the properties of the event to be tracked.
//         $event = (new Event())
//           ->setSiteKey($recaptchaKey)
//           ->setToken($token);

//             // Build the assessment request.
//         $assessment = (new Assessment())
//           ->setEvent($event);

//             try {
//           $response = $client->createAssessment(
//             $projectName,
//             $assessment
//           );

//               // Check if the token is valid.
//           if ($response->getTokenProperties()->getValid() == false) {
//             printf('The CreateAssessment() call failed because the token was invalid for the following reason: ');
//             printf(InvalidReason::name($response->getTokenProperties()->getInvalidReason()));
//             return;
//           }

//               // Check if the expected action was executed.
//           if ($response->getTokenProperties()->getAction() == $action) {
//             // Get the risk score and the reason(s).
//             // For more information on interpreting the assessment, see:
//             // https://cloud.google.com/recaptcha-enterprise/docs/interpret-assessment
//             printf('The score for the protection action is:');
//             printf($response->getRiskAnalysis()->getScore());
//           } else {
//             printf('The action attribute in your reCAPTCHA tag does not match the action you are expecting to score');
//           }
//         } catch (exception $e) {
//           printf('CreateAssessment() call failed with the following error: ');
//           printf($e);
//         }
//       }

//           // TODO: Replace the token and reCAPTCHA action variables before running the sample.
//       create_assessment(
//          '6LcHlUwpAAAAAIg9OC2HCJG2r5BnwkU5SpQEMDRd',
//          'YOUR_USER_RESPONSE_TOKEN',
//          'ashwani-diagnost-1704825814483',
//          'YOUR_RECAPTCHA_ACTION'
//       );

    if ($result) {
        if (mysqli_num_rows($result) > 0)  #it will be executed if username or email is already taken
        {

            $result_fetch = mysqli_fetch_assoc($result);
            if ($result_fetch["username"] == $_POST["username"]) {
                #error if username already registered
                echo "
                    <script>
                    alert('Username already taken');
                    window.location.href='index.php';
                    </script>
                ";
            } else if ($result_fetch["email"] == $_POST["email"]) {
                #error if Email already registered
                echo "
                        <script>
                        alert('Email already taken');
                        window.location.href='index.php';
                        </script>
                    ";
            }
        } else #it will be executed if no one has taken username or email before
        {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $v_code = bin2hex(random_bytes(16));

            $query = "INSERT INTO `register` (`full_name`, `username`, `email`, `password`,`verification_code`, `is_verified`) VALUES ('$_POST[fullname]','$_POST[username]','$_POST[email]','$password','$v_code','0')";

            if (mysqli_query($con, $query)) {
                if (sendMail($_POST['email'], $v_code, $_POST['fullname'])) {
                    #if data can be inserted and email sent successfully
                    echo "
                    <script>
                        alert('Registration Successfull and verification code hasbeen sent to your email id.');
                        window.location.href='index.php';
                    </script>
                    ";
                } else {
                    #if email cannot be sent
                    echo "
                    <script>
                        alert('Email sending failed');
                        window.location.href='index.php';
                    </script>
                    ";
                }
            } else {
                #if data cannot be inserted
                echo "
                <script>
                    alert('Server Down');
                    window.location.href='index.php';
                </script>
                ";
            }
        }
    } else {
        echo "
        <script>
        alert('Cannot Run Query');
         window.location.href='index.php';
        </script>
        ";
    }
}






?>