<?php
require('database_connection.php');

session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo "<script>alert('You are not logged in as an admin.'); window.location.href='admin_login.php';</script>";
    exit();
}

$username = $_SESSION['username'] ?? '';

// Check if the form to add a new user is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    // Validate and sanitize user input
    $name = mysqli_real_escape_string($con, $_POST['full_name']);
    $newUsername = mysqli_real_escape_string($con, $_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Insert the new user into the database
    $insertQuery = "INSERT INTO register (full_name, username, email, password) VALUES ('$name', '$newUsername', '$email', '$password')";

    try {
        if (mysqli_query($con, $insertQuery)) {
            echo "<script>alert('User added successfully.'); window.location.href='admin.php';</script>";
            exit();
        } else {
            throw new Exception(mysqli_error($con));
        }
    } catch (Exception $e) {
        // Check if the error is due to a duplicate entry (username or email)
        if (mysqli_errno($con) == 1062) {
            echo "<script>alert('User with username $newUsername and $email is already registered.');</script>";
        } else {
            echo "<script>alert('Error adding user: " . $e->getMessage() . "')</script>";
        }
    }
}

// Display user details and delete links
$query = "SELECT * FROM register";
$result = mysqli_query($con, $query);

if (!$result) {
    echo 'Error retrieving user details: ' . mysqli_error($con);
    exit();
}

$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Calculate total number of users
$totalUsers = count($users);

// Check if a duplicate alert is set in the session
if (isset($_SESSION['duplicate_alert'])) {
    echo "<script>alert('{$_SESSION['duplicate_alert']}')</script>";
    unset($_SESSION['duplicate_alert']); // Clear the session variable
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ... (your existing head content) ... -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"></script>
    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <link rel="stylesheet" href="https://adcenter.great-site.net/admin/style.css">
    <title>Admin Panel</title>
    <style>
        <!-- ... (your existing styles) ... -->
    </style>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        document.addEventListener("DOMContentLoaded", function () {
            // Function to toggle visibility of sections
            function toggleSection(sectionId) {
                const sections = ['dashboard', 'userDetails', 'appointmentDetails', 'addAppointment'];
                sections.forEach(function (section) {
                    document.getElementById(section).style.display = (section === sectionId) ? 'block' : 'none';
                });
            }

            // Function to toggle visibility of form and user details table
            function toggleFormVisibility() {
                const form = document.getElementById('addUserForm');
                const userDetailsTable = document.getElementById('userDetailsTable');
                form.style.display = (form.style.display === 'none') ? 'block' : 'none';
                userDetailsTable.style.display = (userDetailsTable.style.display === 'none') ? 'block' : 'none';
            }

            // Add event listeners for navigation links
            document.getElementById('dashboardLink').addEventListener('click', function () {
                toggleSection('dashboard');
            });

            document.getElementById('userDetailsLink').addEventListener('click', function () {
                toggleSection('userDetails');
            });

            document.getElementById('appointmentDetailsLink').addEventListener('click', function () {
                toggleSection('appointmentDetails');
            });

            document.getElementById('addAppointmentLink').addEventListener('click', function () {
                toggleSection('addAppointment');
            });

            // Add event listener for "Add User" button
            document.getElementById('addUserButton').addEventListener('click', function () {
                toggleFormVisibility();
            });

            // Function to update pie chart data
            function updatePieChart(totalUsers, appointmentsToday, appointmentsThisMonth) {
                const ctx = document.getElementById('pieChart').getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Total Users', 'Appointments Today', 'Appointments This Month'],
                        datasets: [{
                            data: [totalUsers, appointmentsToday, appointmentsThisMonth],
                            backgroundColor: ['#4caf50', '#2196f3', '#ff9800']
                        }]
                    }
                });
            }

            // Example data (replace with actual data from your server)
            const appointmentsToday = 5;
            const appointmentsThisMonth = 30;

            // Initial update of pie chart
            updatePieChart(<?php echo $totalUsers; ?>, appointmentsToday, appointmentsThisMonth);
        });

        // Automatic logout on browser back button click
        window.addEventListener('popstate', function (event) {
    if (event.state && event.state.page) {
        window.location.reload();
    }
});

    </script>
