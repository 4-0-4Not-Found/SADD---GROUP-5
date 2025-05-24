<?php
include_once("include/connect.php");
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['date'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$date = $conn->real_escape_string($_GET['date']);

$sql = "SELECT * FROM deleted_records 
        WHERE user_id = ? AND DATE(date) = ?
        ORDER BY student_name";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $date);
$stmt->execute();
$records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Attendance Sheet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/user.css">
    <?php include('include/link.php')?>
</head>
<body>
    <?php include('include/nav.php') ?>
    
    <div class="container mt-5">
        <h3>Attendance Sheet for <?= date('F j, Y', strtotime($date)) ?></h3>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>BU Number</th>
                    <th>Status</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?= htmlspecialchars($record['student_name']) ?></td>
                        <td><?= htmlspecialchars($record['bu_no']) ?></td>
                        <td><?= htmlspecialchars($record['status']) ?></td>
                        <td><?= date('H:i:s', strtotime($record['date'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <a href="recycle_bin.php" class="btn btn-secondary">Back to Recycle Bin</a>
    </div>
</body>
</html>