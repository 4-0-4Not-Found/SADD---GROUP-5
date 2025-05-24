<?php
include_once("include/connect.php");
session_start();

$user_id = $_SESSION['user_id'];
$sql = "SELECT DISTINCT date FROM deleted_records WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$deletedDates = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Define the cutoff date (30 days ago)
$cutoff_date = date('Y-m-d', strtotime('-30 days'));

// Prepare and execute the SQL statement to delete records older than 30 days
$sql_delete = "DELETE FROM deleted_records WHERE date < ?";
$stmt_delete = mysqli_prepare($conn, $sql_delete);
mysqli_stmt_bind_param($stmt_delete, "s", $cutoff_date);
mysqli_stmt_execute($stmt_delete);

// Check if any records were deleted
$records_deleted = mysqli_affected_rows($conn);
$deletion_message = $records_deleted > 0 
    ? "Automatically cleaned up $records_deleted record(s) older than 30 days." 
    : "No expired records found (older than 30 days).";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/user.css">
    <?php include('include/link.php')?>
    <title>Recycle Bin</title>
    <style>
        :root {
            --primary-color: #4e73df;
            --danger-color: #e74a3b;
            --success-color: #1cc88a;
            --warning-color: #f6c23e;
            --info-color: #36b9cc;
        }
        
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f8f9fc;
        }
        
        .recycle-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 0.5rem rgba(0,0,0,0.1);
        }
        
        .accordion-item {
            border: none;
            margin-bottom: 1rem;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 0.5rem rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .accordion-button {
            font-weight: 600;
            background-color: #f8f9fc;
            padding: 1.25rem 1.5rem;
        }
        
        .accordion-button:not(.collapsed) {
            background-color: rgba(78, 115, 223, 0.1);
            color: var(--primary-color);
            box-shadow: none;
        }
        
        .accordion-button::after {
            background-size: 1rem;
        }
        
        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 600;
            min-width: 70px;
            display: inline-block;
            text-align: center;
        }
        
        .badge-present {
            background-color: var(--success-color);
        }
        
        .badge-absent {
            background-color: var(--danger-color);
        }
        
        .badge-late {
            background-color: var(--warning-color);
            color: #1f2d3d;
        }
        
        .badge-excused {
            background-color: var(--info-color);
        }
        
        .empty-state {
            min-height: 60vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        
        .empty-state-icon {
            font-size: 5rem;
            color: #dddfeb;
            margin-bottom: 1.5rem;
        }
        
        .record-count {
            font-size: 0.85rem;
            color: #6e707e;
            background-color: #eaecf4;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
        }
        
        .table th {
            background-color: #f8f9fc;
            font-weight: 600;
            color: #4e73df;
        }
        
        .action-buttons .btn {
            min-width: 140px;
        }
        
        .modal-header {
            background-color: var(--primary-color);
            color: white;
        }
        
        .modal-header .btn-close {
            filter: invert(1);
        }
    </style>
</head>

<body>
    <?php include('include/nav.php') ?>
    
    <div class="container py-4">
        <div class="recycle-header p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1"><i class="fas fa-trash-restore me-2"></i> Recycle Bin</h2>
                    <p class="mb-0">Recover or permanently delete deleted attendance records</p>
                </div>
                <div class="text-white-50 small">
                    <i class="fas fa-info-circle me-1"></i> Records auto-delete after 30 days
                </div>
            </div>
        </div>

        <?php if (!empty($deletion_message)): ?>
            <div class="alert alert-info alert-dismissible fade show mb-4">
                <i class="fas fa-info-circle me-2"></i><?php echo $deletion_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($deletedDates)) : ?>
            <div class="empty-state">
                <i class="fas fa-trash-alt empty-state-icon"></i>
                <h3 class="text-gray-800 mb-3">Recycle Bin is Empty</h3>
                <p class="text-gray-600 mb-4">No deleted records found in the recycle bin</p>
                <a href="archive.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Archive
                </a>
            </div>
        <?php else : ?>
            <div class="accordion" id="recycleAccordion">
                <?php foreach ($deletedDates as $index => $deletedDate) : ?>
                    <?php
                    $date = $deletedDate['date'];
                    $sql = "SELECT * FROM deleted_records WHERE user_id = ? AND date = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "is", $user_id, $date);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $deletedRecords = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    $recordCount = count($deletedRecords);
                    
                    // Count statuses for summary
                    $statusCounts = array_count_values(array_column($deletedRecords, 'status'));
                    ?>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="false" 
                                    aria-controls="collapse<?php echo $index; ?>">
                                <div class="d-flex justify-content-between w-100 align-items-center pe-2">
                                    <div>
                                        <i class="far fa-calendar-alt me-2"></i>
                                        <span class="fw-bold"><?php echo date("l, F j, Y", strtotime($date)); ?></span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <?php foreach ($statusCounts as $status => $count): ?>
                                            <span class="badge rounded-pill me-2 status-badge badge-<?php echo strtolower($status); ?>">
                                                <?php echo $count . ' ' . $status; ?>
                                            </span>
                                        <?php endforeach; ?>
                                        <span class="record-count">
                                            <?php echo $recordCount . ' record' . ($recordCount !== 1 ? 's' : ''); ?>
                                        </span>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        
                        <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse" 
                             aria-labelledby="heading<?php echo $index; ?>" data-bs-parent="#recycleAccordion">
                            <div class="accordion-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>BU Number</th>
                                                <th>Student Name</th>
                                                <th>Status</th>
                                                <th>Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($deletedRecords as $record) : ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($record['bu_no']); ?></td>
                                                    <td><?php echo htmlspecialchars($record['student_name']); ?></td>
                                                    <td>
                                                        <span class="badge rounded-pill status-badge badge-<?php echo strtolower($record['status']); ?>">
                                                            <?php echo htmlspecialchars($record['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo date("h:i A", strtotime($record['date'])); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-3 p-3 bg-light border-top action-buttons">
                                    <a href="restore_records.php?delete_group=1&delete_date=<?php echo urlencode($date); ?>" 
                                       class="btn btn-success">
                                        <i class="fas fa-undo me-2"></i> Restore All
                                    </a>
                                    <button type="button" class="btn btn-danger" 
                                            data-bs-toggle="modal" data-bs-target="#deleteAllModal<?php echo $index; ?>">
                                        <i class="fas fa-trash-alt me-2"></i> Delete Permanently
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteAllModal<?php echo $index; ?>" tabindex="-1" 
                         aria-labelledby="deleteAllModalLabel<?php echo $index; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Confirm Permanent Deletion
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>You are about to permanently delete <strong><?php echo $recordCount; ?> record<?php echo $recordCount !== 1 ? 's' : ''; ?></strong> from:</p>
                                    <p class="fw-bold text-center my-3"><?php echo date("l, F j, Y", strtotime($date)); ?></p>
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        This action cannot be undone. All data will be permanently lost.
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i> Cancel
                                    </button>
                                    <a href="delete_all_records.php?delete_date=<?php echo urlencode($date); ?>" 
                                       class="btn btn-danger">
                                        <i class="fas fa-trash-alt me-2"></i> Delete Permanently
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include('include/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php include('include/modal.php'); ?>
</body>
</html>