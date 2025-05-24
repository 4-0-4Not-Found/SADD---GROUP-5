<?php
session_start();

// Generate CSRF token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include_once("include/connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAS - CLASS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iK7t9QQvR1ciRDJC2L/HzIq1qVRyHh4eZL2M/iPh47Ha6Q5iS9x2lVO" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/user.css">    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <?php include('include/link.php')?>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --accent-color: #f39c12;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Montserrat', sans-serif;
        }
        
        .h-font {
            font-family: 'Quicksand', sans-serif;
            color: var(--dark-color);
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 3rem 0;
            color: white;
            border-radius: 0 0 2rem 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .h-line {
            width: 150px;
            margin: 0 auto;
            height: 3px;
        }
        
        .class-card {
            background-color: white;
            border-radius: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            height: 100%;
        }
        
        .class-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .class-card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem;
            text-align: center;
            font-weight: 600;
        }
        
        .class-card-body {
            padding: 1.5rem;
        }
        
        .create-btn {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.8rem 2rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        
        .create-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
            background: linear-gradient(45deg, var(--secondary-color), var(--primary-color));
        }
        
        .class-btn {
            background: linear-gradient(45deg, var(--accent-color), #e67e22);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.8rem 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .class-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
            background: linear-gradient(45deg, #e67e22, var(--accent-color));
        }
        
        .year-heading {
            position: relative;
            margin: 2.5rem 0 1.5rem;
            padding-bottom: 0.5rem;
            color: var(--dark-color);
            font-weight: 700;
        }
        
        .year-heading::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        }
        
        .modal-content {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 1rem 1rem 0 0;
        }
        
        .modal-title {
            font-weight: 700;
        }
        
        .form-control {
            border-radius: 0.5rem;
            padding: 0.8rem;
            border: 1px solid #ddd;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.25);
            border-color: var(--primary-color);
        }
        
        .btn-close {
            background-color: white;
            opacity: 1;
        }
        
        .empty-state {
            padding: 3rem;
            text-align: center;
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php include('include/nav.php') ?>

    <div class="page-header">
        <div class="container">
            <h1 class="fw-bold h-font text-center">CLASS</h1>
            <div class="h-line bg-light col-12 mt-3"></div>
            <p class="text-center mt-3">
    Organize your classes by block and school year for seamless attendance tracking. <br>
    Create multiple classes and access them anytime with just a few clicks.
</p>
            <div class="text-center mt-4">
                <button type="button" class="btn create-btn rounded-pill" data-bs-toggle="modal" data-bs-target="#createClassModal">
                    <i class="bi bi-plus-circle me-2"></i> CREATE CLASS
                </button>
            </div>
        </div>
    </div>

    <div class="container">
        <?php
        $sql = "SELECT `block_id`, `block_name`, `user_id`, `school_yr` FROM `block` WHERE `user_id` = '{$_SESSION['user_id']}' ORDER BY `school_yr` DESC";
        $result = $conn->query($sql);
        $currentYear = null;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $blockYear = $row['school_yr'];
                if ($blockYear != $currentYear) {
                    if ($currentYear !== null) {
                        echo '</div>';
                    }
                    echo '<div class="row">';
                    echo '<div class="col-12"><h3 class="text-center year-heading">School Year ' . $blockYear . '</h3></div>';
                    $currentYear = $blockYear;
                }
                
                echo '<div class="col-md-4 mb-4">
                    <div class="class-card">
                        <div class="class-card-header">
                            <i class="bi bi-journal-text me-2"></i>' . $row['block_name'] . '
                        </div>
                        <div class="class-card-body text-center">
                            <form method="get" action="process_class.php">
                                <input type="hidden" name="block_id" value="' . $row['block_id'] . '">
                                <input type="hidden" name="user_id" value="' . $row['user_id'] . '">
                                <button type="submit" class="btn class-btn rounded-pill">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Enter Class
                                </button>  
                            </form>
                        </div>
                    </div>
                </div>';
            }
            echo '</div>';
        } else {
            echo '
            <div class="empty-state">
                <i class="bi bi-mortarboard"></i>
                <h3>No Classes Created Yet</h3>
                <p class="text-muted">Get started by creating your first class using the button above.</p>
            </div>';
        }
        ?>
    </div>

    <div class="modal fade" id="createClassModal" tabindex="-1" aria-labelledby="createClassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createClassModalLabel"><i class="bi bi-plus-square me-2"></i>Create New Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="create_class.php">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="mb-4">
                            <label for="blockName" class="form-label fw-bold"><i class="bi bi-tag me-2"></i>Block Name</label>
                            <input type="text" class="form-control" id="blockName" name="block_name" placeholder="Enter block name" required>
                        </div>
                        <div class="mb-4">
                            <label for="schoolYear" class="form-label fw-bold"><i class="bi bi-calendar-range me-2"></i>School Year</label>
                            <input type="text" class="form-control" id="schoolYear" name="school_year" pattern="\d{4} - \d{4}" placeholder="yyyy - yyyy" required>
                        </div>
                        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                        <div class="text-center">
                            <button type="submit" class="btn create-btn rounded-pill w-100">
                                <i class="bi bi-check-circle me-2"></i>Create Class
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require('include/footer.php'); ?>
    <?php include('include/modal.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>