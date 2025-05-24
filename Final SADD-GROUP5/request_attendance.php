<?php
include_once("include/connect.php");
session_start();

if (isset($_GET['block_id']) && isset($_GET['user_id'])) {
    $blockId = $_GET['block_id'];
    $userId = $_GET['user_id'];
    
    // Verify the block belongs to the user
    $verifySql = "SELECT block_name FROM block WHERE block_id = ? AND user_id = ?";
    $stmt = $conn->prepare($verifySql);
    $stmt->bind_param("ii", $blockId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $blockData = $result->fetch_assoc();
        $blockName = $blockData['block_name'];
        
        // Delete any existing pending attendance for this block
        $deleteSql = "DELETE FROM attendance WHERE block = ? AND user_id = ? AND status = 'Pending'";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("si", $blockName, $userId);
        $deleteStmt->execute();
        
        // Get active students for this block
        $studentSql = "SELECT id, name, bu_no FROM students WHERE status = 'active' AND block = ? AND user_id = ?";
        $studentStmt = $conn->prepare($studentSql);
        $studentStmt->bind_param("ii", $blockId, $userId);
        $studentStmt->execute();
        $studentResult = $studentStmt->get_result();
        
        if ($studentResult->num_rows > 0) {
            // Insert new attendance records
            $insertSql = "INSERT INTO attendance (id, student_name, status, date, bu_no, block, user_id) VALUES (?, ?, 'Pending', NOW(), ?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            
            while ($row = $studentResult->fetch_assoc()) {
                $insertStmt->bind_param("isssi", $row['id'], $row['name'], $row['bu_no'], $blockName, $userId);
                $insertStmt->execute();
            }
            
            $_SESSION['current_block'] = $blockName;
            header("Location: homepage.php");
            exit();
        } else {
            // No students found
            $_SESSION['error'] = "No active students found for this block.";
            header("Location: homepage.php");
            exit();
        }
    } else {
        // Block doesn't belong to user or doesn't exist
        $_SESSION['error'] = "Invalid block selection.";
        header("Location: homepage.php");
        exit();
    }
} else {
    // Missing parameters
    $_SESSION['error'] = "Missing required parameters.";
    header("Location: homepage.php");
    exit();
}