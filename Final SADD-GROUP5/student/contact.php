<?php
include_once("../include/connect.php");


// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);

    // Insert form data into database
    $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "Message sent successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAS - CONTACT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iK7t9QQvR1ciRDJC2L/HzIq1qVRyHh4eZL2M/iPh47Ha6Q5iS9x2lVO" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/user.css">    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css">
        

    </head>
<body style="background-color: rgb(255, 187, 60);">

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">CONTACT US</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. 
            Commodi facilis porro necessitatibus eveniet quos <br> vel quam laudantium similique mollitia blanditiis possimus.
        </p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4 ">

                <div class="bg-white rounded shadow p-4">
                    <iframe class="w-100 rounded shadow p-4" height="320px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3882.8679520528367!2d123.48211167321634!3d13.29618810768263!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a1a0f8e33fed49%3A0x50cbb3fd2083cc1!2sBicol%20University%20Polangui!5e0!3m2!1sen!2sph!4v1708224534969!5m2!1sen!2sph" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                    <h5>Address</h5>
                    <a href="https://maps.app.goo.gl/WdPNs3ejDgaSsVjT7" target="_blank" class="d-inline-block text-decoration-none text-dark mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
  <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
</svg>7FWM+FVF, Purok Earth, Centro Occidental, Buhi - Polangui Rd, Polangui, 4506 Albay</i> 
                    </a>
                    
                    <h5 class="mt-4">Call Us</h5>                    
                                <a href="tel: 
                                " class="d-inline-block text-decoration-none text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
</svg>Telefax: (052) 486-1220 (Bayantel)</i> 
                                </a> 
                            
                    
                    
                    <h5 class="mt-4">Email</h5>
                    <a href="mailto: bupc-dean@bicol-u.edu.ph" class="d-inline-block text-decoration-none text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
  <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
</svg> bupc-dean@bicol-u.edu.ph </a>

                    <h5 class="mt-4">Follow Us</h5>
                   
                    <a href="https://www.facebook.com/pages/Bicol-University-Polangui-Campus/169728836440245" class="d-inline-block text-dark text-decoration-none">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16" style="margin-end: 5px;">
        <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
    </svg>
    Bicol University Polangui Campus
</a>

                   
                    <br>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 px-4">
                <div class="bg-white rounded shadow p-4">
                        <form action="" method="post">
                            <h5>Send a message</h5>
                            <div class="mt-3">
                                <label class="form-label" style="font-weight:500;">Name</label>
                                <input type="text" class="form-control shadow-none">
                            </div>
                            <div class="mt-3">
                                <label class="form-label" style="font-weight:500;">Email</label>
                                <input type="email" class="form-control shadow-none">
                            </div>
                            <div class="mt-3">
                                <label class="form-label" style="font-weight:500;">Subject</label>
                                <input type="text" class="form-control shadow-none">
                            </div>
                            <div class="mt-3">
                                <label class="form-label" style="font-weight:500;">Message</label>
                                <textarea class="form-control shadow-none" rows="5" style="resize: none;"></textarea>
                            </div>
                            <button type="submit" class="btn btn-white custom-bg mt-3 shadow-none">SEND</button>
                        </form>
                </div>
                
            </div>
        </div>
    </div>

    <footer class="text-center bg-info text-white py-3 ">
        &copy; 2024 Class Attendance System. All rights reserved.
    </footer>
    
</body>
</html>