<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAS - ABOUT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iK7t9QQvR1ciRDJC2L/HzIq1qVRyHh4eZL2M/iPh47Ha6Q5iS9x2lVO" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/user.css">    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <style>
        .box{
            border-top-color: var(--teal) !important;
        }
    </style>
</head>
<body style="background: linear-gradient(to right, #ADD8E6, #FFD700);">

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">ABOUT US</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. 
            Commodi facilis porro necessitatibus eveniet quos <br> vel quam laudantium similique mollitia blanditiis possimus.
        </p>
    </div>

    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
                <h3 class="mb-3">Lorem ipsum dolor sit.</h3>
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Beatae doloribus error omnis nobis molestias sapiente voluptatum?
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Beatae doloribus error omnis nobis molestias sapiente voluptatum?
                </p>
            </div>

            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1 rounded">
                <img src="../img/group.jpg" class="w-100 rounded">
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="../img/student.png" width="70px">
                    <h4 class="mt-3">31 STUDENTS</h4>
                </div>
            </div>
        </div>
    </div>

    <h3 class="my-5 fw-bold h-font text-center">MANAGEMENT TEAM</h3>

    <div class="container px-4">
            <!-- Swiper -->
        <div class="swiper mySwiper">
            <div class="swiper-wrapper mb-5">
                            <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                                <img src="../img/profile.jpg" class="w-100">
                                <h5 class="mt-2">Walton Loneza</h5>
                            </div>
                            <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                                <img src="../img/ly.jpg" class="w-100">
                                <h5 class="mt-2">Alysa Madara</h5>
                            </div>
                            <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                                <img src="../img/shie.jpg" class="w-100">
                                <h5 class="mt-2">Shieralyn Bordeos</h5>
                            </div>
                            <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                                <img src="../img/essy.jpg" class="w-100">
                                <h5 class="mt-2">Francyn Essy Saculo</h5>
                            </div>
                            <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                                <img src="../img/rhea.jpg" class="w-100">
                                <h5 class="mt-2">Rhea May Nasayao</h5>
                            </div>
                            <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                                <img src="../img/shaine.jpg" class="w-100">
                                <h5 class="mt-2">Shaine San Juan</h5>
                            </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>


    <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
    var swiper = new Swiper(".mySwiper", {
        spaceBetween: 4,
        pagination: {
        el: ".swiper-pagination",
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
            },
            640: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        } 
    });
    </script>

<footer class="text-center bg-info text-white py-3 ">
        &copy; 2024 Class Attendance System. All rights reserved.
    </footer>
</body>
</html>