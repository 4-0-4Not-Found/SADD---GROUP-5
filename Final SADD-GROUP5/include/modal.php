<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header shadow" style="color: rgba(255, 255, 255, 0.5);">
                <img src="img/bupc_logo.png" alt="Logo" width="50" height="50" class="d-inline-block me-3">
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
