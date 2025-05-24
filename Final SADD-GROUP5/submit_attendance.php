<?php

include_once("include/connect.php");

session_start();

if(isset($_SESSION['user_id'])) {

    $user_id = $_SESSION['user_id'];
    $sqlSelect = "SELECT `id`, `student_name`, `status`, `date`, `bu_no`, `block` FROM `attendance`";
    $resultSelect = $conn->query($sqlSelect);

    if ($resultSelect->num_rows > 0) {

        while ($row = $resultSelect->fetch_assoc()) {
            $id = $row['id'];
            $studentName = $row['student_name'];
            $status = $row['status'];
            $date = $row['date'];
            $block_name = $row['block'];
            $bu_no = $row['bu_no'];

            $sqlInsert = "INSERT INTO `archive` (`user_id`, `student_name`, `status`, `date`, `bu_no`, `block`) VALUES ('$user_id', '$studentName', '$status', '$date', '$bu_no', '$block_name')";
            
            if ($conn->query($sqlInsert) === TRUE) {
                echo "Record inserted successfully for ID: $id <br>";

                $sqlDelete = "DELETE FROM `attendance` WHERE `id` = '$id'";
                if ($conn->query($sqlDelete) === TRUE) {
                    echo "Record deleted successfully from attendance table <br>";
                } else {
                    echo "Error deleting record from attendance table: " . $conn->error . "<br>";
                }
            } else {
                echo "Error inserting record: " . $conn->error . "<br>";
            }
        }

        $conn->close();

        header("Location: homepage.php");
        exit();

    } else {
        echo "No records found in the attendance table.";
    }

} else {
    echo "User not logged in.";
    $conn->close();
}
?>
