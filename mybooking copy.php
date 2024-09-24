<?php
// Function to get the CSS class for booking status badge
function getBookingStatusClass($booking_status)
{
    switch ($booking_status) {
        case 0:
            return 'badge badge-sm border border-warning text-warning'; // CSS class for Pending status
        case 1:
            return 'badge badge-sm border border-success text-success'; // CSS class for Completed status
        default:
            return 'badge badge-sm border border-danger text-danger'; // CSS class for Canceled or other status
    }
}

// Function to get the booking status text
function getBookingStatusText($booking_status)
{
    switch ($booking_status) {
        case 0:
            return 'Pending';
        case 1:
            return 'Completed';
        default:
            return 'Canceled';
    }
}

// Start of the page
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

$_SESSION;
$page_title = "My Bookings";
include('includes/header.php');
?>

<style>
    /* Custom CSS for fixed height cards */
    .booking-card {
        height: 250px;
        /* Adjust height as needed */
        overflow: hidden;
    }
</style>

<section>
    <div class="container mt-5 px-6">
        <div class="container-fluid">
            <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('assets/img/curved-images/curved1.jpg'); background-position-y: 50%;">
                <span class="mask bg-warning opacity-5"></span>
            </div>
            <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative">
                            <img src="./uploads/<?= $user_data['profile_img'] ?>" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                        </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1"><?= $user_data['firstname'] . ' ' .  $user_data['lastname']; ?></h5>
                            <p class="mb-0 font-weight-bold text-sm"><?= $user_data['email']; ?></p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                        <div class="nav-wrapper position-relative end-0">
                            <h2 class="text-center font-weight-bold text-xl ms-1 text-gradient text-warning" style="font-family:'Poppins', sans-serif;"><?= $page_title ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-5">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs justify-content-center mb-4" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pending-tab" data-bs-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="true">Pending Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="completed-tab" data-bs-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="false">Completed Bookings</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                    <div class="row justify-content-center">
                        <?php
                        $cust_id = $user_data['cust_id'];
                        $query_pending = "SELECT a.*, b.firstname, b.lastname, b.phoneNo, b.email, c.pkg_img, c.pkg_name, c.pkg_price, c.total_pax, c.deposit, d.name as attire, e.name as dais, f.slot, g.menu_name, h.section
                                        FROM package_bookings a
                                        LEFT JOIN customers b ON a.cust_id = b.cust_id
                                        LEFT JOIN packages c ON a.pkg_id = c.pkg_id
                                        LEFT JOIN bridal_attire d ON a.attire_id = d.attire_id
                                        LEFT JOIN bridal_dais e ON a.dais_id = e.dais_id
                                        LEFT JOIN event_slot f ON a.slot_id = f.slot_id
                                        LEFT JOIN package_menu h ON c.pkg_id = h.pkg_id 
                                        LEFT JOIN menus g ON g.menu_id = h.menu_id
                                        WHERE a.cust_id = '$cust_id' AND a.booking_status = 0";

                        $query_run_pending = mysqli_query($con, $query_pending);

                        if ($query_run_pending === false) {
                            echo '<div class="col-12 text-center">
                                      <p class="lead">Error in query: ' . mysqli_error($con) . '</p>';
                        } elseif (mysqli_num_rows($query_run_pending) > 0) {
                            while ($data = mysqli_fetch_assoc($query_run_pending)) {
                                displayCardBooking($data);
                            }
                        } else {
                            echo '<div class="col-12 text-center">
                                      <p class="lead">No pending bookings found.</p>';
                        }
                        ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                    <div class="row justify-content-center">
                        <?php
                        $query_completed = "SELECT a.*, b.firstname, b.lastname, b.phoneNo, b.email, c.pkg_img, c.pkg_name, c.pkg_price, c.total_pax, c.deposit, d.name as attire, e.name as dais, f.slot, g.menu_name, h.section
                                            FROM package_bookings a
                                            LEFT JOIN customers b ON a.cust_id = b.cust_id
                                            LEFT JOIN packages c ON a.pkg_id = c.pkg_id
                                            LEFT JOIN bridal_attire d ON a.attire_id = d.attire_id
                                            LEFT JOIN bridal_dais e ON a.dais_id = e.dais_id
                                            LEFT JOIN event_slot f ON a.slot_id = f.slot_id
                                            LEFT JOIN package_menu h ON c.pkg_id = h.pkg_id 
                                            LEFT JOIN menus g ON g.menu_id = h.menu_id
                                            WHERE a.cust_id = '$cust_id' AND a.booking_status = 1";

                        $query_run_completed = mysqli_query($con, $query_completed);

                        if ($query_run_completed === false) {
                            echo '<div class="col-12 text-center">
                                      <p class="lead">Error in query: ' . mysqli_error($con) . '</p>';
                        } elseif (mysqli_num_rows($query_run_completed) > 0) {
                            while ($data = mysqli_fetch_assoc($query_run_completed)) {
                                displayCardBooking($data);
                            }
                        } else {
                            echo '<div class="col-12 text-center">
                                      <p class="lead">No completed bookings found.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="feedbackForm" method="POST" action="submit_feedback.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">Submit Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="booking_id" id="booking_id">
                    <div class="mb-3">
                        <label for="feedback" class="form-label">Feedback</label>
                        <textarea class="form-control" id="feedback" name="feedback" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating</label>
                        <select class="form-select" id="rating" name="rating" required>
                            <option value="" selected disabled>Select rating</option>
                            <option value="1">1 - Very Poor</option>
                            <option value="2">2 - Poor</option>
                            <option value="3">3 - Average</option>
                            <option value="4">4 - Good</option>
                            <option value="5">5 - Excellent</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-warning" name="submit_feedback_booking">Submit Feedback</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php') ?>

<?php
function displayCardBooking($data)
{
    global $con;
    // Check if feedback is already submitted for this booking
    $booking_id = $data['booking_id'];
    $feedback_query = "SELECT * FROM feedback WHERE booking_id = '$booking_id'";
    $feedback_result = mysqli_query($con, $feedback_query);
    $feedback_submitted = mysqli_num_rows($feedback_result) > 0;
?>
    <div class="col-lg-8 col-md-10 mb-4">
        <div class="card shadow-xl booking-card">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="admin/uploads/<?= $data['pkg_img'] ?>" class="card-img-top" alt="<?= $data['pkg_name'] ?>">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><?= $data['pkg_name'] ?></h5>
                        <p class="card-text"><strong>Total Pax:</strong> <?= $data['total_pax'] ?></p>
                        <p class="card-text"><strong>Booking Status:</strong>
                            <span class="badge <?= getBookingStatusClass($data['booking_status']) ?>">
                                <?= getBookingStatusText($data['booking_status']) ?>
                            </span>
                        </p>
                        <p class="card-text"><strong>Total Payment:</strong> RM<?= $data['total_payment'] ?></p>
                        <a href="booking_summary.php?booking_id=<?= $data['booking_id'] ?>" class="btn btn-sm mb-0 btn-warning">View Details</a>
                        <?php if ($data['booking_status'] == 1 && !$feedback_submitted) : ?>
                            <button class="btn btn-sm btn-outline-dark text-end mb-0" data-bs-toggle="modal" data-bs-target="#feedbackModal" onclick="setBookingId(<?= $data['booking_id'] ?>)">Give Feedback</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<script>
function setBookingId(bookingId) {
    document.getElementById('booking_id').value = bookingId;
}
</script>
