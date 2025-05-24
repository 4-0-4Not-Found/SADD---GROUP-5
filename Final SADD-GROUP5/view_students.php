<?php
include_once("include/connect.php");

if (isset($_GET['bu_no'])) {
    $bu_no = $_GET['bu_no'];
    $query = "SELECT * FROM archive WHERE bu_no = '$bu_no'";
    $result = $conn->query($query);

    $query = "SELECT * FROM students WHERE bu_no = '$bu_no'";
    $result_student = $conn->query($query);
    

    if ($result->num_rows > 0 || $result_student->num_rows > 0) {
        $row = $result->fetch_assoc();
        $row_student = $result_student->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/user.css">
    <!-- Font Awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <?php include('include/link.php')?>
    <title>Student Details</title>
    <style>
        .bg-pink {
            background-color: pink;
            color: white; 
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-content {
            flex: 1;
        }
        footer {
            margin-top: auto;
        }
    </style>
</head>

<body>
    <?php include('include/nav.php') ?>
    
    <div class="main-content">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h2 class="card-title"><?php echo $row_student['name']; ?></h2>
                                    <p class="card-text">BU No: <?php echo $row_student['bu_no']; ?></p>
                                    <p class="card-text">Reg ID: <?php echo $row_student['reg_id']; ?></p>
                                    <p class="card-text">Birthdate: <?php echo date('M d, Y', strtotime($row_student['birthdate'])); ?></p>
                                    <?php
                                    $gender = $row_student['gender'];
                                    if ($gender === 'Male') {
                                        echo "<p class='card-text'>Gender: <span class='badge bg-info'>$gender</span></p>";
                                    } elseif ($gender === 'Female') {
                                        echo "<p class='card-text'>Gender: <span class='badge bg-pink'>$gender</span></p>";
                                    } else {
                                        echo "<p class='card-text'>Gender: <span class='badge bg-secondary'>$gender</span></p>";
                                    }
                                    ?>
                                    <p class="card-text">Year Level: <?php echo $row_student['year_level']; ?> Year</p>
                                    <p class="card-text">Contact No: <?php echo $row_student['contact_number']; ?></p>
                                    <p class="card-text">BU No: <?php echo $row_student['bu_no']; ?></p>
                                    <p class="card-text">BU email: <?php echo $row_student['email_add2']; ?></p>
                                    <p class="card-text badge rounded-pill <?php echo ($row_student['status'] == 'active') ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo $row_student['status']; ?>
                                    </p>

                                    <?php
                                    // Count present, absent, late, and excused records
                                    $countQuery = "SELECT 
                                                    COUNT(CASE WHEN status = 'Present' THEN 1 END) as present_count,
                                                    COUNT(CASE WHEN status = 'Absent' THEN 1 END) as absent_count,
                                                    COUNT(CASE WHEN status = 'Late' THEN 1 END) as late_count,
                                                    COUNT(CASE WHEN status = 'Excused' THEN 1 END) as excused_count
                                                FROM archive WHERE bu_no = '$bu_no'";
                                    $countResult = $conn->query($countQuery);

                                    if ($countResult->num_rows > 0) {
                                        $countRow = $countResult->fetch_assoc();

                                        // Check for students with more than 3 absences
                                        $absencesQuery = "SELECT `student_name`, COUNT(*) AS total_absences
                                                            FROM `archive`
                                                            WHERE `status` = 'Absent' AND `bu_no` = '$bu_no'
                                                            GROUP BY `student_name`
                                                            HAVING COUNT(*) >= 3";
                                        $absencesResult = $conn->query($absencesQuery);
                                        if ($absencesResult->num_rows > 0) {
                                            echo "<div class='alert alert-danger' role='alert'>";
                                            while ($absencesRow = $absencesResult->fetch_assoc()) {
                                                echo "Drop Alert {$absencesRow['student_name']} has {$absencesRow['total_absences']} absences<br>";
                                            }
                                            echo "</div>";
                                        } else {
                                            // Check for students with more than 2 absences
                                            $absencesQuery = "SELECT `student_name`, COUNT(*) AS total_absences
                                                                FROM `archive`
                                                                WHERE `status` = 'Absent' AND `bu_no` = '$bu_no'
                                                                GROUP BY `student_name`
                                                                HAVING COUNT(*) >= 2";
                                            $absencesResult = $conn->query($absencesQuery);
                                            if ($absencesResult->num_rows > 0) {
                                                echo "<div class='alert alert-warning' role='alert'>";
                                                while ($absencesRow = $absencesResult->fetch_assoc()) {
                                                    echo "Drop Alert {$absencesRow['student_name']} has {$absencesRow['total_absences']} absences<br>";
                                                }
                                                echo "</div>";
                                            }
                                        }

                                        // Check for students with more than 5 lates
                                        $latesQuery = "SELECT `student_name`, COUNT(*) AS total_lates
                                                        FROM `archive`
                                                        WHERE `status` = 'Late' AND `bu_no` = '$bu_no'
                                                        GROUP BY `student_name`
                                                        HAVING COUNT(*) >= 5";
                                        $latesResult = $conn->query($latesQuery);
                                        if ($latesResult->num_rows > 0) {
                                            echo "<div class='alert alert-danger' role='alert'>";
                                            while ($latesRow = $latesResult->fetch_assoc()) {
                                                echo "Drop Alert {$latesRow['student_name']} has {$latesRow['total_lates']} lates<br>";
                                            }
                                            echo "</div>";
                                        } else {
                                            // Check for students with more than 3 lates
                                            $latesQuery = "SELECT `student_name`, COUNT(*) AS total_lates
                                                            FROM `archive`
                                                            WHERE `status` = 'Late' AND `bu_no` = '$bu_no'
                                                            GROUP BY `student_name`
                                                            HAVING COUNT(*) >= 3";
                                            $latesResult = $conn->query($latesQuery);
                                            if ($latesResult->num_rows > 0) {
                                                echo "<div class='alert alert-warning' role='alert'>";
                                                while ($latesRow = $latesResult->fetch_assoc()) {
                                                    echo "Drop Alert {$latesRow['student_name']} has {$latesRow['total_lates']} lates<br>";
                                                }
                                                echo "</div>";
                                            }
                                        }
                                    } else {
                                        echo "<p class='card-text'>No records found in the archive.</p>";
                                    }
                                    ?>
                                    <?php
                                    // Query to count present, absent, late, and excused records
                                    $countQuery = "SELECT 
                                                    COUNT(CASE WHEN status = 'Present' THEN 1 END) as present_count,
                                                    COUNT(CASE WHEN status = 'Absent' THEN 1 END) as absent_count,
                                                    COUNT(CASE WHEN status = 'Late' THEN 1 END) as late_count,
                                                    COUNT(CASE WHEN status = 'Excused' THEN 1 END) as excused_count
                                                FROM archive WHERE bu_no = '$bu_no'";
                                                
                                    // Execute the query
                                    $countResult = $conn->query($countQuery);

                                    if ($countResult->num_rows > 0) {
                                        $countRow = $countResult->fetch_assoc();

                                        // Calculate total count
                                        $totalCount = $countRow['present_count'] + $countRow['absent_count'] + $countRow['late_count'] + $countRow['excused_count'];

                                        // Display level measuring pill if there are records
                                        if ($totalCount > 0) {
                                            // Calculate percentages
                                            $presentPercent = ($countRow['present_count'] / $totalCount) * 100;
                                            $absentPercent = ($countRow['absent_count'] / $totalCount) * 100;
                                            $latePercent = ($countRow['late_count'] / $totalCount) * 100;
                                            $excusedPercent = ($countRow['excused_count'] / $totalCount) * 100;

                                            // Display count in the level measuring pill with adjusted height and font size
                                            echo "<div class='progress rounded-pill' style='height: 50px;'>";
                                            echo "<div class='progress-bar bg-success' role='progressbar' style='width: {$presentPercent}%; font-size: 1rem;' aria-valuenow='{$countRow['present_count']}' aria-valuemin='0' aria-valuemax='{$totalCount}'>Present <span class='badge badge-pill badge-light' style='font-size: 0.8rem;'>{$countRow['present_count']}</span></div>";
                                            echo "<div class='progress-bar bg-danger' role='progressbar' style='width: {$absentPercent}%; font-size: 1rem;' aria-valuenow='{$countRow['absent_count']}' aria-valuemin='0' aria-valuemax='{$totalCount}'>Absent <span class='badge badge-pill badge-light' style='font-size: 0.8rem;'>{$countRow['absent_count']}</span></div>";
                                            echo "<div class='progress-bar bg-warning' role='progressbar' style='width: {$latePercent}%; font-size: 1rem;' aria-valuenow='{$countRow['late_count']}' aria-valuemin='0' aria-valuemax='{$totalCount}'>Late <span class='badge badge-pill badge-light' style='font-size: 0.8rem;'>{$countRow['late_count']}</span></div>";
                                            echo "<div class='progress-bar bg-info' role='progressbar' style='width: {$excusedPercent}%; font-size: 1rem;' aria-valuenow='{$countRow['excused_count']}' aria-valuemin='0' aria-valuemax='{$totalCount}'>Excused <span class='badge badge-pill badge-light' style='font-size: 0.8rem;'>{$countRow['excused_count']}</span></div>";
                                            echo "</div>";
                                        } else {
                                            // If total count is zero, display a message
                                            echo "<p>No attendance records found.</p>";
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="col-md-4 justify-content-center">
                                    <?php
                                    // Check if $row_student['img'] is set
                                    if(isset($row_student['img']) && !empty($row_student['img']) && file_exists($row_student['img'])) {
                                        $imageSource = $row_student['img'];
                                    } else {
                                        $imageSource = "img/studentalt.png";
                                    }
                                    ?>
                                    <img src="<?php echo htmlspecialchars($imageSource); ?>" alt="Student Photo" class="img-fluid mb-3 rounded-circle" style="width: 100px; height: 100px;">

                                    <!-- Photo upload form -->
                                    <form action="upload_photo.php" method="post" enctype="multipart/form-data" id="photoForm">
                                        <input type="hidden" name="bu_no" value="<?php echo htmlspecialchars($row_student['bu_no']); ?>">
                                        <div class="mb-3">
                                            <label for="photo" class="form-label">Select New Photo</label>
                                            <input class="form-control" type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/gif" required>
                                            <small class="text-muted">Max size: 2MB (JPEG, PNG, GIF)</small>
                                        </div>
                                        <button type="submit" class="btn btn-outline-info rounded-pill">Upload Photo</button>
                                        <div id="uploadStatus" class="mt-2"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Smaller Pie Chart -->
                    <canvas id="pieChart" width="150" height="150"></canvas>
                    <script>
                        var ctx = document.getElementById('pieChart').getContext('2d');
                        var pieChart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: ['Present', 'Absent', 'Late', 'Excused'],
                                datasets: [{
                                    data: [
                                        <?php echo round(($countRow['present_count'] / ($countRow['present_count'] + $countRow['absent_count'] + $countRow['late_count'] + $countRow['excused_count'])) * 100, 2); ?>,
                                        <?php echo round(($countRow['absent_count'] / ($countRow['present_count'] + $countRow['absent_count'] + $countRow['late_count'] + $countRow['excused_count'])) * 100, 2); ?>,
                                        <?php echo round(($countRow['late_count'] / ($countRow['present_count'] + $countRow['absent_count'] + $countRow['late_count'] + $countRow['excused_count'])) * 100, 2); ?>,
                                        <?php echo round(($countRow['excused_count'] / ($countRow['present_count'] + $countRow['absent_count'] + $countRow['late_count'] + $countRow['excused_count'])) * 100, 2); ?>,
                                    ],
                                    backgroundColor: [
                                        'rgba(144, 238, 144, 0.7)', // Blue-green
                                        'rgba(255, 187, 187, 0.7)', // Lighter red
                                        'rgba(255, 241, 184, 0.7)', // Lighter yellow
                                        'rgba(197, 232, 255, 0.7)', // Lighter cyan
                                    ],
                                    borderColor: [
                                        'rgba(0, 128, 128, 1)', // Teal border
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(255, 205, 86, 1)',
                                        'rgba(54, 162, 235, 1)',
                                    ],
                                    borderWidth: 1
                                }]
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>

    <?php include('include/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php include('include/modal.php'); ?>

</body>
</html>

<?php
    } else {
        echo "Student not found.";
    }
} else {
    echo "Invalid request.";
}
?>