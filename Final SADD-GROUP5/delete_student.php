<?php
session_start();
include_once("include/connect.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Only process deletion if we have all required parameters
if (isset($_POST['delete_id']) && isset($_POST['block_id']) && isset($_POST['user_id'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Security token mismatch'];
        header("Location: process_class.php?block_id=".$_POST['block_id']."&user_id=".$_POST['user_id']);
        exit();
    }

    // Sanitize inputs
    $delete_id = $conn->real_escape_string($_POST['delete_id']);
    $block_id = $conn->real_escape_string($_POST['block_id']); // block is varchar(10)
    $user_id = (int)$_POST['user_id'];

    // Verify the student belongs to this user's class before deleting
    $check_sql = "SELECT ID FROM students WHERE bu_no = '$delete_id' AND block = '$block_id' AND user_id = $user_id LIMIT 1";
    $check_result = $conn->query($check_sql);

    if ($check_result && $check_result->num_rows > 0) {
        // Student exists in this class - proceed with deletion
        $delete_sql = "DELETE FROM students WHERE bu_no = '$delete_id' AND block = '$block_id' AND user_id = $user_id LIMIT 1";
        
        if ($conn->query($delete_sql)) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Student deleted successfully'];
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error deleting student: ' . $conn->error];
        }
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Student not found in this class'];
    }

    // Redirect back to the class page with proper parameters
    header("Location: process_class.php?block_id=$block_id&user_id=$user_id");
    exit();
} else {
    // Missing parameters - redirect to login
    header("Location: index.php");
    exit();
}
?>