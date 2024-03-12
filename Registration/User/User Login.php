<?php 
    require('dbconnection.php');
    session_start();
    session_regenerate_id(true);
    if(!isset($_SESSION['username']))
    {
    header("location:  ../Registration/index.php");
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self';">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
    <style>
        body{
            margin: 0px;
        }
        div.header{
            font-family:poppins;
            display: flex;
            justify-content: space-between;
            align-items:center;
            padding: 0px 60px;
            background-color: lightblue;
        }
        div.header button{
            background-color: #f0f0f0;
            font-size: 16px;
            font-weight:550;
            padding: 8px 12px;
            border: 2px solid black;
            border-radius: 10px;

        }
        
.action
{
position: fixed;
top: 20px;
right: 30px;
}
.action .profile{
    position: relative;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    cursor: pointer;
}
.action .profile img{
    position: absolute;
    top: 100;
    left: 30;
    bottom: auto;
    width: 40px;
    height: 55px;
    object-fit: cover;
}
.action .menu{
    position: absolute;
    top: 80px;
    right: -10px;
    padding: 10px 20px;
    background: #fff;
    width: 200px;
    box-sizing: 0 5px 25px rgba(0,0,0,0.1);
    border-radius: 15px;
    transition: 0.5s;
    visibility: hidden;
    opacity: 0;
}
.action .menu.active{
    top: 80px;
    visibility: visible;
    opacity: 1;
}
.action .menu::before{
    content: '';
    position: absolute;
    top: -5px;
    right: 28px;
    height: 20px;
    width: 20px;
    background: #fff;
    transform: rotate(45deg);
}
.action .menu ul li{
    list-style: none;
    padding: 10px 0;
    border-top: 1px solid rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    justify-content: center;
}
.action .menu ul li a{
    display: inline-block;
    text-decoration: none;
    color: #24d6f5;
    font-weight: 500;
    transition: 0.5s;
}
.action .menu ul li:hover a{
    color: #ff5d94;
}

        </style>
</head>
<body>
    <div class="header">
        <h1>Welcome To User Panel- <?php echo $_SESSION['username']?></h1>
        <form method="POST" action=" <?php ($_SERVER['PHP_SELF']) ?> ">
        <button name="My_Acount">My Account</button>
        <button name="Change_Password">Change Password</button>   
        <button name="Appoinment" >Book An Appoinment</button>
        <button name="Logout">Log Out</button>

        <div class="action">
            <div class="profile" onclick="menuToggle();">
                <img src="img/favicon.png">
            </div>
            <div class="menu">
                <ul>
                    <li>
                        <a href="#">My Profile</a>
                    </li>
                    <li>
                        <a href="#">Edit Profile</a>
                    </li>
                    <li>
                        <a href="#">Setting</a>
                    </li>
                    <li>
                        <a href="#">Help</a>
                    </li>
                </ul>
            </div>
    
        </div>
        
    </div>
        
        

    
    
    </form>

    </div>



    <?php
if(isset($_POST['Change_Password']))
{
        header("location: ../forgot_password.php");
}


if(isset($_POST['Logout']))
{
    session_destroy();
    header("location: ../index.php");
}
if(isset($_POST['Appoinment']))
{
    header("location: /Appoinment/appoinment1.php");
}

?>

<script>
    function menuToggle(){
        const toggleMenu = document.querySelector('.menu');
        toggleMenu.classList.toggle('active')
    }
</script>
</body>
</html>