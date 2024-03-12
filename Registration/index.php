<?php
require('./connection.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
  <!-- <script src="https://www.google.com/recaptcha/enterprise.js?render=6LeoVUspAAAAAGZcz-UAHls0lUIWGWkt30Z-WBNb"></script>
  <script src="https://www.google.com/recaptcha/enterprise.js?render=6LcHlUwpAAAAAIg9OC2HCJG2r5BnwkU5SpQEMDRd"></script> -->
  <link rel="stylesheet" href="css/registration.css">
  <title>Sign in And Sign Up</title>
  <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
</head>

<body>




  <div class="container">
    <div class="signin-signup">
      <form action="login_register.php" class="sign-in-form" method="POST">
        <h2 class="title">Sign in</h2>

        <div class="input-field">
          <i class="fas fa-user"></i>
          <input type="text" placeholder="Username" name="email_username" required>
        </div>
        <div class="input-field">
          <i class="fas fa-lock"></i>
          <input type="password" placeholder="Password" name="password" required>
        </div>
        <input type="submit" class="btn" value="Sign in" name="login">
        <button type="button" class="btn forgot" onClick="myOnClick()">Forgot Password ?</button>


        <p class="social-text">Or Sign in with social platform</p>
        <div class="social-media">
          <a href="login_with_facebook.php" class="social-icon">
            <i class="fab fa-facebook"></i>
          </a><a href="login_with_twitter.php" class="social-icon">
            <i class="fab fa-twitter"></i>
          </a><a href="login_with_google.php" class="social-icon">
            <i class="fab fa-google"></i>
          </a><a href="login_with_linkedin.php" class="social-icon">
            <i class="fab fa-linkedin-in"></i>
          </a>
        </div>

        <br>
        <!-- <a href="../admin/admin.php" class="admin" name="adminlogin"><span class="highlight">Admin
            login</span></a> -->
        <button type="button" class="btn admin highlight" name="adminlogin" onclick="adminOnclick()">Admin Login</button>
        <p class="account-text">Don't have an account? <a href="#" id="sign-up-btn2">Sign up</a>
        </p>
      </form>


      <form action="login_register.php" class="sign-up-form" method="POST" onsubmit="return validateForm();">
        <h2 class="title">Sign up</h2>
        <div class="input-field">
          <i class="fas fa-user"></i>
          <input type="text" placeholder="Full Name" name="fullname" required>
        </div>
        <div class="input-field">
          <i class="fas fa-user"></i>
          <input type="text" placeholder="Username" name="username" required>
        </div>
        <div class="input-field">
          <i class="fas fa-envelope"></i>
          <input type="text" placeholder="Email" name="email" required>
        </div>
        <div class="input-field">
          <i class="fas fa-phone"></i>
          <input type="tel" placeholder="Mobile Number" name="mobile_number" required>
        </div>
        <div class="input-field">
          <i class="fas fa-lock"></i>
          <input type="password" placeholder="Password" name="password" required>
        </div>
        <div class="input-field">
          <i class="fas fa-lock"></i>
          <input type="password" placeholder="Confirm Password" name="confirm_password" required>
        </div>
        <!-- Add this inside your form -->
        <!-- <div class="g-recaptcha" data-sitekey="6LcHlUwpAAAAAIg9OC2HCJG2r5BnwkU5SpQEMDRd" data-action="LOGIN"></div> -->

        <input type="submit" class="btn" value="Sign Up" name="register">
        <p class="social-text">Or Sign in with social platform</p>
        <div class="social-media">
          <a href="login_with_facebook.php" class="social-icon">
            <i class="fab fa-facebook"></i>
          </a><a href="login_with_twitter.php" class="social-icon">
            <i class="fab fa-twitter"></i>
          </a><a href="login_with_google.php" class="social-icon">
            <i class="fab fa-google"></i>
          </a><a href="login_with_linkedin.php" class="social-icon">
            <i class="fab fa-linkedin-in"></i>
          </a>
        </div>
        <p class="account-text">Already have an account? <a href="#" id="sign-in-btn2">Sign in</a>

      </form>




    </div>
    <div class="panels-container">
      <div class="panel left-panel">
        <div class="content">
          <h3>Member of Us?</h3>
          <p>Ashwani Diagnostic Center.</p>
          <button class="btn" id="sign-in-btn">Sign in</button>
        </div>
        <img src="img/signin.jpg" alt="" class="image">
      </div>
      <div class="panel right-panel">
        <div class="content">
          <h3>New to Us?</h3>
          <p>Ashwani Diagnostic Center.</p>
          <button class="btn" id="sign-up-btn">Sign Up</button>
        </div>
        <img src="img/signup.jpg" alt="" class="image">
      </div>
    </div>
  </div>


  <script>
    function myOnClick() {
  window.location.href = "forgot_password.php";
}
    function adminOnclick() {
  window.location.href = "../admin/admin_login.php";
}

function myOnClick() {
  console.log("Redirecting to forgot_password.php");
  window.location.href = "forgot_password.php";
}

function adminOnclick() {
  console.log("Redirecting to ../admin/admin_login.php");
  window.location.href = "../admin/admin_login.php";
}
  </script>






  <!--Script-->
  <script src="js/registration.js"></script>

  <!-- ... Existing code ... -->

  <script src="js/registration.js"></script>
  <script>
    function validateForm() {
      var password = document.querySelector('.sign-up-form input[name="password"]').value;
      var confirmPassword = document.querySelector('.sign-up-form input[name="confirm_password"]').value;
      var email = document.querySelector('.sign-up-form input[name="email"]').value;
      var mobileNumber = document.querySelector('.sign-up-form input[name="mobile_number"]').value;

      // Password validation
    if (!isStrongPassword(password)) {
        alert("Password must be between 8 and 16 characters and include at least one special character.");
        return false;
    }

    // Confirm Password validation
    if (password !== confirmPassword) {
        alert("Passwords do not match");
        return false;
    }

      // Email validation
      var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!email.match(emailRegex)) {
        alert("Enter a valid email address");
        return false;
      }

      // Mobile number validation
      var mobileRegex = /^[0-9]{10}$/;
      if (!mobileNumber.match(mobileRegex)) {
        alert("Enter a valid 10-digit mobile number");
        return false;
      }

      return true;
    }

    function isStrongPassword(password) {
    // Password must be between 8 and 16 characters and include at least one special character
    var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,16}$/;
    return password.match(passwordRegex);
}
  </script>
</body>

</html>

</body>

</html>