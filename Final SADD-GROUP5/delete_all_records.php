<?php
include_once("include/connect.php");

if(isset($_GET['delete_date'])) {
    $delete_date = $_GET['delete_date'];

    $sql_delete_by_date = "DELETE FROM deleted_records WHERE date = ?";
    $stmt_delete_by_date = mysqli_prepare($conn, $sql_delete_by_date);
    mysqli_stmt_bind_param($stmt_delete_by_date, "s", $delete_date);
    mysqli_stmt_execute($stmt_delete_by_date);

    if (mysqli_affected_rows($conn) > 0) {
        echo "Records for the date $delete_date have been deleted permanently.";
    } else {
        echo "No records found for the date $delete_date to delete.";
    }

    mysqli_close($conn);

    header("Location: recycle_bin.php");
    exit(); 
} else {
    echo "Date parameter is missing.";
}
?>
