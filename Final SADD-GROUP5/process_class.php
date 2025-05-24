<?php
session_start();

// Generate CSRF token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include_once("include/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["block_id"]) && isset($_GET["user_id"])) {
    // Sanitize inputs
    $block_id = (int)$_GET["block_id"];
    $user_id = (int)$_GET["user_id"];

    // Prepare the SQL statement with user_id condition using prepared statement
    $sql = "SELECT * FROM `block` WHERE `block_id` = ? AND `user_id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $block_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Encode the block name to display special characters correctly
        $block_name = mb_convert_encoding($row["block_name"], 'UTF-8', 'ISO-8859-1');
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'No data found for the selected block.'];
        header("Location: index.php");
        exit(); 
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAS - <?php echo htmlspecialchars($block_name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <?php include('include/link.php')?>
    <style>
        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        .student-table {
            border-radius: 8px;
            overflow: hidden;
            font-size: 0.9rem;
        }
        .student-table thead {
            background-color: #343a40;
            color: white;
        }
        .action-btn {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .section-title {
            position: relative;
            margin-bottom: 20px;
            font-size: 1.4rem;
        }
        .section-title:after {
            content: "";
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, #ffc107, #fd7e14);
        }
        .bulk-actions {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .header-title {
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }
        .summary-card {
            font-size: 0.9rem;
        }
        .summary-card .count {
            font-size: 1.8rem;
        }
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
</head>
<body class="bg-light">
<?php include('include/nav.php') ?>

<!-- Alert Display -->
<div class="container mt-3">
    <?php if (isset($_SESSION['alert'])): ?>
        <?php $alert = $_SESSION['alert']; ?>
        <div class="alert alert-<?php echo $alert['type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $alert['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>
</div>

<!-- Main Content -->
<div class="container py-4">
    <!-- Block Header -->
    <div class="text-center mb-4">
        <h1 class="fw-bold header-title"><?php echo htmlspecialchars($block_name); ?></h1>
        <div class="d-flex justify-content-center gap-3">
            <button type="button" class="btn btn-warning rounded-pill px-3 py-2" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-user-plus me-2"></i>ADD STUDENT
            </button>
            <button type="button" class="btn btn-info rounded-pill px-3 py-2" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-import me-2"></i>IMPORT EXCEL
            </button>
        </div>
    </div>

    <!-- Student Summary Card -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <div class="card border-0 shadow-sm card-hover summary-card">
                <div class="card-body text-center py-3">
                    <?php
                    $query = "SELECT COUNT(*) as total_students FROM students WHERE block = ? AND user_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ii", $block_id, $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $total_students = $result->num_rows > 0 ? $result->fetch_assoc()['total_students'] : 0;
                    ?>
                    <h3 class="card-title mb-2" style="font-size: 1.1rem;">Class Summary</h3>
                    <div class="count fw-bold text-primary mb-1"><?php echo $total_students; ?></div>
                    <p class="text-muted mb-0">Total Students</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Student List Section -->
    <div class="bg-white rounded-3 shadow-sm p-3 mb-4">
        <h2 class="text-center section-title">Student List</h2>
        
        <!-- Bulk Actions -->
        <div class="bulk-actions d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="selectAll">
                <label class="form-check-label" for="selectAll">Select All</label>
            </div>
            <button id="deleteSelectedBtn" class="btn btn-danger btn-sm" disabled>
                <i class="fas fa-trash-alt me-1"></i>Delete Selected
            </button>
        </div>
        
        <div class="table-responsive">
            <form id="bulkActionForm" method="POST" action="delete_multiple_students.php">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="block_id" value="<?php echo $block_id; ?>">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                
                <table class="table student-table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="40px"></th>
                            <th class="ps-3">Student Name</th>
                            <th>Status</th>
                            <th>BU Number</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT `name`, `status`, `bu_no`, `block`, `user_id` FROM `students` WHERE `block` = ? AND `user_id` = ? ORDER BY `name` ASC";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("ii", $block_id, $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0):
                            while ($student = $result->fetch_assoc()):
                                $statusClass = $student['status'] == 'active' ? 'status-active' : 'status-inactive';
                        ?>
                                <tr>
                                    <td>
                                        <input class="form-check-input student-checkbox" type="checkbox" name="students[]" value="<?php echo htmlspecialchars($student['bu_no']); ?>">
                                    </td>
                                    <td class="ps-3 fw-medium"><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td>
                                        <form method="POST" action="update_student_status.php" class="m-0">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <input type="hidden" name="update_bu_no" value="<?php echo htmlspecialchars($student['bu_no']); ?>">
                                            <input type="hidden" name="block_id" value="<?php echo $block_id; ?>">
                                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                            <select name="new_status" class="form-select form-select-sm <?php echo $statusClass; ?>" onchange="this.form.submit()">
                                                <option value="active" <?php echo $student['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                                <option value="inactive" <?php echo $student['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                            </select>
                                            <input type="hidden" name="update_status" value="1">
                                        </form>
                                    </td>
                                    <td class="fw-medium"><?php echo htmlspecialchars($student['bu_no']); ?></td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="view_students.php?bu_no=<?php echo htmlspecialchars($student['bu_no']); ?>" 
                                               class="btn btn-outline-primary action-btn rounded-circle" 
                                               data-bs-toggle="tooltip" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form method="POST" action="delete_student.php" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($student['bu_no']); ?>">
                                                <input type="hidden" name="block_id" value="<?php echo $block_id; ?>">
                                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                                <button type="submit" class="btn btn-outline-danger action-btn rounded-circle" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-user-slash fa-2x mb-3"></i>
                                    <p class="mb-0">No students found in this class</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-2">
                <h5 class="modal-title fs-5" id="addModalLabel">Add New Student</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add_student.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="block_id" value="<?php echo $block_id; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                    <div class="row g-2">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control form-control-sm" name="name" placeholder="Enter student name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="bu_no" class="form-label">BU Number</label>
                            <input type="text" class="form-control form-control-sm" name="bu_no" placeholder="Enter BU number" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="reg_id" class="form-label">Registration ID</label>
                            <input type="text" class="form-control form-control-sm" name="reg_id" placeholder="Enter registration ID">
                        </div>
                        <div class="col-md-6">
                            <label for="birthday" class="form-label">Birthday</label>
                            <div class="input-group input-group-sm">
                                <input type="date" class="form-control" name="birthday">
                                <span class="input-group-text bg-light">
                                    <span class="badge bg-secondary">YYYY-MM-DD</span>
                                </span>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label for="contact_no" class="form-label">Contact Number</label>
                            <input type="text" class="form-control form-control-sm" name="contact_no" placeholder="Enter contact number">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email_no1" class="form-label">Primary Email</label>
                            <input type="email" class="form-control form-control-sm" name="email_no1" placeholder="Enter primary email">
                        </div>
                        <div class="col-md-6">
                            <label for="email_no2" class="form-label">BU Email</label>
                            <div class="input-group input-group-sm">
                                <input type="email" class="form-control" name="email_no2" placeholder="Enter BU email" required>
                                <span class="input-group-text bg-light">
                                    <span class="badge bg-info text-dark">Required</span>
                                </span>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                                    <label class="form-check-label" for="male">Male</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" required>
                                    <label class="form-check-label" for="female">Female</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Year Level</label>
                            <select class="form-select form-select-sm" name="year_level" required>
                                <option value="" selected disabled>Select Year Level</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-3 text-end">
                        <button type="button" class="btn btn-sm btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-save me-1"></i>Save Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-2">
                <h5 class="modal-title fs-5" id="importModalLabel">Import Student List</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info py-2">
                    <h5 class="alert-heading fs-6"><i class="fas fa-info-circle me-2"></i>Import Instructions</h5>
                    <ol class="mb-0" style="font-size: 0.9rem;">
                        <li>File should contain these columns in order: <strong>ID NUMBER, NAME, Reg ID, Status, Birthdate, Gender, Year Level, Contact #, Email Add 1, Email Add 2</strong></li>
                        <li>First two rows and last two rows will be skipped</li>
                        <li>Supported formats: .xlsx, .xls, .csv</li>
                    </ol>
                </div>
                
                <div class="card border-0 shadow-sm mt-2">
                    <div class="card-body py-3">
                        <form action="upload.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="block_id" value="<?php echo $block_id; ?>">
                            <input type="hidden" name="user_id" value="<?php echo $_SESSION["user_id"]; ?>">
                            
                            <div class="mb-3">
                                <label for="fileInput" class="form-label fw-medium">Select Excel File</label>
                                <input type="file" class="form-control form-control-sm" id="fileInput" name="fileInput" accept=".csv, .xlsx, .xls" required>
                                <div class="form-text" style="font-size: 0.8rem;">Maximum file size: 5MB</div>
                            </div>
                            
                            <div class="mt-3 text-end">
                                <button type="button" class="btn btn-sm btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fas fa-upload me-1"></i>Import Students
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('include/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Enable tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Select All functionality
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.student-checkbox');
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        const bulkActionForm = document.getElementById('bulkActionForm');

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            updateDeleteButtonState();
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateDeleteButtonState();
                // Uncheck "select all" if any checkbox is unchecked
                if (!this.checked && selectAll.checked) {
                    selectAll.checked = false;
                }
            });
        });

        function updateDeleteButtonState() {
            const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
            deleteSelectedBtn.disabled = checkedCount === 0;
        }

        // Delete Selected functionality
        deleteSelectedBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete the selected students?')) {
                bulkActionForm.submit();
            }
        });
    });
</script>
</body>
</html>