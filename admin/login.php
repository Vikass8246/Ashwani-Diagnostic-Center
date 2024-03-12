<?php
session_start();

// Connect to the database
$conn = new mysqli("sql206.infinityfree.com", "if0_36129170", "yNvjSzlVb7", "if0_36129170_registration");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the form fields
    if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
        echo "<script>alert('Invalid username.'); window.location.href='/admin/admin_login.php';</script>";
        exit;
    } elseif (strlen($password) < 8) {
        echo "<script>alert('Invalid password.'); window.location.href='/admin/admin_login.php';</script>";
        exit;
    }

    // Get user details from the database
    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE LOWER(username) = LOWER(?)");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        $is_approved = $row['is_approved'];

        // Check if admin is approved
        if ($is_approved == 1) {
            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Store the username in the session
                $_SESSION['admin'] = true; // Set the admin session variable
                $_SESSION['username'] = $username;

                // Redirect to the admin panel page
echo "<script>alert('Login successful.'); window.location.href='admin.php';</script>";
exit;
            } else {
                echo "<script>alert('Invalid password.'); window.location.href='/admin/admin_login.php';</script>";
                exit;
            }
        } elseif ($is_approved == 0) {
            echo "<script>alert('Your admin status is pending approval.'); window.location.href='/admin/admin_login.php';</script>";
            exit;
        } elseif ($is_approved == 2) {
            echo "<script>alert('Your admin request has been rejected. Please contact support.'); window.location.href='/admin/admin_login.php';</script>";
            exit;
        } else {
            echo "<script>alert('Unknown approval status.'); window.location.href='/admin/admin_login.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Username not found.'); window.location.href='/admin/admin_login.php';</script>";
        exit;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
