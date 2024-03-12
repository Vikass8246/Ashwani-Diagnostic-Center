<?php
$con = mysqli_connect('localhost', 'root', '', 'registration');
// Assuming you have the file_id as a parameter
$fileId = $_GET['id'] ?? '';

if (!empty($Id)) {
    // Fetch file details from the database
    $fileQuery = "SELECT file_name, file_content, file_extension FROM booking_appoinment WHERE id = '$Id'";
    $fileResult = mysqli_query($con, $fileQuery);

    if ($fileResult) {
        $fileRow = mysqli_fetch_assoc($fileResult);

        if ($fileRow) {
            $fileName = $fileRow['file_name'];
            $fileContent = $fileRow['file_content'];
            $fileExtension = $fileRow['file_extension'];

            // Set appropriate headers
            header("Content-type: application/$fileExtension");
            header("Content-Disposition: attachment; filename=$fileName");

            // Output the file content
            echo $fileContent;

            exit;
        } else {
            echo 'File not found';
        }
    } else {
        echo 'Error fetching file details';
    }
} else {
    echo 'File ID not provided';
}

// Assuming $appointmentId is the ID of the appointment
$Id = $_GET['id'] ?? '';

// Display other appointment details...

// Display a download link for the uploaded file
if (!empty($Id)) {
    echo '<a href="download.php?file_id=' . $Id . '">Download File</a>';
}
?>