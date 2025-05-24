<?php
include_once("include/connect.php");

if (isset($_POST['update_status'])) {
    $update_bu_no = $_POST['update_bu_no'];
    $new_status = $_POST['new_status'];

    $update_sql = "UPDATE students SET status = '$new_status' WHERE bu_no = '$update_bu_no'";
    if ($conn->query($update_sql) === TRUE) {
        $update_alert = '<div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 80%;">';
        $update_alert .= 'Status updated successfully!';
        $update_alert .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        $update_alert .= '</div>';
    } else {
        $update_alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 80%;">';
        $update_alert .= 'Error updating status: ' . $conn->error;
        $update_alert .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        $update_alert .= '</div>';
    }

    header("Location: class.php");
    exit(); 
}
?>
