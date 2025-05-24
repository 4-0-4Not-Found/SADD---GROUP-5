<?php
session_start();
include_once("include/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $bu_no = $_POST['bu_no'];
    $user_id = $_POST['user_id'];
    $block = $_POST['block_id'];
    $reg_id = $_POST['reg_id'];
    $birthday = $_POST['birthday'];
    $contact = $_POST['contact_no'];
    $email1 = $_POST['email_no1'];
    $email2 = $_POST['email_no2'];
    $gender = $_POST['gender'];
    $year_level = $_POST['year_level'];

    $status = 'active';

    $sql = "INSERT INTO students (bu_no, name, reg_id, status, birthdate, gender, year_level, contact_number, email_add1, email_add2, block, user_id) VALUES ('$bu_no', '$name', '$reg_id', '$status', '$birthday', '$gender', '$year_level', '$contact', '$email1', '$email2', '$block', '$user_id')";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect to process_class.php with block and user parameters
        header("Location: process_class.php?block_id=$block&user_id=$user_id");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: process_class.php?block_id=$block&user_id=$user_id");
        exit();
    }
}

$conn->close();
?>