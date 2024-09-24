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

$page_title = 'Expenses';
include('includes/header.php');
?>
<!-- promotion categories table -->
<div class="container">
    <div class="row">
        <div class="row-md-12">
            <div class="col-12">
                <div class="card border shadow-xs mb-4">
                    <div class="card-header border-bottom pb-0">
                        <div class="d-sm-flex align-items-center">
                            <div>
                                <h6 class="font-weight-semibold text-lg mb-0">Expenses</h6>
                                <p class="text-sm">See information about all expenses</p>
                            </div>
                            <div class="ms-auto d-flex justify-content-end">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                    <span class="btn-inner--icon"><i class="fas fa-dollar d-block me-2"></i></span>
                                    <span class="btn-inner--text">New Expenses</span>
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
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</span>
                                        </th>
                                        <th class="align-middle text-center">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount (RM)</span>
                                        </th>
                                        <th class="align-middle text-center">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</span>
                                        </th>
                                    </tr>
                                </thead>
                                <?php
                                $no = "1";
                                $query = mysqli_query($con, "SELECT * FROM expenses ORDER BY date_created DESC");
                                while ($data = mysqli_fetch_array($query)) :
                                ?>

                                    <tr>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= $no++ ?></span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <p class="text-xs font-weight-bold mb-0"><?= $data['expense_name'] ?></p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= $data['amount'] ?></span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#modalView<?= $data['expense_id'] ?>"><i class="fas fa-eye text-sm opacity-5"></i></a>
                                            <span style="margin: 0 10px;"></span>
                                            <a href="#" class="text-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['expense_id'] ?>"><i class="fas fa-edit text-sm opacity-5"></i></a>
                                            <span style="margin: 0 10px;"></span>
                                            <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $data['expense_id'] ?>"><i class="fas fa-trash text-sm opacity-5"></i></a>
                                        </td>
                                    </tr>

                                    <!-- Modal View -->
                                    <div class="modal fade" id="modalView<?= $data['expense_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditLabel<?= $data['expense_id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="modalEditLabel<?= $data['expense_id'] ?>">Expenses</h1>
                                                </div>
                                                <form action="expenses_crud.php" method="POST">
                                                    <input type="hidden" name="expense_id" value="<?= $data['expense_id'] ?>">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Name</label>
                                                            <input type="text" class="form-control form-control-lg" name="expense_name" value="<?= $data['expense_name'] ?>" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Amount</label>
                                                            <input type="text" class="form-control form-control-lg" name="amount" value="<?= $data['amount'] ?>" readonly>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Date Created</label>
                                                                <input type="text" class="form-control form-control-lg" name="date_created" value="<?= $data['date_created'] ?>" readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Last Update</label>
                                                                <input type="text" class="form-control form-control-lg" name="date_updated" value="<?= $data['date_updated'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Description</label>
                                                            <input type="textarea" class="form-control form-control-lg" name="description" value="<?= $data['description'] ?>" readonly>
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
                                    <div class="modal fade" id="modalEdit<?= $data['expense_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditLabel<?= $data['expense_id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="modalEditLabel<?= $data['expense_id'] ?>">Edit Expenses</h1>
                                                </div>
                                                <form action="expenses_crud.php" method="POST">
                                                    <input type="hidden" name="expense_id" value="<?= $data['expense_id'] ?>">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Name</label>
                                                            <input type="text" class="form-control form-control-lg" name="expense_name" value="<?= $data['expense_name'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Amount</label>
                                                            <input type="text" class="form-control form-control-lg" name="amount" value="<?= $data['amount'] ?>" required>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Date Created</label>
                                                                <input type="text" class="form-control form-control-lg" name="date_created" value="<?= $data['date_created'] ?>" readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Last Update</label>
                                                                <input type="text" class="form-control form-control-lg" name="date_updated" value="<?= $data['date_updated'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Description</label>
                                                            <input type="textarea" class="form-control form-control-lg" name="description" value="<?= $data['description'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-sm btn-dark mb-0" name="editexpenses">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal Edit -->

                                    <!-- Modal Delete -->
                                    <div class="modal fade" id="modalDelete<?= $data['expense_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel<?= $data['expense_id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalEditLabel<?= $data['expense_id'] ?>">Delete Confirmation</h1>
                                                </div>
                                                <form action="expenses_crud.php" method="POST">
                                                    <input type="hidden" name="expense_id" value="<?= $data['expense_id'] ?>">
                                                    <div class="modal-body">
                                                        <div class="d-flex flex-column">
                                                            <span class="text-danger font-weight-bold text-center"><i class="fas fa-exclamation-circle text-9xl font-weight-bolder opacity-5"></i></span><br>
                                                            <span class="text-dark text-lg font-weight-bold text-center">Are you sure?</span><br>
                                                            <span class="text-secondary text-sm text-center">You won't be able to revert this data!</span><br>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-sm btn-dark mb-0" name="deleteexpenses">Confirm Delete</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal Delete -->



                                <?php endwhile; ?>

                            </table>

                            <!-- Modal Add -->
                            <div class="modal fade" id="modalAdd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="modalAddLabel">New Expenses</h1>
                                        </div>
                                        <form action="expenses_crud.php" method="POST">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" class="form-control form-control-lg" name="expense_name" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Amount</label>
                                                    <input type="text" class="form-control form-control-lg" name="amount" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <textarea class="form-control form-control-lg" name="description" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-sm btn-dark mb-0" name="addexpenses">Save</button>
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
        </div>
    </div>
</div>
<!-- end promotion categories table -->

<?php include('includes/footer.php') ?>