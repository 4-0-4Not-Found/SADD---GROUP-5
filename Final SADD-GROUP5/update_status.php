<?php
include_once("include/connect.php");
session_start();

// Validate input
if (!isset($_POST['student_id']) || !isset($_POST['status']) || !isset($_POST['block_name'])) {
    die(json_encode(['error' => 'Missing required parameters']));
}

$studentId = (int)$_POST['student_id'];
$newStatus = $_POST['status'];
$blockName = $_POST['block_name'];
$currentDate = date('Y-m-d H:i:s'); // Current timestamp

// Update the status and date in the database
$sql = "UPDATE attendance 
        SET status = ?, date = ?
        WHERE id = ? 
        AND block = ?
        AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisi", $newStatus, $currentDate, $studentId, $blockName, $_SESSION['user_id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Error updating status: ' . $stmt->error]);
}
?>