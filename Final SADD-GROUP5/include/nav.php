<?php
require_once("icons.php");
include_once("include/connect.php");
include_once("include/functions.php");

// Get violation count for the badge
$new_violators_count = countNewViolators($conn);
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary shadow custom-navbar">
    <div class="container-fluid d-flex flex-column">
        
        <!-- Top Row -->
        <div class="w-100 d-flex justify-content-between align-items-center flex-wrap mb-2 px-3">
            
            <!-- Logo and Title -->
            <a class="navbar-brand d-flex align-items-center gap-3" href="#">
                <img src="img/bupc_logo2.png" alt="Logo" width="100" height="100" class="d-inline-block align-text-top">
                <div>
                    <h4 class="h-font m-0">BICOL UNIVERSITY</h4>
                    <h5 class="h-font m-0">POLANGUI CAMPUS</h5>
                    <h6 class="h-font m-0">Polangui, Albay</h6>
                </div>
            </a>

            <!-- Nav links -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0 nav-link-group">
                    <li class="nav-item"><a class="nav-link text-dark js-navbar-close" href="homepage.php">HOME</a></li>
                    <li class="nav-item"><a class="nav-link text-dark js-navbar-close" href="archive.php">ARCHIVE</a></li>
                    <li class="nav-item"><a class="nav-link text-dark js-navbar-close" href="class.php">CLASS</a></li>
                    <li class="nav-item nav-item-absentees position-relative">
                        <a class="nav-link text-dark js-navbar-close position-relative" href="absentees.php">
                            <?php if ($new_violators_count > 0): ?>
                            <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">
                                <?= $new_violators_count ?>
                                <span class="visually-hidden">unread notifications</span>
                            </span>
                            <?php endif; ?>
                            ABSENTEES
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link text-dark js-navbar-close" href="contact.php">CONTACT US</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">LOGOUT</a></li>
                </ul>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="w-100 d-flex justify-content-center search-bar-wrapper px-3 mb-3">
            <form class="d-flex w-50" role="search" action="search_results.php" method="get">
                <div class="input-group w-100 search-bar">
                    <input name="searchkey" class="form-control border-info rounded-pill" type="search" placeholder="Search..." aria-label="Search">
                    <button class="btn btn-outline-info rounded-pill shadow-none" type="submit"><?php echo ICONSEARCH; ?></button>
                </div>
            </form>
        </div>
    </div>
</nav>

<style>
    /* Add this to your existing CSS */
    .nav-item-absentees .badge {
        font-size: 0.65rem;
        padding: 0.25em 0.45em;
        animation: pulse 2s infinite;
        transform: translate(-50%, -50%) !important;
    }
    
    .nav-item-absentees .nav-link {
        padding-left: 1.8rem; /* Make space for the badge */
    }
    
    @keyframes pulse {
        0% { transform: translate(-50%, -50%) scale(1); }
        50% { transform: translate(-50%, -50%) scale(1.1); }
        100% { transform: translate(-50%, -50%) scale(1); }
    }
</style>