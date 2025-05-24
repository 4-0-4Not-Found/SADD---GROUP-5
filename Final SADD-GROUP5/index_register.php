<?php
include_once("include/connect.php");

function verifyPassword($inputPassword, $hashedPassword) {
    return password_verify($inputPassword, $hashedPassword);
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $fullname = $_POST["fullname"];
    $user_email = $_POST["user_email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Check if password meets the strength requirement
    if (strlen($password) < 8) {
        $error_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> Password must be at least 8 characters long.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
    } else {
        // Verify passwords match
        if ($password !== $confirm_password) {
            $error_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> Password and confirm password do not match.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>';
        } else {
            // Check if the username or email already exists in the database
            $check_query = "SELECT * FROM users WHERE username='$username' OR email='$user_email'";
            $result = $conn->query($check_query);

            if ($result->num_rows > 0) {
                $error_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> Username or email already exists.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>';
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO users (username, fullname, email, password) VALUES ('$username', '$fullname', '$user_email', '$hashed_password')";

                if ($conn->query($sql) === TRUE) {
                    $error_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success!</strong> User registered successfully.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                      </div>';
                } else {
                    $error_message = "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iK7t9QQvR1ciRDJC2L/HzIq1qVRyHh4eZL2M/iPh47Ha6Q5iS9x2lVO" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/user.css">

    <title>BU-CAS Registration Form</title>
    <style>
        .registration-form {
            max-width: 400px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.5); 
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .registration-form h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333; 
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            color: #333;
        }

        .form-control {
            border-radius: 20px;
        }

        .btn-rounded {
            border-radius: 20px;
        }

        /* Rotating logo animation */
        @keyframes rotate {
            0% {
                transform: rotateY(0deg);
            }
            100% {
                transform: rotateY(360deg);
            }
        }

        .logo {
            animation: rotate 5s linear infinite;
            width: 50px; 
            height: 50px; 
        }
    </style>
</head>

<body>

    <div class="container mb-5">
        <!-- Display error message -->
        <?php echo $error_message; ?>

        <!-- Rotating logo -->
        <div class="registration-form mb-5">
            <img src="img/bup_logo.png" alt="Logo" class="logo mx-auto d-block mb-3">

            <h2 class="text-dark h-font">BU-CAS Registration Form</h2>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username">
                </div>
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter your full name">
                </div>
                <div class="form-group">
                    <label for="user_email">Email address</label>
                    <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
                    <small id="passwordHelpBlock" class="form-text text-muted">
                        Password must be at least 8 characters long.
                    </small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Enter your password again">
                </div>
                <button type="submit" class="btn btn-info btn-block text-dark btn-rounded">Register</button>
            </form>
            <a href="index.php" class="btn btn-warning btn-block text-dark btn-rounded mt-3">Login</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <?php include('include/modal.php'); ?>

</body>
</html>
