<?php
session_start();
include('include/nav.php');
include_once("include/connect.php");
include_once("include/functions.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('include/link.php')?>
    <link rel="stylesheet" href="css/user.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --danger-color: #ef233c;
            --late-color: #ffc107;
            --absence-color: #dc3545;
            --border-radius: 12px;
            --box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7ff;
            color: var(--dark-color);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        h2 {
            color: white;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .card {
            background-color: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 0;
            margin-bottom: 25px;
            border: none;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }
        
        /* Absence Card (Red) */
        .card-absence {
            border-top: 4px solid var(--absence-color);
        }
        
        .card-absence .card-header {
            background-color: var(--absence-color);
        }
        
        .card-absence .student-record {
            background-color: rgba(220, 53, 69, 0.05);
            border-left: 4px solid var(--absence-color);
        }
        
        .card-absence .student-record:hover {
            background-color: rgba(220, 53, 69, 0.1);
        }
        
        .card-absence .student-record strong {
            color: var(--absence-color);
        }
        
        /* Late Card (Yellow) */
        .card-late {
            border-top: 4px solid var(--late-color);
        }
        
        .card-late .card-header {
            background-color: var(--late-color);
        }
        
        .card-late .student-record {
            background-color: rgba(255, 193, 7, 0.05);
            border-left: 4px solid var(--late-color);
        }
        
        .card-late .student-record:hover {
            background-color: rgba(255, 193, 7, 0.1);
        }
        
        .card-late .student-record strong {
            color: #d39e00;
        }
        
        .card-header {
            color: white;
            padding: 18px 25px;
            border-bottom: none;
        }
        
        .card-header h2 {
            color: white;
            margin: 0;
            font-size: 1.3rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        .card-body {
            padding: 25px;
        }
        
        .student-record {
            padding: 15px;
            margin-bottom: 12px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        
        .student-record:hover {
            transform: translateX(5px);
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .alert-info {
            color: #084298;
            background-color: #cfe2ff;
            border-left: 4px solid #9ec5fe;
        }
        
        .alert-danger {
            color: #842029;
            background-color: #f8d7da;
            border-left: 4px solid #f1aeb5;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 8px;
        }
        
        .badge-absence {
            background-color: var(--absence-color);
            color: white;
        }
        
        .badge-late {
            background-color: #d39e00;
            color: white;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }
        
        .col-md-6 {
            padding: 0 15px;
            flex: 0 0 50%;
            max-width: 50%;
        }
        
        @media (max-width: 768px) {
            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
    <title>Class Attendance System</title>
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card card-absence">
                <div class="card-header">
                    <h2><i class="fas fa-user-slash mr-2"></i>STUDENTS WITH 3 OR MORE ABSENCES</h2>
                </div>
                <div class="card-body">
                    <?php
                    $sql_absences = "SELECT `student_name`, `block`, COUNT(*) AS total_absences
                        FROM `archive`
                        WHERE `status` = 'absent'
                        GROUP BY `student_name`, `block`
                        HAVING COUNT(*) >= 3
                        ORDER BY total_absences DESC";

                    $result_absences = mysqli_query($conn, $sql_absences);

                    if (mysqli_num_rows($result_absences) > 0) {
                        while ($row = mysqli_fetch_assoc($result_absences)) {
                            $student_name = htmlspecialchars($row['student_name']);
                            $total_absences = $row['total_absences'];
                            $block = htmlspecialchars($row['block']);
                            echo "<div class='student-record'>
                                    <p><strong>$student_name</strong> from <strong>$block</strong> 
                                    <span class='badge badge-absence'>$total_absences absences</span></p>
                                  </div>"; 
                        }
                    } else {
                        echo "<div class='alert alert-info'>
                                <i class='fas fa-info-circle mr-2'></i>No students found with 3 or more absences.
                              </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-late">
                <div class="card-header">
                    <h2><i class="fas fa-clock mr-2"></i>STUDENTS WITH 5 OR MORE LATES</h2>
                </div>
                <div class="card-body">
                    <?php
                    $sql_lates = "SELECT `student_name`, `block`, COUNT(*) AS total_lates
                                FROM `archive`
                                WHERE `status` = 'late'
                                GROUP BY `student_name`, `block`
                                HAVING COUNT(*) >= 5
                                ORDER BY total_lates DESC";

                    $result_lates = mysqli_query($conn, $sql_lates);

                    if (mysqli_num_rows($result_lates) > 0) {
                        while ($row = mysqli_fetch_assoc($result_lates)) {
                            $student_name = htmlspecialchars($row['student_name']);
                            $total_lates = $row['total_lates'];
                            $block = htmlspecialchars($row['block']);
                            echo "<div class='student-record'>
                                    <p><strong>$student_name</strong> from <strong>$block</strong> 
                                    <span class='badge badge-late'>$total_lates lates</span></p>
                                  </div>"; 
                        }
                    } else {
                        echo "<div class='alert alert-info'>
                                <i class='fas fa-info-circle mr-2'></i>No students found with 5 or more lates.
                              </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('include/modal.php'); ?>
<?php include('include/footer.php'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationCount = <?= countNewViolators($conn) ?>;
        const bubble = document.querySelector('.notification-bubble');
        
        if (notificationCount > 0) {
            bubble.textContent = notificationCount;
            bubble.style.display = 'flex';
        } else {
            bubble.style.display = 'none';
        }
    });
</script>

</body>
</html>