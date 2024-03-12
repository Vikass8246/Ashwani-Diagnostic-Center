<?php

require('connection.php');

#for Appoinment
if(isset($_POST['appointment']))
    {
        $name = $_POST['full_name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $date = date_time('Y-m-d h-m-s', strtotime($_POST['date']));
        $test = $_POST['test'];
        $descript = $_POST['descript'];

        $query = "INSERT INTO booking_appoinment(name,email,mobile,date,test,descript) VALUES('$name','$email','$mobile','$date','$test'.'$descript')";
        $result = mysqli_query($con, $query);

        if($result)
        {
            $_SESSION['status'] = "Appoinment Booking Successfull You Wil Be Notify Soon";
            header("Location: appoinment1.php");
        }
        else
        {
            $_SESSION['status'] = "Appoinment Booking Failed";
            header("Location: appoinment1.php");
        }

    }

?>