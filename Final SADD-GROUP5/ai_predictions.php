<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'ai_predictions_errors.log');

include_once("include/connect.php");
session_start();

// Validate session
if (!isset($_SESSION['user_id'])) {
    die(json_encode(['error' => 'Session expired. Please login again.']));
}

// Validate input
if (!isset($_POST['block_name']) || empty(trim($_POST['block_name']))) {
    die(json_encode(['error' => 'Block name is required']));
}

$blockName = trim($_POST['block_name']);
$userId = (int)$_SESSION['user_id'];
$daysToPredict = isset($_POST['days']) ? min(max((int)$_POST['days'], 1), 30) : 7;

try {
    // Verify database connection
    if (!$conn) {
        throw new Exception("Database connection failed");
    }

    // Verify block access (modified query)
    $blockCheckSql = "SELECT block_id FROM block WHERE block_name = ? AND user_id = ?";
    $stmt = $conn->prepare($blockCheckSql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("si", $blockName, $userId);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $blockResult = $stmt->get_result();
    if ($blockResult->num_rows === 0) {
        die(json_encode(['error' => 'Block not found or access denied']));
    }

    // Get total students in this block (new query)
    $totalStudentsSql = "SELECT COUNT(DISTINCT student_name) as total_students 
                        FROM attendance 
                        WHERE user_id = ? AND block = ?";
    $stmt = $conn->prepare($totalStudentsSql);
    $stmt->bind_param("is", $userId, $blockName);
    $stmt->execute();
    $totalResult = $stmt->get_result();
    $totalData = $totalResult->fetch_assoc();
    $totalStudents = $totalData['total_students'] ?? 0;

    if ($totalStudents == 0) {
        die(json_encode(['error' => 'No students found in this block']));
    }

    // Get historical data (60 days)
    $historySql = "SELECT 
                    DATE(date) as day, 
                    DAYOFWEEK(date) as day_of_week,
                    SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present_count,
                    SUM(CASE WHEN status = 'Late' THEN 1 ELSE 0 END) as late_count,
                    SUM(CASE WHEN status = 'Excused' THEN 1 ELSE 0 END) as excused_count
                  FROM attendance
                  WHERE user_id = ?
                  AND block = ?
                  AND date >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)
                  GROUP BY DATE(date)
                  ORDER BY day ASC";
    
    $stmt = $conn->prepare($historySql);
    $stmt->bind_param("is", $userId, $blockName);
    $stmt->execute();
    $result = $stmt->get_result();
    $historyData = $result->fetch_all(MYSQLI_ASSOC);

    if (empty($historyData)) {
        die(json_encode(['error' => 'Not enough historical data for predictions']));
    }

    // Calculate day-of-week averages
    $dayOfWeekAverages = array_fill(1, 7, []);
    foreach ($historyData as $record) {
        $dow = $record['day_of_week'];
        $attendanceRate = ($record['present_count'] + $record['late_count'] + $record['excused_count']) / $totalStudents;
        $dayOfWeekAverages[$dow][] = $attendanceRate;
    }

    // Generate predictions
    $predictions = [];
    for ($i = 1; $i <= $daysToPredict; $i++) {
        $predictionDate = date('Y-m-d', strtotime("+$i days"));
        $dow = date('N', strtotime($predictionDate));
        
        $predictedRate = 0.7; // Default
        $confidence = 0.5;
        
        if (!empty($dayOfWeekAverages[$dow])) {
            $predictedRate = array_sum($dayOfWeekAverages[$dow]) / count($dayOfWeekAverages[$dow]);
            $confidence = min(0.9, 0.5 + (count($dayOfWeekAverages[$dow]) * 0.1));
        }
        
        $predictions[] = [
            'date' => $predictionDate,
            'day_of_week' => $dow,
            'expected_attendance' => round($predictedRate * $totalStudents),
            'attendance_rate' => $predictedRate,
            'confidence' => $confidence,
            'total_students' => $totalStudents,
            'risk_level' => $predictedRate < 0.5 ? 'high' : ($predictedRate < 0.7 ? 'medium' : 'low'),
            'suggestion' => $predictedRate < 0.5 ? 'Consider interventions' : 
                          ($predictedRate < 0.7 ? 'Monitor closely' : 'Normal attendance expected')
        ];
    }

    echo json_encode([
        'success' => true,
        'predictions' => $predictions,
        'metrics' => [
            'total_students' => $totalStudents,
            'prediction_model' => 'day_of_week_average'
        ]
    ]);

} catch (Exception $e) {
    error_log("Prediction error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error',
        'message' => $e->getMessage()
    ]);
}
?>