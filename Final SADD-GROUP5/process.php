<?php
include_once("include/connect.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['studentId'];
    $newStatus = $_POST['newStatus'];

    $updateSql = "UPDATE students SET status = '$newStatus' WHERE id = $studentId";
    if (mysqli_query($conn, $updateSql)) {
        echo 'Status updated successfully';
    } else {
        echo 'Error updating status: ' . mysqli_error($conn);
    }
} else {
    http_response_code(405); 
    echo 'Invalid request';
}

mysqli_close($conn);
?>
