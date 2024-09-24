<?php
session_start();
include('dbcon.php');
include('functions.php');

// Check if the user is logged in
if (!isset($_SESSION['cust_id'])) {
    // Capture the current URL and redirect to login with it as a query parameter
    $redirect_url = $_SERVER['REQUEST_URI'];
    header("Location: login.php?redirect=" . urlencode($redirect_url));
    die;
}

$user_data = check_login($con);

$page_title = "Book Appointment";
include('includes/header.php');

?>

<header>
    <div class="page-header min-vh-85">
        <div>
            <img class="position-absolute fixed-top ms-auto w-50 h-100 z-index-0 d-none d-sm-none d-md-block border-radius-section border-top-end-radius-0 border-top-start-radius-0 border-bottom-end-radius-0" src="assets/img/IMG_1011.jpg" alt="image">
        </div>
        <div class="container my-3 py-3">
            <div class="row">
                <div class="col-lg-7 d-flex justify-content-center flex-column">
                    <div class="card d-flex blur justify-content-center p-4 shadow-lg my-sm-0 my-sm-6 mt-8 mb-5">
                        <div class="text-center">
                            <h3 class="text-warning">Book Your Appointment Today!</h3>
                            <p class="mb-0">
                                We are excited to help you with your wedding planning.
                                To book your appointment, please fill out our appointment form or contact us directly.
                            </p>
                        </div>
                        <form method="POST" action="add-app.php">
                            <div class="card-body pb-2">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <span class="form-label">Name</span>
                                            <input class="form-control" name="name" type="text" placeholder="Enter your name" value="<?= $user_data['firstname'] ?> <?= $user_data['lastname'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <span class="form-label">Phone</span>
                                            <input class="form-control" name="phone" type="text" value="+60<?= $user_data['phoneNo'] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span class="form-label">Email</span>
                                    <input class="form-control" name="email" type="email" value="<?= $user_data['email'] ?>">
                                </div>
                                <div class="form-group">
                                    <span class="form-label">Location</span>
                                    <input class="form-control" name="location" type="text" placeholder="Eg: Butik Waniey Bridal">
                                </div>
                                <div class="form-group">
                                    <span class="form-label">Purpose</span>
                                    <input class="form-control" name="purpose" type="text" placeholder="Enter purpose">
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <span class="form-label">Date</span>
                                            <input class="form-control" name="date" type="date"  id="dateInput" value="<?= isset($_POST['date']) ? htmlspecialchars($_POST['date']) : '' ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6"> <!-- Use col-sm-6 to make them side by side on larger screens -->
                                        <div class="form-group">
                                            <span class="form-label">Time</span>
                                            <input class="form-control time" name="time" type="time" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-btn">
                                    <button type="submit" class="btn bg-warning mt-3 mb-0 text-white" name="bookapp">Book Appointment</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<?php include('includes/footer.php') ?>

<script>
    // Set the minimum date to today
    document.addEventListener("DOMContentLoaded", function() {
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('dateInput').setAttribute('min', today);
    });
</script>