<?php
include_once("../include/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bu_no = $_POST['bu_no'];

    // Check if a file was uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'stud_img/'; // Specify the directory where you want to store the uploaded photos
        $uploadFile = $uploadDir . basename($_FILES['photo']['name']);
        
        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
            // Update the database with the new photo path
            $updateQuery = "UPDATE students SET img = '$uploadFile' WHERE bu_no = '$bu_no'";
            if ($conn->query($updateQuery)) {
                echo "Photo uploaded successfully and database updated.";

                // Redirect to homepage.php
                header("Location: profile.php");
                exit();
            } else {
                echo "Error updating database: " . $conn->error;
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "No file uploaded or an error occurred.";
    }
} else {
    echo "Invalid request method.";
}
?>
