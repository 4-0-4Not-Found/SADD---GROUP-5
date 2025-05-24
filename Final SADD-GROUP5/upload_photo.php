<?php
include_once("include/connect.php");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate BU number
    if (!isset($_POST['bu_no']) || empty($_POST['bu_no'])) {
        die("Error: BU number is required.");
    }
    
    $bu_no = $conn->real_escape_string($_POST['bu_no']);

    // Check if file was uploaded without errors
    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        die("Error: " . ($_FILES['photo']['error'] ?? 'No file uploaded'));
    }

    // File validation
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($fileInfo, $_FILES['photo']['tmp_name']);
    finfo_close($fileInfo);

    if (!in_array($mimeType, $allowedTypes)) {
        die("Error: Only JPG, PNG, and GIF files are allowed.");
    }

    if ($_FILES['photo']['size'] > $maxSize) {
        die("Error: File size exceeds 2MB limit.");
    }

    // Create upload directory if it doesn't exist
    $uploadDir = 'img/students/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generate unique filename
    $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $filename = 'student_' . $bu_no . '_' . time() . '.' . $extension;
    $uploadPath = $uploadDir . $filename;

    // Move uploaded file
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
        // Update database
        $updateQuery = "UPDATE students SET img = ? WHERE bu_no = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ss", $uploadPath, $bu_no);
        
        if ($stmt->execute()) {
            // Success - redirect back to student view
            header("Location: view_students.php?bu_no=" . urlencode($bu_no));
            exit();
        } else {
            // Delete the uploaded file if DB update fails
            unlink($uploadPath);
            die("Error updating database: " . $conn->error);
        }
    } else {
        die("Error moving uploaded file.");
    }
} else {
    header("Location: view_students.php");
    exit();
}
?>