</head>

<body>

    <header>
        <!-- ... (your existing header content) ... -->
        <nav>
            <a id="dashboardLink" href="#">Dashboard</a>
            <a id="userDetailsLink" href="#">User Details</a>
            <a id="appointmentDetailsLink" href="#">Booked Appointment Details</a>
            <a id="addAppointmentLink" href="#">Book Appointment for Patient</a>
            <a href="logout.php">Log Out</a>
        </nav>
    </header>

    <section id="dashboard">
    <h2>Dashboard</h2>

    <div id="userAppointmentDonutChart" class="apex-charts" dir="ltr"></div>

    <ul class="list-group list-group-flush border-dashed mb-0 mt-3 pt-2">
        <li class="list-group-item px-0">
            <div class="d-flex">
                <div class="flex-shrink-0 avatar-xs">
                    <span class="avatar-title bg-light p-1 rounded-circle">
                        <img src="path/to/your/icon.png" class="img-fluid" alt="Total Users">
                    </span>
                </div>
                <div class="flex-grow-1 ms-2">
                    <h6 class="mb-1">Total Users</h6>
                    <p class="fs-13 mb-0 text-muted"><i class="mdi mdi-circle fs-10 align-middle text-primary me-1"></i>Users</p>
                </div>
                <div class="flex-shrink-0 text-end">
                    <h6 class="mb-1"><?php echo count($users); ?></h6>
                </div>
            </div>
        </li><!-- end -->

        <li class="list-group-item px-0 pb-0">
            <div class="d-flex">
                <div class="flex-shrink-0 avatar-xs">
                    <span class="avatar-title bg-light p-1 rounded-circle">
                        <img src="path/to/your/icon.png" class="img-fluid" alt="Booked Appointments">
                    </span>
                </div>
                <div class="flex-grow-1 ms-2">
                    <h6 class="mb-1">Booked Appointments</h6>
                    <p class="fs-13 mb-0 text-muted"><i class="mdi mdi-circle fs-10 align-middle text-success me-1"></i>Appointments</p>
                </div>
                <div class="flex-shrink-0 text-end">
                    <h6 class="mb-1"><?php echo $totalAppointments; // Use the actual variable storing appointments count ?></h6>
                </div>
            </div>
        </li><!-- end -->
    </ul><!-- end -->

</section>

    <section id="userDetails" style="display: none;">
        <h2>User Details</h2>
        <!-- Your existing user details table -->
        <table id="userDetailsTable">
            <!-- ... (your existing table content) ... -->
            <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                        <a href="delete_user.php?id=<?php echo $user['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
        </table>
        <!-- Form to add a new user -->
        <form id="addUserForm" method="post" action="" style="display: none;">
            <label for="full_name">Name:</label>
            <input type="text" name="full_name" required>
            <br>
            <label for="username">User Name:</label>
            <input type="text" name="username" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <br>
            <button type="submit" name="add_user">Add User</button>
        </form>
        <button id="addUserButton">Add User</button>
    </section>

    <!-- Add a popup div for editing -->
<div id="editPopup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 20px; border: 1px solid #ccc; z-index: 1000;">
    <span style="float: right; cursor: pointer;" onclick="closeEditPopup()">Close &#10006;</span>
    <h2>Edit Appointment</h2>
    <!-- The content of the edit form will go here -->
</div>

