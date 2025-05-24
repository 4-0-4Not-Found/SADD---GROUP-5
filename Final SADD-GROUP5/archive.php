<?php
include_once("include/connect.php");
session_start();

// Function to get status badge class
function getStatusBadgeClass($status) {
    $statusClasses = [
        'Present' => 'bg-success',
        'Absent' => 'bg-danger',
        'Late' => 'bg-warning',
        'Excused' => 'bg-info'
    ];
    return $statusClasses[$status] ?? 'bg-secondary';
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Status updates
    if (isset($_POST['status'])) {
        foreach ($_POST['status'] as $attendanceId => $newStatus) {
            $attendanceId = intval($attendanceId);
            $newStatus = mysqli_real_escape_string($conn, $newStatus);
            
            $updateSql = "UPDATE archive SET status = '$newStatus' WHERE id = $attendanceId";
            mysqli_query($conn, $updateSql);
        }
    }
    
    // Multiple date group deletion
    if (isset($_POST['delete_dates']) && isset($_POST['selected_dates'])) {
        $datesToDelete = array_map(function($date) use ($conn) {
            return "'" . mysqli_real_escape_string($conn, $date) . "'";
        }, $_POST['selected_dates']);
        
        $deleteSql = "DELETE FROM archive 
                     WHERE date IN (" . implode(',', $datesToDelete) . ") 
                     AND user_id = {$_SESSION['user_id']}";
        mysqli_query($conn, $deleteSql);
        
        header('Location: archive.php');
        exit();
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch attendance data for the logged-in user
$user_id = intval($_SESSION['user_id']);
$sql = "SELECT id, student_name as name, status, date, block 
        FROM archive 
        WHERE user_id = $user_id
        ORDER BY date DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database error: " . mysqli_error($conn));
}

$attendanceRecords = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Organize records by date and block
$groupedAttendance = [];
foreach ($attendanceRecords as $record) {
    $groupKey = $record['date'] . '|' . $record['block'];
    
    if (!isset($groupedAttendance[$groupKey])) {
        $groupedAttendance[$groupKey] = [
            'records' => [],
            'block' => $record['block'],
            'date' => $record['date']
        ];
    }
    $groupedAttendance[$groupKey]['records'][] = $record;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive Attendance</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/user.css">
    <?php include('include/link.php') ?>
    <style>
        .accordion-button {
            padding: 1rem 1.25rem;
            cursor: pointer;
        }
        .block-badge {
            font-size: 0.85rem;
            background-color: #6c757d;
        }
        .status-select select {
            min-width: 120px;
            cursor: pointer;
        }
        .date-checkbox-container {
            margin-right: 0.75rem;
        }
        .date-checkbox {
            transform: scale(0.7);
            cursor: pointer;
            position: relative;
            z-index: 2; /* Ensure checkbox is above the accordion button */
        }
        .accordion-item {
            margin-bottom: 0.75rem;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .table caption {
            font-weight: 600;
            color: #495057;
        }
        #deleteDatesBtn {
            transition: all 0.3s ease;
        }
        .empty-state {
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .accordion-header-content {
            flex-grow: 1;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <?php include('include/nav.php') ?>
    
    <div class="container mt-4">
        <div class="text-center mb-4">
            <h1 class="fw-bold">ARCHIVE ATTENDANCE</h1>
            
            <a href="recycle_bin.php" class="btn btn-outline-danger btn-sm mt-2">
                <i class="fas fa-trash-alt me-1"></i> Recycle Bin
            </a>
        </div>

        <form method="post" id="mainForm">
            <!-- Delete Selected Button (hidden by default) -->
            <div class="text-center mb-3">
                <button type="button" class="btn btn-danger rounded-pill" 
                        id="deleteDatesBtn" style="display: none;"
                        data-bs-toggle="modal" data-bs-target="#deleteDatesModal">
                    <i class="fas fa-trash-alt me-2"></i>Delete Selected
                </button>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteDatesModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Deletion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete the selected attendance sheets?</p>
                            <p class="text-danger"><strong>This action cannot be undone.</strong></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="delete_dates" class="btn btn-danger">Confirm Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Records -->
            <div class="mb-5">
                <?php if (empty($groupedAttendance)): ?>
                    <div class="empty-state">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i> No archived attendance records found.
                        </div>
                    </div>
                <?php else: ?>
                    <div class="accordion" id="attendanceAccordion">
                        <?php foreach ($groupedAttendance as $groupKey => $dateData): 
                            $date = $dateData['date'];
                            $block = $dateData['block'];
                            $records = $dateData['records'];
                            
                            // Count statuses
                            $statusCounts = ['Present' => 0, 'Absent' => 0, 'Late' => 0, 'Excused' => 0];
                            foreach ($records as $record) {
                                $statusCounts[$record['status']]++;
                            }
                            $totalRecords = count($records);
                        ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading_<?php echo md5($groupKey); ?>">
                                    <div class="d-flex align-items-center w-100 position-relative">
                                        <div class="date-checkbox-container">
                                            <input type="checkbox" class="form-check-input date-checkbox" 
                                                   name="selected_dates[]" value="<?php echo htmlspecialchars($date); ?>"
                                                   onclick="event.stopPropagation(); toggleDeleteBtn()">
                                        </div>
                                        <button class="accordion-button collapsed position-static" type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapse_<?php echo md5($groupKey); ?>"
                                                aria-expanded="false" 
                                                aria-controls="collapse_<?php echo md5($groupKey); ?>">
                                            <div class="accordion-header-content d-flex flex-wrap align-items-center">
                                                <span class="me-2 fw-bold">
                                                    <?php echo date("D, M d, Y", strtotime($date)); ?>
                                                </span>
                                                <span class="badge block-badge me-2">
                                                    <?php echo htmlspecialchars($block); ?>
                                                </span>
                                                <?php foreach ($statusCounts as $status => $count): 
                                                    if ($count > 0): ?>
                                                        <span class="badge rounded-pill me-2 <?php echo getStatusBadgeClass($status); ?>">
                                                            <?php echo "$status: $count"; ?>
                                                        </span>
                                                    <?php endif;
                                                endforeach; ?>
                                            </div>
                                        </button>
                                    </div>
                                </h2>

                                <div id="collapse_<?php echo md5($groupKey); ?>" class="accordion-collapse collapse" 
                                     aria-labelledby="heading_<?php echo md5($groupKey); ?>" 
                                     data-bs-parent="#attendanceAccordion">
                                    <div class="accordion-body pt-3">
                                        <div class="d-flex justify-content-between mb-3">
                                            <a href="delete_record.php?delete_group=1&delete_date=<?php echo urlencode($date); ?>&block=<?php echo urlencode($block); ?>" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Delete this entire attendance sheet?')">
                                                <i class="fas fa-trash-alt me-1"></i> Delete Sheet
                                            </a>
                                            <span class="text-muted">
                                                Total: <?php echo $totalRecords; ?> records
                                            </span>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <caption class="px-2"><?php echo htmlspecialchars($block); ?></caption>
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th width="40%">Student Name</th>
                                                        <th width="30%">Status</th>
                                                        <th width="25%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($records as $index => $record): ?>
                                                        <tr>
                                                            <td><?php echo $index + 1; ?></td>
                                                            <td><?php echo htmlspecialchars($record['name']); ?></td>
                                                            <td>
                                                                <span class="badge rounded-pill <?php echo getStatusBadgeClass($record['status']); ?>">
                                                                    <?php echo $record['status']; ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <select name="status[<?php echo $record['id']; ?>]" 
                                                                        class="form-select form-select-sm" 
                                                                        onchange="this.form.submit()">
                                                                    <?php foreach (['Present', 'Absent', 'Late', 'Excused'] as $status): ?>
                                                                        <option value="<?php echo $status; ?>" 
                                                                            <?php echo ($record['status'] === $status) ? 'selected' : ''; ?>>
                                                                            <?php echo $status; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <?php include('include/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/updateDateTime.js"></script>
    <?php include('include/modal.php'); ?>

    <script>
        // Toggle delete button based on checked boxes
        function toggleDeleteBtn() {
            const checkboxes = document.querySelectorAll('.date-checkbox:checked');
            document.getElementById('deleteDatesBtn').style.display = 
                checkboxes.length > 0 ? 'inline-block' : 'none';
        }

        // Initialize date/time display
        if (typeof updateDateTime === 'function') {
            updateDateTime();
        }

        // Make entire accordion header clickable except the checkbox
        document.querySelectorAll('.accordion-header').forEach(header => {
            header.addEventListener('click', function(e) {
                // If click was directly on the checkbox, do nothing (let checkbox handle it)
                if (e.target.classList.contains('date-checkbox') || 
                    e.target.closest('.date-checkbox-container')) {
                    return;
                }
                
                // Find the button and trigger click
                const button = this.querySelector('.accordion-button');
                if (button) {
                    button.click();
                }
            });
        });
    </script>
</body>
</html>