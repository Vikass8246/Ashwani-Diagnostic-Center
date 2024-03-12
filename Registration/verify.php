<?php
require("connection.php");


if (isset($_GET['email']) && isset($_GET['verification_code'])) {
    $email = mysqli_real_escape_string($con, $_GET['email']);
    $v_code = mysqli_real_escape_string($con, $_GET['verification_code']);

    $query = "SELECT * FROM `register` WHERE `email`='$email' AND `verification_code`='$v_code'";
    $result = mysqli_query($con, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $result_fetch = mysqli_fetch_assoc($result);

            if ($result_fetch['is_verified'] == 0) {
                $update = "UPDATE `register` SET `is_verified`='1' WHERE `email`='$email'";

                if (mysqli_query($con, $update)) {
                    echo "
                        <script>
                            setTimeout(function(){
                            alert('Email Verification Successful. Redirecting to login page.');
                            window.location.href='index.php';
                            }, 500);  // Alert after 500 milliseconds, then redirect
                        </script>
                    ";

                } else {
                    echo "
                        <script>
                            console.error('Error updating verification status:', " . mysqli_error($con) . ");
                            setTimeout(function(){
                                window.location.href='index.php';
                            }, 1000);
                        </script>
                    ";
                }
            } else {
                echo "
                    <script>
                        console.log('Email Already Verified');
                        setTimeout(function(){
                            window.location.href='index.php';
                        }, 1000);
                    </script>
                ";
            }
        } else {
            echo "
                <script>
                    console.log('No Matching Records Found');
                    setTimeout(function(){
                        window.location.href='index.php';
                    }, 1000);
                </script>
            ";
        }
    } else {
        echo "
            <script>
                console.error('Error executing the verification query:', " . mysqli_error($con) . ");
                setTimeout(function(){
                    window.location.href='index.php';
                }, 1000);
            </script>
        ";
    }
}
?>