<?php
session_start();
include('../dbcon.php');
include('./functions.php');

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page if not logged in
    header("Location: admin_login.php");
    die;
}

$user_data = check_login($con);

$_SESSION;
$page_title = 'Promotions';
include('includes/header.php');

?>


<!-- promotion -->
<div class="container">
    <div class="row">
        <!-- category -->
        <div class="col-12 col-xl-3 mb-4">
            <div class="card border shadow-xs mb-4">
                <div class="card-header border-bottom pb-0 p-3">
                    <div class="row">
                        <div class="col-md-8 col-9">
                            <h6 class="mb-0 font-weight-semibold text-lg">Category</h6>
                            <p class="text-sm mb-1">List of categories</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="d-sm-flex align-items-center">
                        <form class="d-flex align-items-center flex-grow-1" action="promo_crud.php" method="POST">
                            <input type="text" class="form-control form-control-sm me-2" name="ctg_name" required placeholder="Category Name">

                            <button type="submit" class="btn btn-sm btn-dark mb-0" name="addctg">Save</button>
                        </form>
                    </div><br>


                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">

                            <table class="border-top table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="align-middle text-center">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No.</span>
                                        </th>
                                        <th class="align-middle justify-content-start">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</span>
                                        </th>
                                    </tr>
                                </thead>
                                <?php
                                $no = 1;
                                $query = mysqli_query($con, "SELECT * FROM promotion_category");
                                while ($data = mysqli_fetch_array($query)) :
                                ?>

                                    <tr>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= $no++ ?></span>
                                        </td>
                                        <td class="align-middle justify-content-start">
                                            <p class="text-xs font-weight-bold mb-0"><?= $data['ctg_name'] ?></p>
                                        </td>
                                    </tr>

                                <?php endwhile; ?>

                            </table>

                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!-- end category -->

        <!-- promotion table -->
        <div class="col-12 col-xl mb-4">

            <div class="card border shadow-xs mb-4">
                <div class="card-header border-bottom pb-0">
                    <div class="d-sm-flex align-items-center">
                        <div>
                            <h6 class="font-weight-semibold text-lg mb-0">Promotion</h6>
                            <p class="text-sm">See information about all promotions</p>
                        </div>
                        <div class="ms-auto d-flex justify-content-end">

                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                <span class="btn-inner--icon"><i class="fas fa-rectangle-ad d-block me-2"></i></span>
                                </span><span class="btn-inner--text">New Promotion</span>
                            </button>
                        </div>
                    </div>

                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">

                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No.</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Promotion</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Discount</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Start Date</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">End Date</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</span>
                                    </th>
                                </tr>
                            </thead>
                            <?php
                            $no = 1;
                            $query = mysqli_query($con, "SELECT * FROM promotions INNER JOIN promotion_category ON promotions.promo_ctg_id = promotion_category.promo_ctg_id ORDER BY promotions.promo_status DESC");
                            while ($data = mysqli_fetch_array($query)) :
                            ?>

                                <tr>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold"><?= $no++ ?></span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex align-items-center">
                                                <img class="avatar avatar-sm me-2" src="./uploads/<?= $data['poster'] ?>" />
                                            </div>
                                            <div class="d-flex flex-column justify-content-start ms-1">
                                                <h6 class="mb-0 text-sm font-weight-semibold"><?= $data['promo_code'] ?></h6>
                                                <p class="text-sm text-secondary mb-0"><?= $data['ctg_name'] ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">RM<?= $data['amount_off'] ?></span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-0">
                                            <?php
                                            // Convert slot_date to d/m/y format
                                            $slot_date = DateTime::createFromFormat('Y-m-d', $data['start_date']);

                                            // Check if slot_date is valid
                                            if ($slot_date !== false) {
                                                $formatted_slot_date = $slot_date->format('d/m/Y');
                                                echo $formatted_slot_date;
                                            } else {
                                                echo "Invalid date";
                                            }
                                            ?>
                                        </p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-0">
                                            <?php
                                            // Convert slot_date to d/m/y format
                                            $slot_date = DateTime::createFromFormat('Y-m-d', $data['end_date']);

                                            // Check if slot_date is valid
                                            if ($slot_date !== false) {
                                                $formatted_slot_date = $slot_date->format('d/m/Y');
                                                echo $formatted_slot_date;
                                            } else {
                                                echo "Invalid date";
                                            }
                                            ?>
                                        </p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <?php
                                        if ($data['promo_status'] == "Upcoming") {
                                        ?>
                                            <p class="badge badge-sm border border-warning text-warning bg-warning"><?= $data['promo_status'] ?></p>

                                        <?php
                                        } elseif ($data['promo_status'] == "Ongoing") {

                                        ?>
                                            <p class="badge badge-sm border border-success text-success bg-success"><?= $data['promo_status'] ?></p>
                                        <?php
                                        } else {
                                        ?>
                                            <p class="badge badge-sm border border-danger text-danger bg-danger"><?= $data['promo_status'] ?></p>
                                        <?php
                                        }
                                        ?>


                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#modalView<?= $data['promo_id'] ?>"><i class="fas fa-eye text-sm opacity-5"></i></a>
                                        <span style="margin: 0 10px;"></span>
                                        <a href="#" class="text-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['promo_id'] ?>"><i class="fas fa-edit text-sm font-weight-bolder opacity-5"></i></a>
                                        <span style="margin: 0 10px;"></span>
                                        <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $data['promo_id'] ?>"><i class="fas fa-trash text-sm font-weight-bolder opacity-5"></i></a>
                                    </td>
                                </tr>

                                <!-- Modal View -->
                                <div class="modal fade modal-lg" id="modalView<?= $data['promo_id'] ?>" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditLabel<?= $data['promo_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalEditLabel<?= $data['promo_id'] ?>">Promotion Details</h1>
                                            </div>
                                            <form action=".php" method="POST">
                                                <input type="hidden" name="promo_id" value="<?= $data['promo_id'] ?>">
                                                <div class="modal-body">
                                                    <div class="mb-3 text-center">
                                                        <img class="w-30" src="./uploads/<?= $data['poster'] ?>" />
                                                    </div>
                                                    <span>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Promotion Code</label>
                                                                <input type="text" class="form-control form-control-lg" name="promo_code" value="<?= $data['promo_code'] ?>" readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Discount (RM)</label>
                                                                <input type="number" class="form-control form-control-lg" name="amount_off" value="<?= $data['amount_off'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Category</label>
                                                                <input type="text" class="form-control form-control-lg" name="ctg_name" value="<?= $data['ctg_name'] ?>" readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Status</label>
                                                                <input type="text" class="form-control form-control-lg" name="promo_status" value="<?= $data['promo_status'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Start Date</label>
                                                                <input type="text" class="form-control form-control-lg" name="start_date" value="<?= $data['start_date'] ?>" readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">End Date</label>
                                                                <input type="text" class="form-control form-control-lg" name="end_date" value="<?= $data['end_date'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Description</label>
                                                            <textarea class="form-control form-control-lg" name="promo_desc" readonly> <?= $data['promo_desc'] ?> </textarea>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-dark mb-0" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal View -->

                                <!-- Modal Edit -->
                                <div class="modal fade modal-lg" id="modalEdit<?= $data['promo_id'] ?>" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditLabel<?= $data['promo_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalEditLabel<?= $data['promo_id'] ?>">Edit Details</h1>
                                            </div>
                                            <form action="promo_crud.php" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="promo_id" value="<?= $data['promo_id'] ?>">
                                                <div class="modal-body">
                                                    <div class="row">

                                                        <label class="form-label">New Poster</label>
                                                        <div class="col-md-1 mb-3">
                                                            <input type="hidden" name="old_img" value="<?= $data['poster'] ?>">
                                                            <img class="avatar avatar-lg" src="./uploads/<?= $data['poster'] ?>" /><br><br>
                                                        </div>
                                                        <div class="col-md-11 mb-3">
                                                            <input type="file" class="form-control form-control-lg" name="poster">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Promotion Code</label>
                                                            <input type="text" class="form-control form-control-lg" name="promo_code" value="<?= $data['promo_code'] ?>" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Discount (RM)</label>
                                                            <input type="number" class="form-control form-control-lg" name="amount_off" value="<?= $data['amount_off'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Start Date</label>
                                                            <input type="date" class="form-control form-control-lg" name="start_date" value="<?= $data['start_date'] ?>" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">End Date</label>
                                                            <input type="date" class="form-control form-control-lg" name="end_date" value="<?= $data['end_date'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Category</label>
                                                            <select class="form-select form-select-lg" name="promo_ctg_id">
                                                                <option value="" selected disabled>Select Category</option>
                                                                <?php
                                                                $categories = mysqli_query($con, "SELECT * FROM promotion_category");
                                                                if (mysqli_num_rows($categories) > 0) {
                                                                    foreach ($categories as $category) {
                                                                ?>
                                                                        <option value="<?= $category['promo_ctg_id'] ?>" <?= $data['promo_ctg_id'] == $category['promo_ctg_id'] ? 'selected' : '' ?>><?= $category['ctg_name'] ?></option>

                                                                <?php
                                                                    }
                                                                } else {
                                                                    echo "No category available";
                                                                }

                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Current Status</label>
                                                            <input type="text" class="form-control form-control-lg" name="promo_status" value="<?= $data['promo_status'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Description</label>
                                                        <textarea class="form-control form-control-lg" name="promo_desc" required><?= $data['promo_desc'] ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-dark mb-0" name="editpromo">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal Edit -->

                                <!-- Modal Delete -->
                                <div class="modal fade" id="modalDelete<?= $data['promo_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel<?= $data['promo_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalEditLabel<?= $data['promo_id'] ?>">Delete Confirmation</h1>
                                            </div>
                                            <form action="promo_crud.php" method="POST">
                                                <input type="hidden" name="promo_id" value="<?= $data['promo_id'] ?>">
                                                <div class="modal-body">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-danger font-weight-bold text-center"><i class="fas fa-exclamation-circle text-9xl font-weight-bolder opacity-5"></i></span><br>
                                                        <span class="text-dark text-lg font-weight-bold text-center">Are you sure?</span><br>
                                                        <span class="text-secondary text-sm text-center">You won't be able to revert this data!</span><br>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-dark mb-0" name="deletepromo">Confirm Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal Delete -->



                            <?php endwhile; ?>

                        </table>

                        <!-- Modal Add -->
                        <div class="modal fade modal-lg" id="modalAdd" data-bs-backdrop="static" role="dialog" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalAddLabel">New Promotion</h1>
                                    </div>
                                    <form action="promo_crud.php" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Upload Poster</label>
                                                <input type="file" class="form-control form-control-lg" name="poster">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Promotion Code</label>
                                                    <input type="text" class="form-control form-control-lg" name="promo_code" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Discount (RM)</label>
                                                    <input type="text" class="form-control form-control-lg" name="amount_off" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Start Date</label>
                                                    <input type="date" class="form-control form-control-lg" name="start_date" required>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">End Date</label>
                                                    <input type="date" class="form-control form-control-lg" name="end_date" required>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Category</label>
                                                    <select class="form-select form-select-lg" name="promo_ctg_id">
                                                        <option value="" selected disabled>Select Category</option>
                                                        <?php
                                                        $categories = mysqli_query($con, "SELECT * FROM promotion_category");
                                                        if (mysqli_num_rows($categories) > 0) {
                                                            foreach ($categories as $category) {
                                                        ?>
                                                                <option value="<?= $category['promo_ctg_id'] ?>"><?= $category['ctg_name'] ?></option>

                                                        <?php
                                                            }
                                                        } else {
                                                            echo "No category available";
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control form-control-lg" name="promo_desc" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-sm btn-dark mb-0" name="addpromo">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal Add -->

                    </div>
                </div>
            </div>

        </div>
        <!-- end promotion table -->


    </div>
</div>
<!-- end promotion -->

<?php include('includes/footer.php') ?>

<!-- <script>
function updateStatus() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "promo_crud.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log("Status updated");
            location.reload(); // Reload the page to reflect the updated statuses
        }
    };
    xhr.send();
}

// Update status every 5 minutes (300000 milliseconds)
setInterval(updateStatus, 300000);

// Call updateStatus on page load
window.onload = updateStatus;
</script> -->

<script>
    function updateStatus() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "promo_crud.php?update_status=true", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                console.log("Status updated");
                // You can optionally reload the page here if you want
            }
        };
        xhr.send();
    }

    //Update status every 5 minutes(300000 milliseconds)
    // setInterval(updateStatus, 300000);

    // Call updateStatus when the page loads
    window.onload = function() {
        updateStatus();
    };
</script>