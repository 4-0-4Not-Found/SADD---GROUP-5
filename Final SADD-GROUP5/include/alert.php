<?php
if (isset($_GET['alert'])) {
    $alertType = $_GET['alert'];

    if ($alertType === 'success') {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Attendance records successfully inserted!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    } elseif ($alertType === 'error') {
        $errorMessage = isset($_GET['message']) ? urldecode($_GET['message']) : 'Unknown error';
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error: ' . $errorMessage . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    } elseif ($alertType === 'no_records') {
        echo '<div class="alert alert-info alert-dismissible fade show" role="alert">
                No records to insert.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
}


?>