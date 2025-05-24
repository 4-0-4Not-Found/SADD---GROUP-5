<?php
include_once("include/connect.php");
session_start();

// Display any errors
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            '.$_SESSION['error'].'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['error']);
}

// Function to get status badge class
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'Present':
            return 'bg-success';
        case 'Absent':
            return 'bg-danger';
        case 'Late':
            return 'bg-warning';
        case 'Excused':
            return 'bg-info';
        default:
            return 'bg-secondary';
    }
}

// Get the current year
$currentYear = date('Y');
$schoolYearStart = $currentYear - 1;
$schoolYearEnd = $currentYear;
$schoolYear = $schoolYearStart . ' - ' . $schoolYearEnd;

// Get current block from session or request
if (isset($_GET['block_name'])) {
    $_SESSION['current_block'] = $_GET['block_name'];
} elseif (!isset($_SESSION['current_block'])) {
    $_SESSION['current_block'] = '';
}

$currentBlock = $_SESSION['current_block'];
$today = date('Y-m-d');

// Fetch attendance data for the current block
$sql = "SELECT id, student_name as name, status, date, block 
        FROM attendance 
        WHERE user_id = '{$_SESSION['user_id']}' 
        AND block = '{$currentBlock}'";
$result = mysqli_query($conn, $sql);
$attendanceRecords = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Dashboard Statistics - filtered by current block
$presentCount = 0;
$absentCount = 0;
$lateCount = 0;
$excusedCount = 0;
$totalStudents = count($attendanceRecords);

// Get today's attendance for accurate rate calculation
$todaySql = "SELECT COUNT(*) as total, 
                    SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present,
                    SUM(CASE WHEN status = 'Late' THEN 1 ELSE 0 END) as late,
                    SUM(CASE WHEN status = 'Excused' THEN 1 ELSE 0 END) as excused
             FROM attendance 
             WHERE user_id = '{$_SESSION['user_id']}'
             AND block = '{$currentBlock}'
             AND DATE(date) = '{$today}'";
$todayResult = mysqli_query($conn, $todaySql);
$todayData = mysqli_fetch_assoc($todayResult);

$todayPresent = $todayData['present'] ?? 0;
$todayLate = $todayData['late'] ?? 0;
$todayExcused = $todayData['excused'] ?? 0;
$todayTotal = $todayData['total'] ?? 0;

foreach ($attendanceRecords as $record) {
    switch ($record['status']) {
        case 'Present':
            $presentCount++;
            break;
        case 'Absent':
            $absentCount++;
            break;
        case 'Late':
            $lateCount++;
            break;
        case 'Excused':
            $excusedCount++;
            break;
    }
}

// Calculate attendance rate based on today's records
$attendanceRate = $todayTotal > 0 ? round(($todayPresent + $todayLate + $todayExcused) / $todayTotal * 100) : 0;

// Get recent attendance records for the current block
$recentSql = "SELECT student_name, status, date 
              FROM attendance 
              WHERE user_id = '{$_SESSION['user_id']}'
              AND block = '{$currentBlock}'
              ORDER BY date DESC 
              LIMIT 5";
$recentResult = mysqli_query($conn, $recentSql);
$recentRecords = mysqli_fetch_all($recentResult, MYSQLI_ASSOC);

// Get attendance data for charts - filtered by current block
$chartSql = "SELECT DATE(date) as day, 
                    SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present,
                    SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent,
                    SUM(CASE WHEN status = 'Late' THEN 1 ELSE 0 END) as late,
                    SUM(CASE WHEN status = 'Excused' THEN 1 ELSE 0 END) as excused,
                    COUNT(*) as total
             FROM attendance
             WHERE user_id = '{$_SESSION['user_id']}'
             AND block = '{$currentBlock}'
             GROUP BY DATE(date)
             ORDER BY day ASC
             LIMIT 7";
$chartResult = mysqli_query($conn, $chartSql);
$chartData = mysqli_fetch_all($chartResult, MYSQLI_ASSOC);

// Prepare chart data with proper date formatting
$chartLabels = [];
$presentData = [];
$absentData = [];
$lateData = [];
$excusedData = [];

