


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CAS | STUDENT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/user.css">    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        <link rel="stylesheet" href="../css/user.css">
  </head>
  <body style="background-image: url('stud_img/bupc.jpg'); background-size: 100% 100%;; background-position: center; background-repeat: no-repeat; background-attachment: fixed; ">
    <nav class="navbar ">
        
                <div class="col-md-1 col-sm-1 col-lg-1 col-2 px-2">
                <a class="navbar-brand" href="#">
                <img src="../img/Bicol_University.png" alt="..."  width="100" height="100" class="img-fluid">
                </a>
                </div>

                <div class="col-md-1 col-sm-1 col-lg-1 col-2 px-2">
                    <img src="stud_img/Logo_2.png" alt="..."  width="100" height="100" class="img-fluid">
                </div>

                
                <div class="col-md-8 col-sm-8 col-lg-8 col-8 ps-2">
                 <h3>BICOL UNIVERSITY</h4>
                 <h4>POLANGUI CAMPUS</h5>
                 <h6>Polangui, Albay</h6>
                </div>    
                
                <div class="col-md-1 col-sm-1 col-lg-1 col-auto  ps-2">
                            <a class="nav-link" href="about.php">About</a>
                </div>

                <div class="col-md-1 col-sm-1 col-lg-1 col-auto  ps-2">
                <a class="nav-link" href="contact.php">Contact</a>
                </div>
    </nav>

    <?php
include_once("../include/connect.php");

// Assuming you have already established a database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the BU number from the form
    $buNumber = $_POST["bu_number"];

    // Query the database to check if the BU number exists in the 'archive' table
    $query = "SELECT `id`, `user_id`, `student_name`, `status`, `date`, `bu_no` FROM `archive` WHERE `bu_no` = '$buNumber'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Check if a row was found
        if (mysqli_num_rows($result) > 0) {
            // Start a session
            session_start();

            // Store the BU number in a session variable
            $_SESSION["bu_no"] = $buNumber;

            // Redirect to profile.php if BU number is found
            header("Location: profile.php");
            exit();
        } else {
            
        }
    } else {
        // Handle database query error
        echo "Error: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>


    <h1 class="text-center my-5">BSIS - 2A CLASS ATTENDANCE SYSTEM</h1>
    <div class="container text-center my-5">
        <div class="row align-items-center">

            <div class="col-md-8 align-self-center">
<!-- Swiper -->
<div class="swiper swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
        <img src="stud_img/group.jpg" class="w-100 d-block rounded"/>
      </div>
      <div class="swiper-slide">
        <img src="stud_img/car1.jpg" class="w-100 d-block rounded"/>
      </div>
      <div class="swiper-slide">
        <img src="stud_img/car2.jpg" class="w-100 d-block rounded"/>
      </div>
      <div class="swiper-slide">
        <img src="stud_img/car3.jpg" class="w-100 d-block rounded"/>
      </div>
      <div class="swiper-slide">
        <img src="stud_img/car4.jpg" class="w-100 d-block rounded"/>
      </div>
    </div>
    
</div>     

        </div>
            <div class="col-md-4 " >
            <!-- Display the alert message above the form -->
            <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && mysqli_num_rows($result) == 0) {
                        echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                            You are not <strong>BSIS - 2A!</strong> 
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                          </div>";
                    }
                ?>
            <form class="py-3 h-font text-dark rounded h-font" method="post" style="background-color: rgba(255, 255, 255, 0.5);">
                <div>
                    <h3>BSIS - 2A</h3>
                </div>
                <div class="mb-3 h-font form-floating py-2 px-2">
                    <input type="text" class="form-control  " id="bu_number" name="bu_number" placeholder="BU Number" required>
                    <label for="bu_number">BU number</label>
                    <div id="emailHelp" class="form-text text-dark">We'll never share your BU number with anyone else.</div>
                </div>
                <button type="submit" class="btn btn-info rounded-pill">Login</button>
            </form>
       
            </div>

        </div>
    </div>

    <footer class="text-center bg-info text-white py-3 ">
        &copy; 2024 Class Attendance System. All rights reserved.
    </footer>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper(".swiper-container", {
      spaceBetween: 30,
      effect: "fade",
      loop: true,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      }
    });
  </script>
  </body>
</html>