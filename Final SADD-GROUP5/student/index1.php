<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Attendance System | student</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iK7t9QQvR1ciRDJC2L/HzIq1qVRyHh4eZL2M/iPh47Ha6Q5iS9x2lVO" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/user.css">

</head>

<body style="background-image: url('stud_img/bupc.jpg'); background-size: 100% 100%;; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
<header class="h-font text-white py-3">
    <div class="container">
        <div class="row">
            <div class="col-auto">
                <img src="../img/Bicol_University.png" alt="Logo" width="100" height="100" class="d-inline-block align-text-top">
            </div>
            <div class="col-auto">
                <img src="stud_img/logo_2.png" alt="Logo" width="100" height="100" class="d-inline-block align-text-top">
            </div>
            <div class="col-auto me-5">
                <h4 class="d-inline-block h-font align-text-top">BICOL UNIVERSITY</h4> <br>
                <h5 class="d-inline-block h-font align-text-top">POLANGUI CAMPUS</h5> <br>
                <h6 class="d-inline-block h-font align-text-top">Polangui, Albay</h6>
            </div>
            <div class="col ms-auto py-3 justify-content-end align-items-end">
                <button class="btn btn-info rounded-pill" type="button" data-bs-toggle="modal" data-bs-target="#loginModal">
                    Login
                </button>
            </div>
        </div>
    </div>
    <h1 class="text-center">BSIS - 2A CLASS ATTENDANCE SYSTEM</h1>
</header>



    <main class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p class="text-center text-white">
                        Thank you for visiting our website. Feel free to explore the content, and let us know if you have any
                        questions.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center bg-info text-white py-3 fixed-bottom">
        &copy; 2024 Class Attendance System. All rights reserved.
    </footer>

    <!-- Add Bootstrap JS and Popper.js scripts (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    

<!-- Add this modal structure at the bottom of your HTML body -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header shadow" style="color: rgba(255, 255, 255, 0.5);">
                <img src="img/Bicol_University.png" alt="Logo" width="50" height="50" class="d-inline-block me-3">
                <h5 class="modal-title h-font text-dark" id="loginModalLabel">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 1.5rem; color: #fff;">&times;</span>
                </button>
            </div>
            <div class="modal-body d-flex" style="background: url('img/login4.jpg'); background-size: cover;">
                <!-- Your login form goes here -->
                <form action="login.php" method="post" class="row g-3">
                    <div class="mb-3 col-12 text-dark">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control text-dark" id="username" name="uname" required>
                    </div>
                    <div class="mb-3 col-12 text-dark">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="pword" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-info rounded-pill text-light me-3">Login</button>
                        <!-- Add the register button -->
                        <a href="register.php" class=" text-warning text-decoration-none">Register</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>
