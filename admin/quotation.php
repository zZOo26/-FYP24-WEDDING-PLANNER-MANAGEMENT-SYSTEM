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

$page_title = 'Quotation Request';
include('includes/header.php');
?>

<!-- quotation request -->

<div class="container">
    <div class="row">
        <div class="row-md-12">
            <div class="col-12">
                <div class="card border shadow-xs mb-4">
                    <div class="card-header border-bottom pb-0">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="font-weight-semibold text-lg mb-0">Quotation Requests</h6>
                                <p class="text-sm">See information about all requests</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <!-- Filter form -->
                                <form action="" method="GET" class="mb-3 ms-3">
                                    <select class="form-select mt-2" name="status_filter" onchange="this.form.submit()">
                                        <option value="all" <?= isset($_GET['status_filter']) && $_GET['status_filter'] == 'all' ? 'selected' : '' ?>>All</option>
                                        <option value="0" <?= isset($_GET['status_filter']) && $_GET['status_filter'] == '0' ? 'selected' : '' ?>>Pending</option>
                                        <option value="1" <?= isset($_GET['status_filter']) && $_GET['status_filter'] == '1' ? 'selected' : '' ?>>Completed</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">

                            <form action="quotation_crud.php" method="POST">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center">
                                                <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No.</span>
                                            </th>
                                            <th class="align-middle text-center">
                                                <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request</span>
                                            </th>
                                            <th class="align-middle text-center">
                                                <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Event Date</span>
                                            </th>
                                            <th class="align-middle text-center">
                                                <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Event Type</span>
                                            </th>
                                            <th class="align-middle text-center">
                                                <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Event Location</span>
                                            </th>
                                            <th class="align-middle text-center">
                                                <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Pax</span>
                                            </th>
                                            <th class="align-middle text-center">
                                                <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Update</span>
                                            </th>
                                            <th class="align-middle text-center">
                                                <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</span>
                                            </th>
                                        </tr>
                                    </thead>

                                    <?php 
                                    $no = 1;
                                    $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : 'all';
                                    $query_string = "SELECT * FROM quotation_request";
                                    if ($status_filter != 'all') {
                                        $query_string .= " WHERE status = $status_filter";
                                    }
                                    $query_string .= " ORDER BY status";
                                    $query = mysqli_query($con, $query_string);
                                    while ($data = mysqli_fetch_array($query)) :
                                    ?>

                                        <tr>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?= $no++ ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex flex-column justify-content-center ms-1">
                                                    <h6 class="mb-0 text-sm font-weight-semibold"><?= $data['fullname'] ?></h6>
                                                    <p class="text-sm text-secondary mb-0">0<?= $data['phoneNo'] ?></p>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?= date('d/m/y ', strtotime($data['event_date'])) ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?= $data['event_type'] ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?= $data['event_location'] ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?= $data['total_pax'] ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <input type="checkbox" name="status_update[]" value="<?= $data['request_id'] ?>" <?= $data['status'] == 1 ? 'checked' : '' ?>>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#modalView<?= $data['request_id'] ?>"><i class="fas fa-eye text-sm opacity-5"></i></a>
                                                <span style="margin: 0 10px;"></span>
                                                <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $data['request_id'] ?>"><i class="fas fa-trash text-sm opacity-5"></i></a>
                                            </td>
                                        </tr>

                                        <!-- Modal View -->
                                        <div class="modal fade modal-lg" id="modalView<?= $data['request_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalViewLabel<?= $data['request_id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalViewLabel<?= $data['request_id'] ?>">Request Details</h1>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5 class="text-center">
                                                            <ul class="list-group">
                                                                <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pt-0 pb-1 text-sm"><span class="text-secondary">Full Name:</span> &nbsp; <?= $data['fullname'] ?></li>
                                                                <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm"><span class="text-secondary">Email:</span> &nbsp; <?= $data['email'] ?></li>
                                                                <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm"><span class="text-secondary">Mobile:</span> &nbsp; <?= $data['phoneNo'] ?></li>
                                                                <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm"><span class="text-secondary">Event Date:</span> &nbsp; <?= $data['event_date'] ?></li>
                                                                <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm"><span class="text-secondary">Event Type:</span> &nbsp; <?= $data['event_type'] ?></li>
                                                                <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm"><span class="text-secondary">Event Location:</span> &nbsp; <?= $data['event_location'] ?></li>
                                                                <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm"><span class="text-secondary">Total (Pax):</span> &nbsp; <?= $data['total_pax'] ?></li>
                                                                <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm"><span class="text-secondary">Question:</span> &nbsp; <?= $data['question'] ?></li>
                                                                <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm"><span class="text-secondary">Date Requested:</span> &nbsp; <?= $data['created_at'] ?></li>
                                                                <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm"><span class="text-secondary">Status:</span> &nbsp; <?= $data['status'] == 1 ? 'Completed' : 'Pending' ?></li>
                                                            </ul>
                                                        </h5>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-dark mb-0" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal View -->

                                        <!-- Modal Delete -->
                                        <div class="modal fade" id="modalDelete<?= $data['request_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel<?= $data['request_id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalDeleteLabel<?= $data['request_id'] ?>">Delete Confirmation</h1>
                                                    </div>
                                                    <form action="quotation_crud.php" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="request_id" value="<?= $data['request_id'] ?>">
                                                        <div class="modal-body">
                                                            <div class="d-flex flex-column">
                                                                <span class="text-danger font-weight-bold text-center"><i class="fas fa-exclamation-circle text-9xl font-weight-bolder opacity-5"></i></span><br>
                                                                <span class="text-dark text-lg font-weight-bold text-center">Are you sure?</span><br>
                                                                <span class="text-secondary text-sm text-center">You won't be able to revert this data!</span><br>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-sm btn-dark mb-0" name="deletequot">Confirm Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal Delete -->

                                    <?php endwhile; ?>
                                </table>
                                <div class="text-end mt-3 me-3">
                                    <button type="submit" class="btn btn-sm btn-dark mb-3">Update Status</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- end quotation request -->

<?php include('includes/footer.php') ?>