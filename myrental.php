<?php
// Function to get the CSS class for rental status badge
function getRentalStatusClass($rental_status)
{
    switch ($rental_status) {
        case 0:
            return 'badge badge-sm border border-warning text-warning'; // CSS class for Pending status
        case 1:
            return 'badge badge-sm border border-success text-success'; // CSS class for Completed status
        default:
            return 'badge badge-sm border border-danger text-danger'; // CSS class for Canceled or other status
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
$page_title = "My Rentals";
include('includes/header.php');
?>

<style>
    /* Custom CSS for fixed height cards */
    .rental-card {
        height: 250px;
        /* Adjust height as needed */
        overflow: hidden;
    }
</style>

<section>
    <div class="container">
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
                            <h5 class="mb-1">
                                <?= $user_data['firstname'] . ' ' .  $user_data['lastname']; ?>
                            </h5>
                            <p class="mb-0 font-weight-bold text-sm">
                                <?= $user_data['email']; ?>
                            </p>
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
    </div>

    <div class="container mt-5 px-6">
        <div class="container-fluid">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs justify-content-center mb-4" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pending-tab" data-bs-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="true">Pending Rentals</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="completed-tab" data-bs-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="false">Completed Rentals</a>
                </li>
            </ul>

            <!-- Success and error messages -->
            <?php if (isset($_SESSION['success_message'])) : ?>
                <div class="alert alert-success" role="alert">
                    <?= $_SESSION['success_message'];
                    unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_SESSION['error_message'];
                    unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <!-- Tab panes -->
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                    <div class="row justify-content-center">
                        <?php
                        $cust_id = $user_data['cust_id'];
                        $query = "SELECT b.*, c.*, 
                                    CASE 
                                        WHEN b.rental_status = 0 THEN 'Pending'
                                        WHEN b.rental_status = 1 THEN 'Completed'
                                        ELSE 'Canceled'
                                    END AS rental_status_text,
                                    CASE 
                                        WHEN b.rental_type = 'dais' THEN d.name
                                        WHEN b.rental_type = 'attire' THEN a.name
                                    END AS item_name,
                                    CASE 
                                        WHEN b.rental_type = 'dais' THEN d.image
                                        WHEN b.rental_type = 'attire' THEN a.image
                                    END AS item_img,
                                    CASE 
                                        WHEN b.rental_type = 'dais' THEN 'Bridal Dais'
                                        WHEN b.rental_type = 'attire' THEN 'Bridal Attire'
                                    END AS rental_type
                                FROM rentals b
                                JOIN customers c ON b.cust_id = c.cust_id
                                LEFT JOIN bridal_dais d ON b.rental_type = 'dais' AND b.item_id = d.dais_id
                                LEFT JOIN bridal_attire a ON b.rental_type = 'attire' AND b.item_id = a.attire_id
                                WHERE b.cust_id = '$cust_id' AND b.rental_status = 0
                                ORDER BY b.created_at DESC";

                        $query_run = mysqli_query($con, $query);

                        if (mysqli_num_rows($query_run) > 0) {
                            while ($data = mysqli_fetch_assoc($query_run)) {
                                displayRentalCard($data, $con);
                            }
                        } else {
                            echo '<div class="col-12 text-center">
                                      <p class="lead">No pending rentals found.</p>
                                  </div>';
                        }
                        ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                    <div class="row justify-content-center">
                        <?php
                        $query = "SELECT b.*, c.*, 
                                    CASE 
                                        WHEN b.rental_status = 0 THEN 'Pending'
                                        WHEN b.rental_status = 1 THEN 'Completed'
                                        ELSE 'Canceled'
                                    END AS rental_status_text,
                                    CASE 
                                        WHEN b.rental_type = 'dais' THEN d.name
                                        WHEN b.rental_type = 'attire' THEN a.name
                                    END AS item_name,
                                    CASE 
                                        WHEN b.rental_type = 'dais' THEN d.image
                                        WHEN b.rental_type = 'attire' THEN a.image
                                    END AS item_img,
                                    CASE 
                                        WHEN b.rental_type = 'dais' THEN 'Bridal Dais'
                                        WHEN b.rental_type = 'attire' THEN 'Bridal Attire'
                                    END AS rental_type
                                FROM rentals b
                                JOIN customers c ON b.cust_id = c.cust_id
                                LEFT JOIN bridal_dais d ON b.rental_type = 'dais' AND b.item_id = d.dais_id
                                LEFT JOIN bridal_attire a ON b.rental_type = 'attire' AND b.item_id = a.attire_id
                                WHERE b.cust_id = '$cust_id' AND b.rental_status = 1 
                                ORDER BY b.created_at DESC";

                        $query_run = mysqli_query($con, $query);

                        if (mysqli_num_rows($query_run) > 0) {
                            while ($data = mysqli_fetch_assoc($query_run)) {
                                displayRentalCard($data, $con);
                            }
                        } else {
                            echo '<div class="col-12 text-center">
                                      <p class="lead">No completed rentals found.</p>
                                  </div>';
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
                    <input type="hidden" name="rental_id" id="rental_id">
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
                    <button type="submit" class="btn btn-sm btn-warning" name="submit_feedback_rental">Submit Feedback</button>
                </div>
            </form>
        </div>
    </div>
</div>



<?php include('includes/footer.php') ?>

<?php
function displayRentalCard($data, $con)
{
    // Check if feedback is already submitted for this booking
    $rental_id = $data['rental_id'];
    $feedback_query = "SELECT * FROM feedback WHERE rental_id = '$rental_id'";
    $feedback_result = mysqli_query($con, $feedback_query);
    $feedback_submitted = mysqli_num_rows($feedback_result) > 0;
?>
    <div class="col-lg-8 col-md-10 mb-4">
        <div class="card shadow-xl rental-card">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="admin/uploads/<?= $data['item_img'] ?>" class="card-img-top" alt="<?= $data['item_name'] ?>">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><?= $data['item_name'] ?></h5>
                        <p class="card-text">Rental Type: <strong><?= $data['rental_type'] ?></strong></p>
                        <span class="badge position-absolute top-0 end-0 m-2 <?= getRentalStatusClass($data['rental_status']) ?>">
                            <?= $data['rental_status_text'] ?>
                        </span>
                        <p class="card-text">Total Payment:<strong> RM<?= $data['total_payment'] ?></strong></p>
                        <p class="card-text text-sm">Rental date:<?= date('j F Y', strtotime($data['created_at'])) ?></p>
                        <a href="rental_confirmation.php?rental_id=<?= $data['rental_id'] ?>" class="btn btn-sm mb-0 btn-warning">View Details</a>
                        <?php if ($data['rental_status'] == 1 && !$feedback_submitted) : ?>
                            <button class="btn btn-sm btn-outline-dark text-end mb-0" data-bs-toggle="modal" data-bs-target="#feedbackModal" onclick="setRentalId(<?= $data['rental_id'] ?>)">Give Feedback</button>
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
    function setRentalId(rentalId) {
        document.getElementById('rental_id').value = rentalId;
    }
</script>