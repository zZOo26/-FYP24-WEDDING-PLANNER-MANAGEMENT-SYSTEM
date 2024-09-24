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
$page_title = "My Appointments";
include('includes/header.php');

?>

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
        <div class="container-fluid text-center">
            <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true">Upcoming Appointments</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">Completed Appointments</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled" aria-selected="false">Cancelled Appointments</button>
                </li>
            </ul>

            <div class="tab-content mt-4" id="myTabContent">
                <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                    <div class="row justify-content-center">
                        <?php
                        $cust_id = $user_data['cust_id'];
                        $query_upcoming = "SELECT * FROM appointments WHERE cust_id = $cust_id AND status = 0 ORDER BY date, time";
                        $query_run_upcoming = mysqli_query($con, $query_upcoming);

                        if (mysqli_num_rows($query_run_upcoming) > 0) {
                            while ($data = mysqli_fetch_array($query_run_upcoming)) {
                        ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card shadow-xl border border-warning">
                                        <div class="card-body">
                                            <!-- Badge for status -->
                                            <span class="badge bg-warning position-absolute top-0 end-0 m-2 opacity-7">Upcoming</span>
                                            <h5 class="card-title"><?= $data['purpose'] ?></h5>
                                            <p class="card-text">
                                                <strong>Date:</strong> <?= date('d/m/Y', strtotime($data['date'])) ?><br>
                                                <strong>Time:</strong> <?= date('h:i A', strtotime($data['time'])) ?><br>
                                                <strong>Location:</strong> <?= $data['location'] ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <div class="col">
                                <p class="text-muted">No upcoming appointments found.</p>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                    <div class="row justify-content-center">
                        <?php
                        $query_completed = "SELECT * FROM appointments WHERE cust_id = $cust_id AND status = 1 ORDER BY date DESC, time DESC";
                        $query_run_completed = mysqli_query($con, $query_completed);

                        if (mysqli_num_rows($query_run_completed) > 0) {
                            while ($data = mysqli_fetch_array($query_run_completed)) {
                        ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card shadow-xl border border-sucess">
                                        <div class="card-body">
                                            <!-- Badge for status -->
                                            <span class="badge bg-success position-absolute top-0 end-0 m-2 opacity-7">Completed</span>
                                            <h5 class="card-title"><?= $data['purpose'] ?></h5>
                                            <p class="card-text">
                                                <strong>Date:</strong> <?= date('d/m/Y', strtotime($data['date'])) ?><br>
                                                <strong>Time:</strong> <?= date('h:i A', strtotime($data['time'])) ?><br>
                                                <strong>Location:</strong> <?= $data['location'] ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <div class="col">
                                <p class="text-muted">No completed appointments found.</p>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                    <div class="row justify-content-center">
                        <?php
                        $query_cancelled = "SELECT * FROM appointments WHERE cust_id = $cust_id AND status = 2 ORDER BY date DESC, time DESC";
                        $query_run_cancelled = mysqli_query($con, $query_cancelled);

                        if (mysqli_num_rows($query_run_cancelled) > 0) {
                            while ($data = mysqli_fetch_array($query_run_cancelled)) {
                        ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card shadow-xl border border-danger position-relative">
                                        <div class="card-body">
                                            <!-- Badge for status -->
                                            <span class="badge bg-danger position-absolute top-0 end-0 m-2 opacity-7">Cancelled</span>
                                            <h5 class="card-title"><?= htmlspecialchars($data['purpose']) ?></h5>
                                            <p class="card-text">
                                                <strong>Date:</strong> <?= date('d/m/Y', strtotime($data['date'])) ?><br>
                                                <strong>Time:</strong> <?= date('h:i A', strtotime($data['time'])) ?><br>
                                                <strong>Location:</strong> <?= htmlspecialchars($data['location']) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <div class="col">
                                <p class="text-muted">No cancelled appointments found.</p>
                            </div>
                        <?php
                        }
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<?php include('includes/footer.php') ?>