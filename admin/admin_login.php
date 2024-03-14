<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Add your styles or link to external stylesheets here -->
</head>
<body>

    <h1>Login</h1>

    <!-- Login form -->
    <form method="post" action="login.php">
        <label for="username">Username:</label>
        <input type="text" name="username" placeholder="Username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" placeholder="Password" required>
        <br>
        <button type="submit" name="login_form" value="1">Login</button>
    </form>

</body>
</html>
