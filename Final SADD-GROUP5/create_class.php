<?php
session_start();
include_once("include/connect.php");

// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token validation failed");
}

// Validate and sanitize inputs
$block_name = $conn->real_escape_string($_POST['block_name']);
$school_year = $conn->real_escape_string($_POST['school_year']);
$user_id = (int)$_POST['user_id'];

// Validate school year format
if (!preg_match('/^\d{4} - \d{4}$/', $school_year)) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Invalid school year format. Please use "YYYY - YYYY" format.'];
    header("Location: class.php");
    exit();
}

// Check if class already exists
$check_sql = "SELECT block_id FROM block WHERE block_name = ? AND user_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("si", $block_name, $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'A class with this name already exists.'];
    header("Location: class.php");
    exit();
}

// Create the class
$sql = "INSERT INTO `block` (`block_name`, `user_id`, `school_yr`) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sis", $block_name, $user_id, $school_year);

if ($stmt->execute()) {
    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Class created successfully!'];
} else {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error creating class: ' . $conn->error];
}

header("Location: class.php");
exit();
?>