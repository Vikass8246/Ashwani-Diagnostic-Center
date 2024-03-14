<?php

$con=mysqli_connect("localhost","root","","registration");

if(mysqli_connect_error())
{
    echo"<script>alert('Cannot connect to the databse');</script>";
    exit();
}

?>
<?php
function openConnection() {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "registration";

    $con = mysqli_connect($host, $username, $password, $database);

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $con;
}

function closeConnection($con) {
    mysqli_close($con);
}
?>
