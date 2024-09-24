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

$_SESSION;
$page_title = "My Profile";
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
                                <?php echo $user_data['firstname'] . ' ' .  $user_data['lastname']; ?>
                            </h5>
                            <p class="mb-0 font-weight-bold text-sm">
                                <?php echo $user_data['email']; ?>
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



        <div class="container-fluid py-4">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-6">
                    <div class="card h-100 shadow-xl">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <div class="col-md-8 d-flex align-items-center">
                                    <h6 class="mb-0">Profile Information</h6>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="javascript:; " data-bs-toggle="modal" data-bs-target="#modalEdit<?= $user_data['cust_id'] ?>">
                                        <i class="fas fa-user-edit text-secondary text-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Profile"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <p class="text-sm">
                                Hey! You're in your own space now. This is where you can find all your info. Feel free to tweak, update, or add anything whenever you want. It's your canvas to personalize as you see fit. Enjoy exploring!
                            </p>
                            <hr class="horizontal gray-light my-4">
                            <ul class="list-group">
                                <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">First Name:</strong> &nbsp; <?php echo $user_data['firstname'] ?></li>
                                <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Last Name:</strong> &nbsp; <?php echo $user_data['lastname']; ?></li>
                                <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Mobile:</strong> &nbsp; +60<?php echo $user_data['phoneNo']; ?></li>
                                <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Email:</strong> &nbsp; <?php echo $user_data['email']; ?></li>
                                <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Bank:</strong> &nbsp; <?php echo $user_data['bank_name']; ?></li>
                                <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Account No:</strong> &nbsp; <?php echo $user_data['acc_no']; ?></li>
                            </ul>
                        </div>
                        <div class="modal fade" id="modalEdit<?= $user_data['cust_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditLabel<?= $user_data['cust_id'] ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalEditLabel<?= $user_data['cust_id'] ?>">Update Profile Information</h1>
                                    </div>
                                    <form action="profile_crud.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="cust_id" value="<?= $user_data['cust_id'] ?>">
                                        <div class="modal-body">
                                            <div class="avatar avatar-xl mt-3">
                                                <input type="hidden" name="old_img" value="<?= $user_data['profile_img'] ?>">
                                                <img class="border-radius-lg shadow" src="./uploads/<?= $user_data['profile_img'] ?>" /><br><br>
                                            </div>

                                            <div class="mb-3 ">
                                                <label class="form-label">New Profile Picture:</label>
                                                <input type="file" class="form-control form-control-lg" name="profile_img">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">First Name:</label>
                                                    <input type="text" class="form-control form-control-lg" name="firstname" value="<?= $user_data['firstname'] ?>">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Last Name</label>
                                                    <input type="text" class="form-control form-control-lg" name="lastname" value="<?= $user_data['lastname'] ?>">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Phone No:</label>
                                                    <input type="text" class="form-control form-control-lg" name="phoneNo" value="<?= $user_data['phoneNo'] ?>">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" class="form-control form-control-lg" name="email" aria-label="Email" aria-describedby="email-addon" value="<?= $user_data['email'] ?>">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Bank:</label>
                                                    <input type="text" class="form-control form-control-lg" name="bank_name" value="<?= $user_data['bank_name'] ?>">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Account No:</label>
                                                    <input type="text" class="form-control form-control-lg" name="acc_no" value="<?= $user_data['acc_no'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-outline-dark mb-0" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-sm btn-dark mb-0" name="editprofile">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<?php include('includes/footer.php') ?>