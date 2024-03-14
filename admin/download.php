<?php
// Include database connection
include 'database_connection.php';

// Check if appointment ID is provided
if (isset($_GET['id'])) {
    $appointmentId = $_GET['id'];

    // Retrieve file details from the database based on the appointment ID
    $query = "SELECT file_name, file_content FROM booking_appoinment WHERE id = $appointmentId";
    $result = mysqli_query($con, $query);

    if ($result) {
        $appointment = mysqli_fetch_assoc($result);
        $fileName = $appointment['file_name'];
        $fileContent = $appointment['file_content'];

        // Check if both file name and content are available
        if ($fileName && $fileContent) {
            // Determine the file extension
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            // Check if the file type is supported
            if ($_GET['action'] === 'preview') {
                // Check if the file type is supported for preview
                $supportedImageExtensions = array('jpg', 'jpeg', 'png', 'gif');
                $supportedPdfExtensions = array('pdf');
                if (in_array(strtolower($fileExtension), $supportedImageExtensions)) {
                    // Display the image directly
                    echo "<img src='data:image/$fileExtension;base64," . base64_encode($fileContent) . "' alt='Preview'>";
                } elseif (in_array(strtolower($fileExtension), $supportedPdfExtensions)) {
                    // Embed the PDF
                    echo "<embed src='data:application/pdf;base64," . base64_encode($fileContent) . "' type='application/pdf' width='100%' height='600px' />";
                } else {
                    // If the file type is not supported for preview, display a message
                    echo 'File type not supported for preview.';
                }
            } elseif ($_GET['action'] === 'download') {
                // Force download the file
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $fileName . '"');
                echo $fileContent;
                exit();
            } else {
                // Invalid action
                echo 'Invalid action.';
            }
        } else {
            // If file content is not available, display a message
            echo 'No file available for download.';
        }
    } else {
        echo 'Error retrieving file details: ' . mysqli_error($con);
    }
} else {
    echo 'Invalid request.';
}
?>
