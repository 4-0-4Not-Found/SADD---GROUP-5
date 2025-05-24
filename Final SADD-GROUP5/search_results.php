<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <style>
        .search-result-section {
            margin-bottom: 3rem;
        }
        .student-card {
            transition: transform 0.3s ease;
        }
        .student-card:hover {
            transform: translateY(-5px);
        }
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }
        .no-results {
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <?php include('include/nav.php') ?>

    <div class="container mt-4">
        <?php
        include_once("include/connect.php");

        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["searchkey"]) && !empty($_GET["searchkey"])) {
            $searchKey = mysqli_real_escape_string($conn, $_GET["searchkey"]);
            
            // Check if search key matches a date pattern (e.g., "Nov 5")
            $isDateSearch = preg_match('/^[A-Za-z]{3}\s\d{1,2}$/', $searchKey);
            
            // Search for students
            $studentQuery = "SELECT `id`, `name`, `status`, `bu_no`, `img` 
                            FROM `students`
                            WHERE `name` LIKE ? OR `bu_no` LIKE ?";
            
            $stmt = $conn->prepare($studentQuery);
            $likeParam = "%$searchKey%";
            $stmt->bind_param("ss", $likeParam, $likeParam);
            $stmt->execute();
            $studentResult = $stmt->get_result();
            
            // Search archive if date pattern matches
            $archiveResult = null;
            if ($isDateSearch) {
                $dateQuery = "SELECT `id`, `user_id`, `student_name`, `status`, `date`, `bu_no` 
                             FROM `archive`
                             WHERE DATE_FORMAT(`date`, '%b %e') = ?";
                $stmt = $conn->prepare($dateQuery);
                $stmt->bind_param("s", $searchKey);
                $stmt->execute();
                $archiveResult = $stmt->get_result();
            }
            
            // Display student results
            if ($studentResult && $studentResult->num_rows > 0) {
                echo '<div class="search-result-section">';
                echo '<h2 class="mb-4">Student Results</h2>';
                echo '<div class="row">';
                
                $studentCount = 0;
                while ($row = $studentResult->fetch_assoc()) {
                    $studentCount++;
                    $bu_no = $row['bu_no'];
                    
                    // Get attendance counts
                    $countQuery = "SELECT 
                                    COUNT(CASE WHEN status = 'Absent' THEN 1 END) as absent_count,
                                    COUNT(CASE WHEN status = 'Present' THEN 1 END) as present_count,
                                    COUNT(CASE WHEN status = 'Late' THEN 1 END) as late_count,
                                    COUNT(CASE WHEN status = 'Excused' THEN 1 END) as excused_count
                                   FROM archive WHERE bu_no = ?";
                    $stmt = $conn->prepare($countQuery);
                    $stmt->bind_param("s", $bu_no);
                    $stmt->execute();
                    $countResult = $stmt->get_result();
                    
                    $countRow = $countResult->fetch_assoc();
                    $absentCount = $countRow['absent_count'] ?? 0;
                    $presentCount = $countRow['present_count'] ?? 0;
                    $lateCount = $countRow['late_count'] ?? 0;
                    $excusedCount = $countRow['excused_count'] ?? 0;
                    
                    // Generate unique ID for chart
                    $chartId = 'chart-' . $studentCount;
                    ?>
                    
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card student-card shadow h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-5 text-center">
                                        <img src="<?= htmlspecialchars($row['img']) ?>" 
                                             alt="Student Photo" 
                                             class="img-fluid rounded-circle mb-3" 
                                             style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-7">
                                        <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                                        <p class="card-text">BU No: <?= htmlspecialchars($row['bu_no']) ?></p>
                                        <span class="badge rounded-pill bg-<?= $row['status'] == 'active' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="chart-container mt-3">
                                    <canvas id="<?= $chartId ?>"></canvas>
                                </div>
                                
                                <div class="attendance-stats mt-2">
                                    <small class="d-block"><i class="fas fa-check-circle text-success"></i> Present: <?= $presentCount ?></small>
                                    <small class="d-block"><i class="fas fa-times-circle text-danger"></i> Absent: <?= $absentCount ?></small>
                                    <small class="d-block"><i class="fas fa-clock text-warning"></i> Late: <?= $lateCount ?></small>
                                    <small class="d-block"><i class="fas fa-info-circle text-info"></i> Excused: <?= $excusedCount ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var ctx = document.getElementById('<?= $chartId ?>').getContext('2d');
                            new Chart(ctx, {
                                type: 'doughnut',
                                data: {
                                    labels: ['Absent', 'Present', 'Late', 'Excused'],
                                    datasets: [{
                                        data: [<?= $absentCount ?>, <?= $presentCount ?>, <?= $lateCount ?>, <?= $excusedCount ?>],
                                        backgroundColor: [
                                            'rgba(255, 99, 132, 0.7)',
                                            'rgba(75, 192, 192, 0.7)',
                                            'rgba(255, 205, 86, 0.7)',
                                            'rgba(54, 162, 235, 0.7)'
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
                        });
                    </script>
                    <?php
                }
                echo '</div></div>';
            } elseif (!$isDateSearch) {
                echo '<div class="no-results">';
                echo '<div class="alert alert-info text-center w-100">';
                echo 'No student records found for: ' . htmlspecialchars($searchKey);
                echo '</div></div>';
            }
            
            // Display archive results if date search
            if ($isDateSearch && $archiveResult && $archiveResult->num_rows > 0) {
                echo '<div class="search-result-section mt-5">';
                echo '<h2 class="mb-4">Attendance Records for ' . htmlspecialchars($searchKey) . '</h2>';
                echo '<div class="table-responsive">';
                echo '<table class="table table-hover align-middle">';
                echo '<thead class="table-light">';
                echo '<tr><th>Student</th><th>Status</th><th>Date</th><th>BU No</th></tr>';
                echo '</thead><tbody>';
                
                while ($row = $archiveResult->fetch_assoc()) {
                    $statusClass = [
                        'Present' => 'success',
                        'Absent' => 'danger',
                        'Late' => 'warning',
                        'Excused' => 'info'
                    ][$row['status']] ?? 'secondary';
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['student_name']) ?></td>
                        <td><span class="badge bg-<?= $statusClass ?>"><?= $row['status'] ?></span></td>
                        <td><?= date("M j, Y", strtotime($row['date'])) ?></td>
                        <td><?= htmlspecialchars($row['bu_no']) ?></td>
                    </tr>
                    <?php
                }
                echo '</tbody></table></div></div>';
            } elseif ($isDateSearch && (!$archiveResult || $archiveResult->num_rows === 0)) {
                echo '<div class="no-results">';
                echo '<div class="alert alert-info text-center w-100">';
                echo 'No attendance records found for date: ' . htmlspecialchars($searchKey);
                echo '</div></div>';
            }
        } else {
            echo '<div class="no-results">';
            echo '<div class="alert alert-warning text-center w-100">';
            echo 'Please enter a search term';
            echo '</div></div>';
        }
        ?>
    </div>

    <?php include('include/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php include('include/modal.php'); ?>
</body>
</html>