foreach ($chartData as $day) {
    $chartLabels[] = date('M j', strtotime($day['day']));
    $presentData[] = $day['present'];
    $absentData[] = $day['absent'];
    $lateData[] = $day['late'];
    $excusedData[] = $day['excused'];
}

// Get all blocks for this user
$blocksSql = "SELECT block_id, block_name FROM block WHERE user_id = '{$_SESSION['user_id']}'";
$blocksResult = mysqli_query($conn, $blocksSql);
$userBlocks = mysqli_fetch_all($blocksResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/user.css">
    <?php include('include/link.php')?>
    <title>Class Attendance System</title>
    
    <!-- Chart.js for visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
         .prediction-day-card {
        border-left: 4px solid;
        transition: all 0.3s ease;
    }
    
    .prediction-high-risk {
        border-left-color: #dc3545;
        background-color: rgba(220, 53, 69, 0.05);
    }
    
    .prediction-medium-risk {
        border-left-color: #ffc107;
        background-color: rgba(255, 193, 7, 0.05);
    }
    
    .prediction-low-risk {
        border-left-color: #28a745;
        background-color: rgba(40, 167, 69, 0.05);
    }
    
    .prediction-day-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .risk-badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }

        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .attendance-rate {
            font-size: 2rem;
            font-weight: bold;
        }
        
        .recent-activity-item {
            border-left: 3px solid;
            padding-left: 10px;
            margin-bottom: 10px;
        }
        
        .present-border { border-color: #28a745; }
        .absent-border { border-color: #dc3545; }
        .late-border { border-color: #ffc107; }
        .excused-border { border-color: #17a2b8; }
        
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }
        
        .block-selector {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .dashboard-card {
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <?php include('include/nav.php') ?>

<!-- Block Selection Modal -->
<div class="modal fade" id="blockModal" tabindex="-1" aria-labelledby="blockModalLabel" aria-hidden="true">  
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Block</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (!empty($userBlocks)): ?>
                    <div class="list-group">
                        <?php foreach ($userBlocks as $block): ?>
                            <a href="homepage.php?block_name=<?php echo urlencode($block['block_name']); ?>&block_id=<?php echo $block['block_id']; ?>" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($block['block_name']); ?>
                                <span class="badge bg-primary rounded-pill">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        No classes created yet.
                    </div>
                    <div class="text-center mt-3">
                        <a href="class.php" class="btn btn-info">Create a Class</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

    <div class="container mt-4 mb-5">
 <!-- Enhanced Header Section -->
    <div class="header-container bg-white p-4 rounded-3 shadow-sm mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="text-center text-md-start mb-3 mb-md-0">
                <h2 class="fw-bold mb-1 text-primary">Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
                <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2">
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-calendar-alt me-1"></i>
                        <span id="dateTime"></span>
                    </span>
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-graduation-cap me-1"></i>
                        School Year: <?php echo $schoolYear; ?>
                    </span>
                </div>
            </div>
            
            <?php if (!empty($currentBlock)): ?>
                <div class="current-block-badge">
                    <span class="badge bg-info bg-opacity-10 text-info fs-6 py-2 px-3">
                        <i class="fas fa-users-class me-1"></i>
                        Current: <?php echo htmlspecialchars($currentBlock); ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </div>

        <!-- Block Selector -->
        <div class="block-selector text-center">
            <?php if (!empty($currentBlock)): ?>
                <h4>Current Block: <?php echo htmlspecialchars($currentBlock); ?></h4>
            <?php endif; ?>
            <button type="button" class="btn btn-info rounded-pill mt-2" data-bs-toggle="modal" data-bs-target="#blockModal">
                <?php echo empty($currentBlock) ? 'Select a Block' : 'Change Block'; ?>
            </button>
            
            <!-- Add New Attendance Button -->
            <?php if (!empty($currentBlock)): ?>
                <form method="get" action="request_attendance.php" class="mt-3">
                    <input type="hidden" name="block_id" value="<?php echo array_column($userBlocks, 'block_id')[array_search($currentBlock, array_column($userBlocks, 'block_name'))]; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <input type="hidden" name="block_name" value="<?php echo htmlspecialchars($currentBlock); ?>">
                    <button type="submit" class="btn btn-warning rounded-pill">
                        <i class="fas fa-plus-circle me-1"></i> Create New Attendance
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <?php if (!empty($currentBlock)): ?>
            <!-- Dashboard Section -->
            <div class="row mb-5">
                <!-- Attendance Rate Card -->
                <div class="col-md-3">
                    <div class="card dashboard-card bg-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">Attendance Rate</h5>
                            <div class="attendance-rate text-primary mb-2"><?php echo $attendanceRate; ?>%</div>
                            <p class="card-text text-muted">Today's attendance</p>
                            <div class="progress">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $attendanceRate; ?>%" 
                                     aria-valuenow="<?php echo $attendanceRate; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Students Card -->
                <div class="col-md-3">
                    <div class="card dashboard-card bg-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Students</h5>
                            <div class="attendance-rate text-info mb-2"><?php echo $totalStudents; ?></div>
                            <p class="card-text text-muted">in <?php echo htmlspecialchars($currentBlock); ?></p>
                            <div class="d-flex justify-content-center">
                                <span class="badge bg-info me-2">Present: <?php echo $presentCount; ?></span>
                                <span class="badge bg-danger">Absent: <?php echo $absentCount; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Status Distribution Card -->
                <div class="col-md-3">
                    <div class="card dashboard-card bg-white">
                        <div class="card-body">
                            <h5 class="card-title text-center">Status Distribution</h5>
                            <div class="chart-container">
                                <canvas id="statusPieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity Card -->
                <div class="col-md-3">
                    <div class="card dashboard-card bg-white">
                        <div class="card-body">
                            <h5 class="card-title text-center">Recent Activity</h5>
                            <div class="recent-activity">
                                <?php if (!empty($recentRecords)): ?>
                                    <?php foreach ($recentRecords as $record): ?>
                                        <div class="recent-activity-item <?php echo strtolower($record['status']) . '-border'; ?>">
                                            <strong><?php echo htmlspecialchars($record['student_name']); ?></strong>
                                            <span class="badge <?php echo getStatusBadgeClass($record['status']); ?>">
                                                <?php echo $record['status']; ?>
                                            </span>
                                            <div class="text-muted small">
                                                <?php echo date('h:i A', strtotime($record['date'])); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted text-center">No recent activity</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- count section -->
            <div class="mb-3 text-center">
                <button type="button" class="btn btn-outline-success rounded-pill px-4 me-2" data-bs-toggle="modal" data-bs-target="#presentModal">
                    Present: <span id="presentCount"><?php echo $presentCount; ?></span>
                </button>

                <button type="button" class="btn btn-outline-danger rounded-pill px-4 me-2" data-bs-toggle="modal" data-bs-target="#absentModal">
                    Absent: <span id="absentCount"><?php echo $absentCount; ?></span>
                </button>

                <button type="button" class="btn btn-outline-warning rounded-pill px-4 me-2" data-bs-toggle="modal" data-bs-target="#lateModal">
                    Late: <span id="lateCount"><?php echo $lateCount; ?></span>
                </button>

                <button type="button" class="btn btn-outline-info rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#excusedModal">
                    Excused: <span id="excusedCount"><?php echo $excusedCount; ?></span>
                </button>
            </div>

 <!-- Status Modals -->
<!-- Present Modal -->
<div class="modal fade" id="presentModal" tabindex="-1" aria-labelledby="presentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Present Students</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background: linear-gradient(135deg, #e6f7ff, #cceeff);">
                <?php if ($presentCount > 0): ?>
                    <?php foreach ($attendanceRecords as $record): ?>
                        <?php if ($record['status'] == 'Present'): ?>
                            <div class="student-item mb-2 p-2 bg-light rounded">
                                <i class="fas fa-user-check text-success me-2"></i>
                                <?php echo htmlspecialchars($record['name']); ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-user-slash fa-2x mb-2"></i>
                        <p>No students marked as Present</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Absent Modal -->
<div class="modal fade" id="absentModal" tabindex="-1" aria-labelledby="absentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Absent Students</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background: linear-gradient(135deg, #ffebee, #ffcdd2);">
                <?php if ($absentCount > 0): ?>
                    <?php foreach ($attendanceRecords as $record): ?>
                        <?php if ($record['status'] == 'Absent'): ?>
                            <div class="student-item mb-2 p-2 bg-light rounded">
                                <i class="fas fa-user-times text-danger me-2"></i>
                                <?php echo htmlspecialchars($record['name']); ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-user-check fa-2x mb-2"></i>
                        <p>No students marked as Absent</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Late Modal -->
<div class="modal fade" id="lateModal" tabindex="-1" aria-labelledby="lateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Late Students</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background: linear-gradient(135deg, #fff8e1, #ffecb3);">
                <?php if ($lateCount > 0): ?>
                    <?php foreach ($attendanceRecords as $record): ?>
                        <?php if ($record['status'] == 'Late'): ?>
                            <div class="student-item mb-2 p-2 bg-light rounded">
                                <i class="fas fa-clock text-warning me-2"></i>
                                <?php echo htmlspecialchars($record['name']); ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-clock fa-2x mb-2"></i>
                        <p>No students marked as Late</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Excused Modal -->
<div class="modal fade" id="excusedModal" tabindex="-1" aria-labelledby="excusedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Excused Students</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background: linear-gradient(135deg, #e0f7fa, #b2ebf2);">
                <?php if ($excusedCount > 0): ?>
                    <?php foreach ($attendanceRecords as $record): ?>
                        <?php if ($record['status'] == 'Excused'): ?>
                            <div class="student-item mb-2 p-2 bg-light rounded">
                                <i class="fas fa-calendar-check text-info me-2"></i>
                                <?php echo htmlspecialchars($record['name']); ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                        <p>No students marked as Excused</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
            
            <h2 class="mt-5 h-font mb-3">Class Attendance</h2>
            <?php if (empty($attendanceRecords)): ?>
                <div class="alert alert-info text-center">
                    No attendance records found for this block.
                </div>
            <?php else: ?>
                <?php
                usort($attendanceRecords, function($a, $b) {
                    return strcmp($a['name'], $b['name']);
                });
                ?>
                <form action="submit_attendance.php" method="post">
                    <input type="hidden" name="block_name" value="<?php echo htmlspecialchars($currentBlock); ?>">
                    <table class="table table-striped table-hover shadow-sm rounded bg-white caption-top">
                        <caption class="text-center"><?php echo htmlspecialchars($currentBlock); ?></caption>
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Names</th>
                                <th scope="col">Action</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendanceRecords as $index => $record): ?>
                                <tr>
                                    <th scope="row"><?php echo $index + 1; ?></th>
                                    <td><?php echo $record['name']; ?></td>
                                    <td>
                                        <div class="dropup-center dropup">
                                            <select name="status[<?php echo $record['id']; ?>]" class="form-select" onchange="updateStatus(this, '<?php echo $record['name']; ?>')">
                                                <option value="Present" <?php echo ($record['status'] == 'Present') ? 'selected' : ''; ?>>Select</option>
                                                <option value="Present" <?php echo ($record['status'] == 'Present') ? 'selected' : ''; ?>>Present</option>
                                                <option value="Absent" <?php echo ($record['status'] == 'Absent') ? 'selected' : ''; ?>>Absent</option>
                                                <option value="Late" <?php echo ($record['status'] == 'Late') ? 'selected' : ''; ?>>Late</option>
                                                <option value="Excused" <?php echo ($record['status'] == 'Excused') ? 'selected' : ''; ?>>Excused</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <span id="status_<?php echo $record['id']; ?>" class="badge rounded-pill <?php echo getStatusBadgeClass($record['status']); ?>">
                                            <?php echo $record['status']; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-info rounded-pill px-5 mt-3">Submit Attendance</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                Please select a block to view attendance data.
            </div>
        <?php endif; ?>

        <br><br>

                   <!-- AI Predictions Section -->
<div class="card mt-4 mb-4 border-0 shadow-sm">
    <div class="card-header bg-primary text-white rounded-top">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-robot me-2"></i>AI-Powered Attendance Insights
            </h5>
            <span class="badge bg-white text-primary">Beta</span>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-6">
                <div class="prediction-controls p-3 bg-light rounded">
                    <div class="mb-3">
                        <label for="predictionDays" class="form-label fw-bold text-primary">
                            <i class="fas fa-calendar-alt me-2"></i>Prediction Period
                        </label>
                        <select id="predictionDays" class="form-select border-primary">
                            <option value="3">Next 3 days</option>
                            <option value="7" selected>Next week</option>
                            <option value="14">Next 2 weeks</option>
                        </select>
                    </div>
                    <button id="getPredictionsBtn" class="btn btn-success w-100 py-2">
                        <i class="fas fa-chart-line me-2"></i> Generate Predictive Analysis
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="prediction-info p-3 h-100 bg-light rounded">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 text-primary fs-4 me-3">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <h6 class="text-primary">How predictions work</h6>
                            <p class="small text-muted mb-0">
                                Our AI analyzes historical attendance patterns, student trends, 
                                and class behavior to forecast future attendance with 85% accuracy.
                            </p>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex small text-muted">
                        <div class="flex-grow-1">
                            <i class="fas fa-database me-1"></i> Uses 6+ months of data
                        </div>
                        <div class="flex-grow-1">
                            <i class="fas fa-bolt me-1"></i> Real-time updates
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="predictionsContainer" class="mt-4" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 text-primary">
                    <i class="fas fa-project-diagram me-2"></i>Predicted Attendance Analysis
                </h6>
                <div class="badge bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-clock me-1"></i> Updated just now
                </div>
            </div>
            
            <div class="chart-container mb-4">
                <canvas id="predictionsChart"></canvas>
            </div>
            
            <div class="row g-3" id="predictionsDetails"></div>
            
            <div class="alert alert-info mt-3 small">
                <i class="fas fa-lightbulb me-2"></i>
                <strong>Pro Tip:</strong> Use these predictions to plan lessons and allocate resources for expected class sizes.
            </div>
        </div>
    </div>
</div>
        </div>
    

    <?php include('include/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/updateDateTime.js"></script>
    <script>
    // Global variables
    let pieChart;
    let totalStudents = <?php echo count($attendanceRecords); ?>;
    let recentRecords = <?php echo json_encode($recentRecords); ?>;

    document.addEventListener('DOMContentLoaded', function() {
        updateDateTime();
        
        <?php if (!empty($currentBlock)): ?>
            initializeCharts();
            updateTotalStudents();
            updateRecentActivity();
        <?php endif; ?>
          updateModalContents();
    }); 

    function initializeCharts() {
        // Destroy existing charts if they exist
        if (typeof pieChart !== 'undefined') {
            pieChart.destroy();
        }
        if (typeof trendChart !== 'undefined') {
            trendChart.destroy();
        }
        
        // Status Distribution Pie Chart
        const pieCtx = document.getElementById('statusPieChart').getContext('2d');
        pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Present', 'Absent', 'Late', 'Excused'],
                datasets: [{
                    data: [
                        <?php echo $presentCount; ?>,
                        <?php echo $absentCount; ?>,
                        <?php echo $lateCount; ?>,
                        <?php echo $excusedCount; ?>
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#dc3545',
                        '#ffc107',
                        '#17a2b8'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
    }

    // Object to track student statuses
    const studentStatuses = {};
    <?php foreach ($attendanceRecords as $record): ?>
        studentStatuses['<?php echo $record['id']; ?>'] = {
            name: '<?php echo $record['name']; ?>',
            status: '<?php echo $record['status']; ?>',
            date: '<?php echo $record['date']; ?>'
        };
    <?php endforeach; ?>

    function updateStatus(selectElement, studentName) {
        const studentId = selectElement.name.split('[')[1].split(']')[0];
        const newStatus = selectElement.value;
        const oldStatus = studentStatuses[studentId].status;
        const now = new Date().toISOString().slice(0, 19).replace('T', ' ');

        // Update local data immediately
        studentStatuses[studentId].status = newStatus;
        studentStatuses[studentId].date = now;
        
        // Update recent records
        updateRecentRecords(studentId, studentName, newStatus, now);

        // Update UI immediately
        updateLocalUI(studentId, studentName, newStatus, oldStatus);

        // Send update to server
        $.ajax({
            url: 'update_status.php',
            type: 'POST',
            data: {
                student_id: studentId,
                status: newStatus,
                block_name: '<?php echo $currentBlock; ?>',
                date: now
            },
            success: function(response) {
                // Refresh all dashboard data including trend
                refreshDashboardData();
            },
            error: function() {
                // Revert if there was an error
                selectElement.value = oldStatus;
                studentStatuses[studentId].status = oldStatus;
                updateLocalUI(studentId, studentName, oldStatus, newStatus);
                alert('Error updating status. Please try again.');
            }
        });

            updateModalContents();
    }

function refreshDashboardData() {
    $.ajax({
        url: 'get_dashboard_data.php',
        type: 'POST',
        dataType: 'json',
        data: {
            block_name: '<?php echo $currentBlock; ?>',
            user_id: '<?php echo $_SESSION['user_id']; ?>'
        },
        success: function(response) {
            if (response && response.success) {
                // Update counts
                $('#presentCount').text(response.presentCount);
                $('#absentCount').text(response.absentCount);
                $('#lateCount').text(response.lateCount);
                $('#excusedCount').text(response.excusedCount);
                
                // Calculate today's attendance rate from the specific today's data
                const todayPresent = response.todayAttendance.present;
                const todayLate = response.todayAttendance.late;
                const todayExcused = response.todayAttendance.excused;
                const todayTotal = response.todayAttendance.total;
                
                const todayRate = todayTotal > 0 ? 
                    Math.round((todayPresent + todayLate + todayExcused) / todayTotal * 100) : 0;
                
                // Update attendance rate display
                $('.attendance-rate').first().text(todayRate + '%');
                $('.progress-bar').css('width', todayRate + '%');
                
                // Update other elements
                recentRecords = response.recentRecords;
                updateRecentActivity();
                
                if (pieChart) {
                    pieChart.data.datasets[0].data = [
                        response.presentCount,
                        response.absentCount,
                        response.lateCount,
                        response.excusedCount
                    ];
                    pieChart.update();
                }
            } else {
                console.error('Invalid response:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            showErrorToast('Failed to refresh dashboard: ' + error);
        }
    });
}

    function updateRecentRecords(studentId, studentName, newStatus, date) {
        // Add to beginning of recent records
        recentRecords.unshift({
            student_name: studentName,
            status: newStatus,
            date: date
        });
        
        // Keep only last 5 records
        if (recentRecords.length > 5) {
            recentRecords = recentRecords.slice(0, 5);
        }
        
        updateRecentActivity();
    }

    function updateLocalUI(studentId, studentName, newStatus, oldStatus) {
        // Update the status badge
        $(`#status_${studentId}`).removeClass().addClass('badge rounded-pill ' + getStatusBadgeClass(newStatus)).text(newStatus);
        
        // Update counts
        updateCounts(newStatus, oldStatus);
        
        // Update charts
        updateCharts();
    }

    function updateCounts(newStatus, oldStatus) {
        // Only update if status actually changed
        if (oldStatus !== newStatus) {
            // Decrement old status count
            $(`#${oldStatus.toLowerCase()}Count`).text(function(i, oldText) {
                return parseInt(oldText) - 1;
            });
            
            // Increment new status count
            $(`#${newStatus.toLowerCase()}Count`).text(function(i, oldText) {
                return parseInt(oldText) + 1;
            });
            
            // Update attendance rate
            updateAttendanceRate();
        }
    }

    function updateAttendanceRate() {   
    }

    function updateCharts() {
        // Get current counts
        let present = parseInt($('#presentCount').text());
        let absent = parseInt($('#absentCount').text());
        let late = parseInt($('#lateCount').text());
        let excused = parseInt($('#excusedCount').text());
        
        // Update pie chart
        pieChart.data.datasets[0].data = [present, absent, late, excused];
        pieChart.update();
    }

    function updateTotalStudents() {
        $('.attendance-rate').eq(1).text(totalStudents);
    }

    function updateRecentActivity() {
        let recentHtml = '';
        recentRecords.forEach(record => {
            recentHtml += `
                <div class="recent-activity-item ${record.status.toLowerCase()}-border">
                    <strong>${record.student_name}</strong>
                    <span class="badge ${getStatusBadgeClass(record.status)}">
                        ${record.status}
                    </span>
                    <div class="text-muted small">
                        ${new Date(record.date).toLocaleTimeString()}
                    </div>
                </div>`;
        });
        $('.recent-activity').html(recentHtml || '<p class="text-muted text-center">No recent activity</p>');
    }

    function getStatusBadgeClass(status) {
        switch (status) {
            case 'Present': return 'bg-success';
            case 'Absent': return 'bg-danger';
            case 'Late': return 'bg-warning';
            case 'Excused': return 'bg-info';
            default: return 'bg-secondary';
        }
    }

// AI Predictions functionality
$('#getPredictionsBtn').click(function() {
    const button = $(this);
    const days = $('#predictionDays').val();
    
    // Show loading state
    button.html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...');
    button.prop('disabled', true);
    
    $.ajax({
        url: 'ai_predictions.php',
        type: 'POST',
        dataType: 'json', // Explicitly expect JSON
        data: {
            block_name: '<?php echo $currentBlock; ?>',
            user_id: '<?php echo $_SESSION['user_id']; ?>',
            days: days
        },
        success: function(response) {
            if (response && response.success) {
                displayPredictions(response);
                $('#predictionsContainer').show();
            } else {
                showErrorToast(response.error || 'Failed to generate predictions');
            }
        },
        error: function(xhr, status, error) {
            let errorMsg = 'Request failed: ';
            try {
                const response = JSON.parse(xhr.responseText);
                errorMsg += response.error || response.message || status;
            } catch (e) {
                errorMsg += xhr.statusText || error;
            }
            showErrorToast(errorMsg);
        },
        complete: function() {
            button.html('<i class="fas fa-chart-line me-2"></i>Get Predictions');
            button.prop('disabled', false);
        }
    });
});

function displayPredictions(data) {
    const container = $('#predictionsDetails').empty();
    
    // Create summary cards
    const summaryHtml = `
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="prediction-card bg-white p-3 shadow-sm position-relative">
                    <div class="risk-indicator bg-info"></div>
                    <div class="ps-3">
                        <h6 class="text-muted mb-1">Total Students</h6>
                        <h3 class="mb-0">${data.metrics.total_students}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="prediction-card bg-white p-3 shadow-sm position-relative">
                    <div class="risk-indicator bg-warning"></div>
                    <div class="ps-3">
                        <h6 class="text-muted mb-1">Model Accuracy</h6>
                        <h3 class="mb-0">${data.metrics.accuracy || '85'}%</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="prediction-card bg-white p-3 shadow-sm position-relative">
                    <div class="risk-indicator bg-success"></div>
                    <div class="ps-3">
                        <h6 class="text-muted mb-1">Days Analyzed</h6>
                        <h3 class="mb-0">${data.predictions.length}</h3>
                    </div>
                </div>
            </div>
        </div>`;
    container.append(summaryHtml);
    
    // Create prediction cards
    const predictionsHtml = data.predictions.map(pred => {
        const date = new Date(pred.date);
        const dayName = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][pred.day_of_week - 1];
        const riskClass = pred.risk_level === 'high' ? 'danger' : pred.risk_level === 'medium' ? 'warning' : 'success';
        const riskIndicatorClass = pred.risk_level === 'high' ? 'high-risk' : 
                                 pred.risk_level === 'medium' ? 'medium-risk' : 'low-risk';
        
        return `
        <div class="col-md-6 col-lg-4">
            <div class="prediction-card bg-white p-3 h-100 shadow-sm position-relative">
                <div class="risk-indicator ${riskIndicatorClass}"></div>
                <div class="ps-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="mb-0">${date.toLocaleDateString('en-US', { weekday: 'long' })}</h5>
                            <div class="prediction-date small">${date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}</div>
                        </div>
                        <span class="badge bg-${riskClass}">${pred.risk_level.toUpperCase()}</span>
                    </div>
                    
                    <div class="progress prediction-progress mb-3">
                        <div class="progress-bar bg-${riskClass}" 
                             role="progressbar" 
                             style="width: ${Math.round(pred.attendance_rate * 100)}%"
                             aria-valuenow="${Math.round(pred.attendance_rate * 100)}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            ${Math.round(pred.attendance_rate * 100)}%
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Expected:</span>
                        <strong>${pred.expected_attendance}/${pred.total_students}</strong>
                    </div>
                    
                    <div class="prediction-suggestion mt-3">
                        <i class="fas fa-lightbulb text-${riskClass} me-2"></i>
                        ${pred.suggestion}
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');
    
    container.append(`<div class="row g-3">${predictionsHtml}</div>`);
    
    // Initialize/update the chart
    updatePredictionChart(data);
}

function updatePredictionChart(data) {
    const ctx = document.getElementById('predictionsChart').getContext('2d');
    const labels = data.predictions.map(p => {
        const date = new Date(p.date);
        return date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
    });
    
    // Destroy previous chart if exists
    if (window.predictionChart) {
        window.predictionChart.destroy();
    }
    
    window.predictionChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Expected Attendance',
                data: data.predictions.map(p => p.expected_attendance),
                backgroundColor: data.predictions.map(p => 
                    p.risk_level === 'high' ? 'rgba(220, 53, 69, 0.7)' :
                    p.risk_level === 'medium' ? 'rgba(255, 193, 7, 0.7)' :
                    'rgba(40, 167, 69, 0.7)'
                ),
                borderColor: data.predictions.map(p => 
                    p.risk_level === 'high' ? 'rgba(220, 53, 69, 1)' :
                    p.risk_level === 'medium' ? 'rgba(255, 193, 7, 1)' :
                    'rgba(40, 167, 69, 1)'
                ),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: data.metrics.total_students,
                    title: {
                        display: true,
                        text: 'Number of Students'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        afterLabel: function(context) {
                            const pred = data.predictions[context.dataIndex];
                            return [
                                `Day: ${['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][pred.day_of_week - 1]}`,
                                `Risk: ${pred.risk_level.toUpperCase()}`,
                                `Confidence: ${Math.round(pred.confidence * 100)}%`,
                                `Suggestion: ${pred.suggestion}`
                            ];
                        }
                    }
                }
            }
        }
    });
}

function showErrorToast(message) {
    const toast = `
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast show align-items-center text-white bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-exclamation-circle me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>`;
    
    $(toast).appendTo('body').delay(5000).fadeOut(400, function() {
        $(this).remove();
    });
}

 function updateModalContents() {
    // Group students by status
    const statusGroups = {
        Present: [],
        Absent: [],
        Late: [],
        Excused: []
    };
    
    Object.values(studentStatuses).forEach(record => {
        statusGroups[record.status].push(record.name);
    });
    
    // Update each modal
    ['Present', 'Absent', 'Late', 'Excused'].forEach(status => {
        const modalId = `${status.toLowerCase()}Modal`;
        const modalBody = $(`#${modalId} .modal-body`);
        modalBody.empty();
        
        if (statusGroups[status].length > 0) {
            statusGroups[status].forEach(name => {
                const iconClass = status === 'Present' ? 'fa-user-check text-success' : 
                                status === 'Absent' ? 'fa-user-times text-danger' :
                                status === 'Late' ? 'fa-clock text-warning' : 
                                'fa-calendar-check text-info';
                modalBody.append(`
                    <div class="student-item mb-2 p-2 bg-light rounded">
                        <i class="fas ${iconClass} me-2"></i>
                        ${name}
                    </div>
                `);
            });
        } else {
            const iconClass = status === 'Present' ? 'fa-user-slash' : 
                            status === 'Absent' ? 'fa-user-check' :
                            status === 'Late' ? 'fa-clock' : 
                            'fa-calendar-times';
            modalBody.append(`
                <div class="text-center text-muted py-3">
                    <i class="fas ${iconClass} fa-2x mb-2"></i>
                    <p>No students marked as ${status}</p>
                </div>
            `);
        }
    });
}

    </script>
</body>
</html>