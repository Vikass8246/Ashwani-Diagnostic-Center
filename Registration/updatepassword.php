<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Upadate</title>
    <style>
    *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    text-decoration: none;
    font-family: 'Poppins' sans-serif;
    }
    form{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        background-color: #f0f0f0;
        width: 350px;
        border-radius: 5px;
        padding: 20px 25px 30px 25px; 
    }
    form h3{
        margin-bottom: 15px;
        color: #30475e;
    }
    form input{
        width: 100%;
    height: 50px;
    flex: 5;
    background: transparent;
    border: 2px solid #df4adf;
    border-radius: 50px;
    outline: none;
    font-size: 18px;
    font-weight: 600;
    color: #444;
    align-items: center;
}
form button{
    width: 150px;
    height: 50px;
    border: none;
    border-radius: 50px;
    background: #df4adf;
    color: #fff;
    font-weight: 600;
    margin: 10px 0;
    text-transform: uppercase;
    cursor: pointer;
}
    

    </style>
</head>
<body>
<?php
require('connection.php');

if (isset($_GET['email']) && isset($_GET['reset_token'])) {
    date_default_timezone_set('Asia/Kolkata');
    $date = date("Y-m-d H:i:s");
    $email = mysqli_real_escape_string($con, $_GET['email']);
    $reset_token = mysqli_real_escape_string($con, $_GET['reset_token']);

    $query = "SELECT * FROM `register` WHERE `email`='$email' AND `reset_token`='$reset_token' AND `resettokenexpire` > '$date'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        // Valid reset token, show password update form
        echo "
        <form method='POST'>
            <h3>Create New Password</h3>
            <input type='password' placeholder='New Password' name='Password' required>
            <button type='submit' name='updatepassword'>UPDATE</button>
            <input type='hidden' name='email' value='$email'>
        </form>
        ";
    } else {
        echo "Invalid or expired reset link. Please try again.";
    }
}

if (isset($_POST['updatepassword'])) {
    $pass = password_hash($_POST['Password'], PASSWORD_BCRYPT);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $update = "UPDATE `register` SET `password`='$pass',`reset_token`=NULL,`resettokenexpire`=NULL WHERE `email`='$email'";
    if (mysqli_query($con, $update)) {
        echo "Password Updated Successfully. <a href='index.php'>Login</a>";
    } else {
        echo "Error updating password. Please try again.";
    }
}
?>

    
</body>
</html>