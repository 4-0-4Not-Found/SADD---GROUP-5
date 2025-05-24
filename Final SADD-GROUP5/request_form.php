<?php
include_once("include/connect.php");

// Select data from students table, excluding inactive students
$sqlSelect = "SELECT `id`, `name`, `status`, `bu_no` FROM `students` WHERE `status` = 'active'";
$resultSelect = $conn->query($sqlSelect);

// Check if there is data to insert
if ($resultSelect->num_rows > 0) {

    // Prepare and execute insert statement for attendance table
    while ($row = $resultSelect->fetch_assoc()) {
        $id = $row['id'];
        $studentName = $row['name'];
        $bu_no = $row['bu_no'];

        $sqlInsert = "INSERT INTO `attendance` (`id`, `student_name`, `status`, `date`, `bu_no`) VALUES ('$id', '$studentName', 'Pending', NOW(), '$bu_no')";

        if ($conn->query($sqlInsert) === TRUE) {
            echo "Record inserted successfully!<br>";
        } else {
            echo "Error inserting record: " . $conn->error . "<br>";
        }
    }

    // Close connection
    $conn->close();

    // Redirect to homepage.php
    header("Location: homepage.php");
    exit();

} else {
    echo "No active records found in the students table.";
    // Close connection
    $conn->close();
}
?>