<section id="appointmentDetails" style="display: none;">
    <h2>Appointment Details</h2>

    <?php
    // Retrieve and display appointment details
    $appointmentQuery = "SELECT * FROM booking_appoinment"; // Update the table name if needed
    $appointmentResult = mysqli_query($con, $appointmentQuery);

    if ($appointmentResult) {
        $appointments = mysqli_fetch_all($appointmentResult, MYSQLI_ASSOC);

        // Display appointment details in a table
        echo "<table border='1'>";
        echo "<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Appointment Date Time</th><th>Selected Tests</th><th>Total Price</th><th>Actions</th></tr></thead>";
        echo "<tbody>";
        foreach ($appointments as $appointment) {
            echo "<tr id='row{$appointment['id']}'>";
            echo "<td>{$appointment['id']}</td>";
            echo "<td id='name{$appointment['id']}'>{$appointment['name']}</td>";
            echo "<td id='email{$appointment['id']}'>{$appointment['email']}</td>";
            echo "<td id='phone{$appointment['id']}'>{$appointment['phone']}</td>";
            echo "<td id='datetime{$appointment['id']}'>{$appointment['appointment_date_time']}</td>";
            echo "<td id='tests{$appointment['id']}'>{$appointment['selected_tests']}</td>";
            echo "<td id='price{$appointment['id']}'>{$appointment['total_price']}</td>";
            echo "<td>
                    <button onclick=\"confirmAppointment({$appointment['id']})\">Appointment Confirmed</button>
                    <button onclick=\"editAppointment({$appointment['id']})\">Edit</button>
                    <button onclick=\"deleteAppointment({$appointment['id']})\">Delete</button>
                </td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo 'Error retrieving appointment details: ' . mysqli_error($con);
    }
    ?>

    <!-- JavaScript functions for handling actions -->
    <script>
        function openEditPopup(appointmentId) {
    // Add logic to populate the popup with existing data
    const name = document.getElementById(`name${appointmentId}`).innerText;
    const email = document.getElementById(`email${appointmentId}`).innerText;
    const phone = document.getElementById(`phone${appointmentId}`).innerText;
    const datetime = document.getElementById(`datetime${appointmentId}`).innerText;
    const tests = document.getElementById(`tests${appointmentId}`).innerText;
    const price = document.getElementById(`price${appointmentId}`).innerText;

    // Populate the popup with existing data
    document.getElementById('editPopup').innerHTML = `
        <h2>Edit Appointment</h2>
        <label for="editName">Name:</label>
        <input type="text" id="editName" value="${name}">
        <label for="editEmail">Email:</label>
        <input type="text" id="editEmail" value="${email}">
        <label for="editPhone">Phone:</label>
        <input type="text" id="editPhone" value="${phone}">
        <label for="editDatetime">Appointment Date Time:</label>
        <input type="text" id="editDatetime" value="${datetime}">
        <label for="editTests">Selected Tests:</label>
        <select id="editTests" class="js-example-basic-multiple" multiple="multiple" onchange="updateTestPrice()">
            <!-- Add the selected tests dynamically based on the current appointment -->
            <?php
            $selectedTests = explode(', ', $tests); // Assuming tests are stored as comma-separated values in the database
            $testsPrices = [
                'CBC' => 250,
                'ESR' => 150,
                'FASTING SUGAR' => 50,
                'POST LUNCH SUGAR' => 50,
                'RT-PCR' => 500,
                'RANDOM SUGAR' => 100,
                'BLOOD GROUP' => 70,
                'URINE CULTURE' => 60,
                'DENGUE NS1' => 50,
                'HBA1C' => 150,
                // Add more test prices as needed
            ];

            foreach ($testsPrices as $test => $price) {
                $isSelected = in_array($test, $selectedTests) ? 'selected' : '';
                echo "<option value='{$test}' data-price='{$price}' {$isSelected}>{$test}</option>";
            }
            ?>
        </select>
        <div id="testPrices"></div>
        <label for="editPrice">Total Price:</label>
        <input type="text" id="editPrice" value="${price}" readonly>
        <button onclick="updateAppointment(${appointmentId})">Update</button>
        <button onclick="closeEditPopup()">Cancel</button>
    `;

    // Display the popup
    document.getElementById('editPopup').style.display = 'block';

    // Initialize the multi-select dropdown
    $('.js-example-basic-multiple').select2();
    
    // Call the updateTestPrice function initially to show the prices for selected tests
    updateTestPrice();
}

function updateTestPrice() {
    const editTestsDropdown = document.getElementById('editTests');
    const testPricesDiv = document.getElementById('testPrices');
    
    // Clear previous prices
    testPricesDiv.innerHTML = '';

    // Iterate through selected options and display their prices
    Array.from(editTestsDropdown.selectedOptions).forEach(option => {
        const testName = option.value;
        const testPrice = option.getAttribute('data-price');
        
    });

    // Update the total price based on selected tests
    updateTotalPrice();
}



        function closeEditPopup() {
            // Close the popup
            document.getElementById('editPopup').style.display = 'none';
        }

        function confirmAppointment(appointmentId) {
            // Add logic to confirm the appointment and trigger the mail
            alert(`Appointment Confirmed for ID: ${appointmentId}`);
            // You can use AJAX to communicate with the server and send an email
        }

        function editAppointment(appointmentId) {
            openEditPopup(appointmentId);
        }

        function updateTotalPrice() {
    const editTestsDropdown = document.getElementById('editTests');
    const editPriceCell = document.getElementById('editPrice');
    const selectedTests = Array.from(editTestsDropdown.selectedOptions).map(option => option.value);
    const testsPrices = {
        'CBC': 250,
        'ESR': 150,
        'FASTING SUGAR': 50,
        'POST LUNCH SUGAR': 50,
        'RT-PCR': 500,
        'RANDOM SUGAR': 100,
        'BLOOD GROUP': 70,
        'URINE CULTURE': 60,
        'DENGUE NS1': 50,
        'HBA1C': 150,
        // Add more test prices as needed
    };
    const totalPrice = selectedTests.reduce((total, test) => total + testsPrices[test], 0);
    editPriceCell.value = totalPrice;
}


        function updateAppointment(appointmentId) {
            // Get the updated values from the edit form
            const newName = document.getElementById('editName').value;
            const newEmail = document.getElementById('editEmail').value;
            const newPhone = document.getElementById('editPhone').value;
            const newDatetime = document.getElementById('editDatetime').value;
            const newTests = document.getElementById('editTests').value;

            // Calculate the updated total price
            const testsPrices = {
                'CBC': 250,
                'ESR': 150,
                'FASTING SUGAR': 50,
                'POST LUNCH SUGAR': 50,
                'RT-PCR': 500,
                'RANDOM SUGAR': 100,
                'BLOOD GROUP': 70,
                'URINE CULTURE': 60,
                'DENGUE NS1': 50,
                'HBA1C': 150,
                // Add more test prices as needed
            };
            const newPrice = newTests.split(', ').reduce((total, test) => total + testsPrices[test], 0);

            // Use AJAX to update the details in the database
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_appointment.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Update the row with the new values
                    const updatedRow = `
                        <td>${appointmentId}</td>
                        <td id='name${appointmentId}'>${newName}</td>
                        <td id='email${appointmentId}'>${newEmail}</td>
                        <td id='phone${appointmentId}'>${newPhone}</td>
                        <td id='datetime${appointmentId}'>${newDatetime}</td>
                        <td id='tests${appointmentId}'>${newTests}</td>
                        <td id='price${appointmentId}'>${newPrice}</td>
                        <td>
                            <button onclick="confirmAppointment(${appointmentId})">Appointment Confirmed</button>
                            <button onclick="editAppointment(${appointmentId})">Edit</button>
                            <button onclick="deleteAppointment(${appointmentId})">Delete</button>
                        </td>
                    `;

                    document.getElementById(`row${appointmentId}`).innerHTML = updatedRow;

                    // Close the popup after updating
                    closeEditPopup();
                }
            };
            xhr.send(`id=${appointmentId}&name=${newName}&email=${newEmail}&phone=${newPhone}&datetime=${newDatetime}&tests=${newTests}&price=${newPrice}`);
        }

        function deleteAppointment(appointmentId) {
            // Add logic to delete the appointment from the database
            const confirmation = confirm("Are you sure you want to delete this appointment?");
            if (confirmation) {
                // Use AJAX to delete the appointment
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `delete_appointment.php?id=${appointmentId}`, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Remove the row from the table
                        document.getElementById(`row${appointmentId}`).remove();
                    }
                };
                xhr.send();
            }
        }
    </script>
</section>





    <section id="addAppointment" style="display: none;">
        <h2>Book Appointment for Patient</h2>
        <!-- Your existing add appointment form here -->
    </section>

</body>

</html>
