<?php
session_start();
include_once("include/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["students"])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Invalid CSRF token'];
        header("Location: index.php");
        exit();
    }

    $block_id = (int)$_POST["block_id"];
    $user_id = (int)$_POST["user_id"];
    $students = $_POST["students"];

    // Prepare the SQL statement
    $placeholders = implode(',', array_fill(0, count($students), '?'));
    $sql = "DELETE FROM `students` WHERE `bu_no` IN ($placeholders) AND `block` = ? AND `user_id` = ?";
    
    // Add block_id and user_id to the parameters
    $params = array_merge($students, [$block_id, $user_id]);
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($students)) . 'ii', ...$params);
    
    if ($stmt->execute()) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Selected students deleted successfully'];
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error deleting students: ' . $conn->error];
    }
    
    // Redirect back to process_class.php with the same parameters
    header("Location: process_class.php?block_id=$block_id&user_id=$user_id");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>