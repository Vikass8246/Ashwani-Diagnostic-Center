<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Booking Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <style>
        /* Style for the searchable dropdown */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
        }

        label {
            margin-top: 5px;
            display: block;
            margin-bottom: 5px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            margin-top: 5px;
        }

        .select2-container--default .select2-selection--multiple {
            width: 100%;
            padding-top: 4px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .select2-container--default .select2-results__option {
            font-size: 14px;
            /* Adjust the font size as needed */
        }

        select {
            min-height: 100px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        #totalPrice {
            font-weight: bold;
            font-size: 16px;
            display: block;
            margin-top: 10px;
        }

        @media (max-width: 600px) {
            form {
                max-width: 100%;
            }

            select {
                width: 100%;
            }
        }
    </style>
    <script>
        // JavaScript function to calculate the total price
        function calculateTotal() {
            var testsSelect = document.getElementById('tests');
            var totalPriceDisplay = document.getElementById('totalPrice');
            var appointmentDateTimeInput = document.getElementById('appointment_date_time');

            var selectedTests = testsSelect.selectedOptions;
            var total = 0;

            for (var i = 0; i < selectedTests.length; i++) {
                var testPrice = parseFloat(selectedTests[i].getAttribute('data-price')) || 0;
                total += testPrice;
            }

            // Update the hidden input field with the total price
            document.getElementById('total_price_input').value = total.toFixed(2);

            totalPriceDisplay.textContent = '₹' + total.toFixed(2);

            // Check if the appointment date and time are valid
            var selectedDateTime = new Date(appointmentDateTimeInput.value);
            var currentDateTime = new Date();

            // Check if the selected date and time are within the next 5 minutes
            var oneMinutesLater = new Date(currentDateTime.getTime() + 0 * 60000); // One minutes in milliseconds

            if (selectedDateTime <= oneMinutesLater) {
                alert("Please select a valid date and time.");
                return false;
            }

            return true;
        }
    </script>
</head>

<body>

    <!-- Your form content here -->

    <form method="post" action="appoinment1.php" onsubmit="return calculateTotal()" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" name="name" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="phone">Phone:</label>
        <input type="tel" name="phone" required><br>

        <label for="test">Select Test(s):</label>
        <select id="tests" name="tests[]" class="js-example-basic-multiple" multiple="multiple" onchange="calculateTotal()" required>
            <option value="" disabled>Select Tests</option>
            <option value="CBC" data-price="250">CBC</option>
            <option value="ESR" data-price="150">ESR</option>
            <option value="FASTING SUGAR" data-price="50">FASTING SUGAR</option>
            <option value="POST LUNCH SUGAR" data-price="50">POST LUNCH SUGAR</option>
            <option value="RT-PCR" data-price="500">RT-PCR</option>
            <option value="RANDOM SUGAR" data-price="100">RANDOM SUGAR</option>
            <option value="BLOOD GROUP" data-price="70">BLOOD GROUP</option>
            <option value="URINE CULTURE" data-price="60">URINE CULTURE</option>
            <option value="DENGUE NS1" data-price="50">DENGUE NS1</option>
            <option value="HBA1C" data-price="150">HBA1C</option>
            <!-- Add more test options as needed -->
        </select><br>

        <label>Total Price:</label>
        <span id="totalPrice">₹0.00</span><br>
        <input type="hidden" name="total_price" id="total_price_input" value="₹0.00">

        <label for="appointment_date_time">Appointment Date and Time:</label>
        <input type="datetime-local" name="appointment_date_time" id="appointment_date_time" required min="<?php echo date('Y-m-d\TH:i'); ?>"><br>

        <label for="file_upload">Upload Image/PDF/docs: (Optional)</label>
        <input type="file" name="file" accept=".jpg, .jpeg, .png, .gif, .pdf, .doc, .docx"><br>

        <button type="submit">Book Appointment</button>
    </form>

    <script>
        // Activate Select2 on your multi-select dropdown
        $(document).ready(function () {
            $(".js-example-basic-multiple").select2({
                width: "100%"
            });
        });
    </script>

</body>

</html>
