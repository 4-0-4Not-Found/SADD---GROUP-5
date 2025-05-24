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
    <title>Student Details</title>
</head>

<body>

    <div class="container mt-5">
        <?php

        session_start(); // Start the session
        include_once("../include/connect.php");

        // Check if the session variable is set
        if (isset($_SESSION["bu_no"])) {
            $bu_no = $_SESSION["bu_no"];

            // Fetch student details based on the BU number
            $query_student = "SELECT * FROM archive WHERE bu_no = '$bu_no'";
            $result_student = mysqli_query($conn, $query_student);

            // Fetch name, img, and status based on the BU number from the students table
            $query_student_details = "SELECT `name`, `img`, `status` FROM `students` WHERE bu_no = '$bu_no'";
            $student_details_result = mysqli_query($conn, $query_student_details);

            if ($result_student && mysqli_num_rows($result_student) > 0 && $student_details_result && mysqli_num_rows($student_details_result) > 0) {
                // Fetch the student details from the archive
                $row_student = mysqli_fetch_assoc($result_student);

                // Fetch the name, img, and status from the students table
                $row_student_details = mysqli_fetch_assoc($student_details_result);

                // Add the new HTML and PHP code for the student details, photo, and "Add Photo" button
                echo '<div class="row">
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8 col-8">
                                            <h2 class="card-title">' . $row_student_details["name"] . '</h2>
                                            <p class="card-text">BU No: ' . $row_student["bu_no"] . '</p>
                                            <p class="card-text badge rounded-pill ' . (($row_student_details["status"] == "active") ? "bg-success" : "bg-danger") . '">
                                                ' . $row_student_details["status"] . '
                                            </p>
                                        </div>
                                        <div class="col-md-4 col-4 justify-content-center">
                                            <!-- Add the following code for the photo -->
                                            <img src="' . $row_student_details["img"] . '" alt="Student Photo" class="img-fluid mb-3 rounded-circle" style="width: 100px; height: 100px;">
                                            <!-- Add the following code for the "Add Photo" button and form -->
                                            <form action="upload_photo.php" method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="bu_no" value="' . $row_student["bu_no"] . '">
                                                <div class="mb-3">
                                                    <label for="photo" class="form-label"></label>
                                                    <input class="form-control" id="photo" type="file" name="photo" accept="image/*">
                                                </div>
                                                <button type="submit" class="btn btn-outline-info rounded-pill">Add Photo</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                // Fetch distinct user_ids and count status from the archive table
                $distinct_user_ids_query = "SELECT user_id, 
                                                    COUNT(CASE WHEN status = 'Present' THEN 1 END) as present_count,
                                                    COUNT(CASE WHEN status = 'Absent' THEN 1 END) as absent_count,
                                                    COUNT(CASE WHEN status = 'Late' THEN 1 END) as late_count,
                                                    COUNT(CASE WHEN status = 'Excused' THEN 1 END) as excused_count
                                            FROM archive 
                                            WHERE bu_no = '$bu_no'
                                            GROUP BY user_id";

                $distinct_user_ids_result = mysqli_query($conn, $distinct_user_ids_query);

                if ($distinct_user_ids_result && mysqli_num_rows($distinct_user_ids_result) > 0) {
                    // Iterate over each distinct user_id
                    while ($distinct_user_id_row = mysqli_fetch_assoc($distinct_user_ids_result)) {
                        $current_user_id = $distinct_user_id_row['user_id'];

                        // Fetch fullname from the users table for the specific user_id
                        $fullname_query = "SELECT `fullname` FROM `users` WHERE user_id = '$current_user_id'";
                        $fullname_result = mysqli_query($conn, $fullname_query);

                        if ($fullname_result && mysqli_num_rows($fullname_result) > 0) {
                            $fullname_row = mysqli_fetch_assoc($fullname_result);
                            $fullname = $fullname_row['fullname'];

                            echo "<div class='container mt-5'>";
                            echo "<div class='row'>";
                            echo "<div class='col-md-6'>";
                            // Display the fullname in the heading
                            echo "<h3>$fullname </h3>";
                        } else {
                            echo "<h3>Profile for User ID: $current_user_id</h3>";
                        }

                        // Fetch and display records from the archive table for the specific user_id
                        $archive_query = "SELECT `id`, `user_id`, `student_name`, `status`, `date`, `bu_no` FROM `archive` WHERE user_id = '$current_user_id' AND bu_no = '$bu_no'";
                        $archive_result = mysqli_query($conn, $archive_query);

                        if ($archive_result && mysqli_num_rows($archive_result) > 0) {
                            while ($archive_row = mysqli_fetch_assoc($archive_result)) {
                                // Add your existing HTML and PHP code for the profile section here
                                // Make sure to replace any hard-coded references to $row_student and $row_student_details with dynamic data based on the current $archive_row
                            }
                        } else {
                            echo "<p>No records found in the archive for User ID: $current_user_id</p>";
                        }

                        echo "</div>"; // Close the row div

                        // Display count in the table for each status outside the inner loop
                        echo "<div class='col-md-6'>";
                        echo "<p class='card-text'>Present: {$distinct_user_id_row['present_count']}</p>";
                        echo "<p class='card-text'>Absent: {$distinct_user_id_row['absent_count']}</p>";
                        echo "<p class='card-text'>Late: {$distinct_user_id_row['late_count']}</p>";
                        echo "<p class='card-text'>Excused: {$distinct_user_id_row['excused_count']}</p>";
                        echo "</div>";

                        // Display a pie chart for each status outside the inner loop
                        echo "<div class='col-md-6 mb-5'>";
                        echo "<canvas class='pieChart' id='pieChart_$current_user_id' width='150' height='150'></canvas>";

                        echo "<script>
                            var ctx = document.getElementById('pieChart_$current_user_id').getContext('2d');
                            var pieChart_$current_user_id = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: ['Present', 'Absent', 'Late', 'Excused'],
                                    datasets: [{
                                        data: [
                                            {$distinct_user_id_row['present_count']},
                                            {$distinct_user_id_row['absent_count']},
                                            {$distinct_user_id_row['late_count']},
                                            {$distinct_user_id_row['excused_count']}
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
                        </script>";
                        echo "</div>";

                        echo "</div>"; // Close the container div
                    }
                } else {
                    echo "<p>No distinct user_ids found in the archive for BU No: $bu_no</p>";
                }
            } else {
                echo "Student not found.";
            }
        } else {
            echo "Invalid request.";
        }

        ?>
    </div>
    <footer class="text-center bg-info text-white py-3 ">
        &copy; 2024 Class Attendance System. All rights reserved.
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
