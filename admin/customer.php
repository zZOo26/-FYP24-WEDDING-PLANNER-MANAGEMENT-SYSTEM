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

$page_title = 'Customer Accounts';
include('includes/header.php');
?>

<div class="container">
    <div class="row">
        <div class="row-md-12">
            <div class="col-12">
                <div class="card border shadow-xs mb-4">
                    <div class="card-header border-bottom pb-0">

                    <div class="d-sm-flex align-items-center">
                        <div>
                            <h6 class="font-weight-semibold text-lg mb-0">Customer's Accounts</h6>
                            <p class="text-sm">See information about all customer</p>
                        </div>
                        <div class="ms-auto d-flex justify-content-end">

                            <!-- Button trigger modal -->
                            <!-- <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                <span class="btn-inner--icon">
                                    <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="d-block me-2">
                                        <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z" />
                                    </svg>
                                </span><span class="btn-inner--text">Add customer</span>
                            </button> -->
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
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">First Name</span>
                                        </th>
                                        <th class="align-middle text-center">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Last Name</span>
                                        </th>
                                        <th class="align-middle text-center">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Phone Number</span>
                                        </th>
                                        <th class="align-middle text-center">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</span>
                                        </th>

                                        <th class="align-middle text-center">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</span>
                                        </th>
                                    </tr>
                                </thead>
                                <?php
                                $no = "1";
                                $query = mysqli_query($con, "SELECT * FROM customers ORDER BY  created_at DESC");
                                while ($data = mysqli_fetch_array($query)) :
                                ?>

                                    <tr>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= $no++ ?></span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <p class="text-xs font-weight-bold mb-0"><?= $data['firstname'] ?></p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <p class="text-xs font-weight-bold mb-0"><?= $data['lastname'] ?></p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <p class="text-xs font-weight-bold mb-0">+60<?= $data['phoneNo'] ?></p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <p class="text-xs font-weight-bold mb-0"><?= $data['email'] ?></p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <a href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#modalView<?= $data['cust_id'] ?>"><i class="fas fa-eye text-sm opacity-5"></i></a>
                                            <span style="margin: 0 10px;"></span>
                                            <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $data['cust_id'] ?>"><i class="fas fa-trash text-sm opacity-5"></i></a>
                                        </td>
                                    </tr>

                                    <!-- Modal View -->
                                    <div class="modal fade" id="modalView<?= $data['cust_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalViewLabel<?= $data['cust_id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="modalViewLabel<?= $data['cust_id'] ?>">Edit Promotion Category</h1>
                                                </div>
                                                <form>
                                                    <input type="hidden" name="cust_id" value="<?= $data['cust_id'] ?>">
                                                    <div class="modal-body">
                                                        <div class="row d-flex">
                                                            <div class="mb-3">
                                                                <label class="form-label">First Name</label>
                                                                <input type="text" class="form-control form-control-lg" name="firstname" value="<?= $data['firstname'] ?>" readonly>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Last Name</label>
                                                                <input type="text" class="form-control form-control-lg" name="lastname" value="<?= $data['lastname'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Phone Number</label>
                                                            <input type="text" class="form-control form-control-lg" name="phoneNo" value="<?= $data['phoneNo'] ?>" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Email Address</label>
                                                            <input type="text" class="form-control form-control-lg" name="email" value="<?= $data['email'] ?>" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Registered at</label>
                                                            <input type="text" class="form-control form-control-lg" name="created_at" value="<?= $data['created_at'] ?>" readonly>
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

                                    <!-- Modal Delete -->
                                    <div class="modal fade" id="modalDelete<?= $data['cust_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel<?= $data['cust_id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalDeleteLabel<?= $data['cust_id'] ?>">Delete Confirmation</h1>
                                                </div>
                                                <form action="customer_crud.php" method="POST">
                                                    <input type="hidden" name="cust_id" value="<?= $data['cust_id'] ?>">
                                                    <div class="modal-body">
                                                    <div class="d-flex flex-column">
                                                            <span class="text-danger font-weight-bold text-center"><i class="fas fa-exclamation-circle text-9xl font-weight-bolder opacity-5"></i></span><br>
                                                            <span class="text-dark text-lg font-weight-bold text-center">Delete <?= $data['firstname'] ?> <?= $data['lastname'] ?>?</span><br>
                                                            <span class="text-secondary text-sm text-center">You won't be able to revert this data!</span><br>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-sm btn-dark mb-0" name="deletecustomer">Confirm Delete</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal Delete -->



                                <?php endwhile; ?>

                            </table>

                            <!-- Modal Add -->
                            <!-- <div class="modal fade" id="modalAdd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="modalAddLabel">Add New Customer</h1>
                                        </div>
                                        <form action="customer_crud.php" method="POST">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">First Name</label>
                                                    <input type="text" class="form-control form-control-lg" name="firstname" placeholder="First name" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Last Name</label>
                                                    <input type="text" class="form-control form-control-lg" name="lastname" placeholder="Last Name" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Phone Number</label>
                                                    <input type="text" class="form-control form-control-lg" name="phoneNo" placeholder="Phone Number" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Email Address</label>
                                                    <input type="text" class="form-control form-control-lg" name="email" placeholder="Email Address" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-sm btn-dark mb-0" name="addcustomer">Add Customer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div> -->
                            <!-- End Modal Add -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php') ?>