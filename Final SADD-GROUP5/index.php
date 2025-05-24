<?php
session_start();

include_once("include/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['uname']) && isset($_POST['pword'])){
        $user_name = $_POST['uname'];
        $pass_word = $_POST['pword'];

        $stmt = $conn->prepare("SELECT * FROM `users` WHERE `username`=?");
        $stmt->bind_param("s", $user_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password']; 

            // Verify the password
            if (password_verify($pass_word, $hashed_password)) {
                $_SESSION["username"] = $user_name;
                $_SESSION['user_id'] = $row['user_id']; 
                header("location: homepage.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Username and password are required.";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
            background-color: rgba(255, 255, 255, 0.8); 
            padding: 20px;
            border-radius: 20px;
        }
        .logo {
            width: 100px; 
            height: auto;
            animation: rotateHorizontal 6s linear infinite; 
        }
        @keyframes rotateHorizontal {
            0% {
                transform: rotateY(0deg);
            }
            100% {
                transform: rotateY(360deg);
            }
        }
        .form-control {
            border-radius: 20px;
            padding-right: 40px; 
        }
        .input-group-text {
            border: none; 
            background-color: transparent; 
            position: absolute;
            right: 10px; 
            top: 50%; 
            transform: translateY(-50%);
            cursor: pointer;
            box-shadow: none; 
        }
        .input-group-text i {
            font-size: 1.2rem; 
            outline: none; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="text-center mb-4">
                    <img src="img/bup_logo.png" alt="Logo" class="logo"> 
                </div>
                <h2 class="text-center mb-4">Welcome to Class Attendance System</h2>

                <!-- Display error message if exists -->
                <?php if(isset($error)) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php } ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="uname" placeholder="Username">
                    </div>
                    <div class="mb-3 position-relative">
                        <input type="password" class="form-control" name="pword" placeholder="Password" id="passwordInput">
                        <span class="input-group-text" id="togglePassword">
                            <i class="bi bi-eye"></i> 
                        </span>
                    </div>
                    <div class="mb-4 text-center">
                        <div class="d-inline-block"> 
                            <input type="submit" value="Log In" class="btn btn-info rounded-pill">
                            <a href="index_register.php" class="btn btn-link text-warning text-decoration-none">Sign Up</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // JavaScript to toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#passwordInput');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>

