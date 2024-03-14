<?php require("database_connection.php")?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Details</title>
</head>
<body>
    <div class="table-responsive">

    <?php

        $query="SELECT * FROM registration";
        $result= mysqli_query($con, $query);

    ?>

    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Password</th>
                <th>EDIT</th>
                <th>DELET</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if(mysqli_num_rows($result)>0)
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
                       ?>           
            <tr>
                <td><?php echo  $row['full_name'];  ?></td>
                <td><?php echo  $row['username'];  ?></td>
                <td><?php echo  $row['email'];  ?></td>
                <td><?php echo  $row['password'];  ?></td>
                
            <tr>

            <?php
                    }
                }
                else
                {
                    echo"No Record Found";
                }
            ?>
        </tbody>

    </table>

</div>    
    
</body>
</html>