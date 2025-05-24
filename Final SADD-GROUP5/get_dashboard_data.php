<?php
include_once("include/connect.php");
session_start();

header('Content-Type: application/json');

// Validate input
if (!isset($_POST['block_name']) || !isset($_POST['user_id'])) {
    die(json_encode(['error' => 'Missing parameters']));
}

$currentBlock = $_POST['block_name'];
$userId = (int)$_POST['user_id'];
$today = date('Y-m-d');

try {
    // Fetch all attendance data for the block (for general counts)
    $sql = "SELECT id, student_name as name, status, date, block 
            FROM attendance 
            WHERE user_id = ? 
            AND block = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $userId, $currentBlock);
    $stmt->execute();
    $result = $stmt->get_result();
    $attendanceRecords = $result->fetch_all(MYSQLI_ASSOC);

    // Calculate general counts (all records)
    $counts = ['Present' => 0, 'Absent' => 0, 'Late' => 0, 'Excused' => 0];
    foreach ($attendanceRecords as $record) {
        if (isset($counts[$record['status']])) {
            $counts[$record['status']]++;
        }
    }

    // Get today's attendance data specifically for rate calculation
    $todaySql = "SELECT 
                COUNT(DISTINCT student_name) as total_students,
                SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN status = 'Late' THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN status = 'Excused' THEN 1 ELSE 0 END) as excused,
                SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent
                FROM attendance 
                WHERE user_id = ?
                AND block = ?
                AND DATE(date) = ?";
    $stmt = $conn->prepare($todaySql);
    $stmt->bind_param("iss", $userId, $currentBlock, $today);
    $stmt->execute();
    $todayData = $stmt->get_result()->fetch_assoc();

    // Calculate today's attendance rate
    $todayPresent = (int)($todayData['present'] ?? 0);
    $todayLate = (int)($todayData['late'] ?? 0);
    $todayExcused = (int)($todayData['excused'] ?? 0);
    $todayAbsent = (int)($todayData['absent'] ?? 0);
    $totalTodayRecords = $todayPresent + $todayLate + $todayExcused + $todayAbsent;
    $uniqueStudentsToday = (int)($todayData['total_students'] ?? 0);

    // Use the higher of total records or unique students to prevent >100% rate
    $denominator = max($uniqueStudentsToday, $totalTodayRecords);
    $attendanceRate = $denominator > 0 ? 
        round(($todayPresent + $todayLate + $todayExcused) / $denominator * 100) : 0;

    // Ensure rate doesn't exceed 100%
    $attendanceRate = min($attendanceRate, 100);

    // Get recent records
    $recentSql = "SELECT student_name, status, date 
                 FROM attendance 
                 WHERE user_id = ?
                 AND block = ?
                 ORDER BY date DESC 
                 LIMIT 5";
    $stmt = $conn->prepare($recentSql);
    $stmt->bind_param("is", $userId, $currentBlock);
    $stmt->execute();
    $recentRecords = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Return data
    echo json_encode([
        'success' => true,
        'presentCount' => $counts['Present'],
        'absentCount' => $counts['Absent'],
        'lateCount' => $counts['Late'],
        'excusedCount' => $counts['Excused'],
        'attendanceRate' => $attendanceRate,
        'todayAttendance' => [
            'present' => $todayPresent,
            'late' => $todayLate,
            'excused' => $todayExcused,
            'absent' => $todayAbsent,
            'total' => $denominator,
            'uniqueStudents' => $uniqueStudentsToday
        ],
        'recentRecords' => $recentRecords,
        'totalStudents' => count($attendanceRecords)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}
?>