<!-- table  -->
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

$page_title = 'External Resources';
include('includes/header.php');


function getAllActive($table, $category_id = null)
{
    global $con;
    $query = "SELECT * FROM $table INNER JOIN resource_category ON $table.resource_ctg_id = resource_category.resource_ctg_id";
    if ($category_id) {
        $escaped_category_id = mysqli_real_escape_string($con, $category_id);
        $query .= " WHERE $table.resource_ctg_id = '$escaped_category_id'";
    }
    $query .= " ORDER BY $table.created_at";
    $query_run = mysqli_query($con, $query);
    return $query_run;
}

function getAllCategories()
{
    global $con;
    $query = "SELECT * FROM resource_category ORDER BY ctg_name";
    $query_run = mysqli_query($con, $query);
    return $query_run;
}

$selected_category = isset($_GET['category_id']) ? $_GET['category_id'] : null;
$resource = getAllActive("ex_resources", $selected_category);

?>
<!-- external resources -->
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
                        <form class="d-flex align-items-center flex-grow-1" action="exresource_crud.php" method="POST">
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
                                $query = mysqli_query($con, "SELECT * FROM resource_category");
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



        <!-- resource table -->
        <div class="col-12 col-xl mb-4">

            <div class="card border shadow-xs mb-4">
                <div class="card-header border-bottom pb-0">
                    <div class="d-sm-flex align-items-center">
                        <div>
                            <h6 class="font-weight-semibold text-lg mb-0">External Resources</h6>
                            <p class="text-sm">See information about all members</p>
                        </div>
                        <div class="ms-auto d-flex justify-content-end">
                            <div class="me-2">
                                <!-- Category Filter -->
                                <form action="" method="GET" class="d-flex align-items-center">
                                    <select name="category_id" class="form-select form-select px-5 mb-3 me-2" onchange="this.form.submit()">
                                        <option value="">All Categories</option>
                                        <?php
                                        $categories = getAllCategories();
                                        if (mysqli_num_rows($categories) > 0) {
                                            foreach ($categories as $category) {
                                                $selected = $category['resource_ctg_id'] == $selected_category ? 'selected' : '';
                                                echo "<option value='{$category['resource_ctg_id']}' $selected>{$category['ctg_name']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </form>
                            </div>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                <span class="btn-inner--icon">
                                    <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="d-block me-2">
                                        <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z" />
                                    </svg>
                                </span><span class="btn-inner--text">Add member</span>
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
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Full name</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Contact</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Role</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Account detail</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</span>
                                    </th>
                                </tr>
                            </thead>
                            <?php
                            $no = 1;
                            while ($data = mysqli_fetch_array($resource)) :
                            ?>

                                <tr>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold"><?= $no++ ?></span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-0"><?= $data['fullname'] ?></p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-sm text-dark font-weight-semibold mb-0">+60<?= $data['phoneNo'] ?></p>
                                        <p class="text-sm text-secondary mb-0"><?= $data['email'] ?></p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-0"><?= $data['ctg_name'] ?></p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-sm text-dark font-weight-semibold mb-0"><?= $data['bank_name'] ?></p>
                                        <p class="text-sm text-secondary mb-0"><?= $data['acc_no'] ?></p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="#" class="text-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['resource_id'] ?>"><i class="fas fa-edit text-sm font-weight-bolder opacity-5"></i></a>
                                        <span style="margin: 0 10px;"></span>
                                        <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $data['resource_id'] ?>"><i class="fas fa-trash text-sm font-weight-bolder opacity-5"></i></a>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="modalEdit<?= $data['resource_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditLabel<?= $data['resource_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="modalEditLabel<?= $data['resource_id'] ?>">Edit Details</h1>
                                            </div>
                                            <form action="exresource_crud.php" method="POST">
                                                <input type="hidden" name="resource_id" value="<?= $data['resource_id'] ?>">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Full Name</label>
                                                        <input type="text" class="form-control form-control-lg" name="fullname" value="<?= $data['fullname'] ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="text" class="form-control form-control-lg" name="email" value="<?= $data['email'] ?>" required>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Phone Number</label>
                                                            <input type="text" class="form-control form-control-lg" name="phoneNo" value="+60<?= $data['phoneNo'] ?>" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Role</label>
                                                            <select class="form-select form-select-lg" name="resource_ctg_id">
                                                                <option value="" selected disabled>Select Category</option>
                                                                <?php
                                                                $categories = mysqli_query($con, "SELECT * FROM resource_category");
                                                                if (mysqli_num_rows($categories) > 0) {
                                                                    foreach ($categories as $category) {
                                                                ?>
                                                                        <option value="<?= $category['resource_ctg_id'] ?>" <?= $data['resource_ctg_id'] == $category['resource_ctg_id'] ? 'selected' : '' ?>><?= $category['ctg_name'] ?></option>

                                                                <?php
                                                                    }
                                                                } else {
                                                                    echo "No category available";
                                                                }

                                                                ?>
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Bank</label>
                                                            <input type="text" class="form-control form-control-lg" name="bank_name" value="<?= $data['bank_name'] ?>" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Account Number</label>
                                                            <input type="text" class="form-control form-control-lg" name="acc_no" value="<?= $data['acc_no'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Remark (Price Rate)</label>
                                                        <textarea class="form-control form-control-lg" name="remark" required><?= $data['remark'] ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-dark mb-0" name="editresource">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal Edit -->

                                <!-- Modal Delete -->
                                <div class="modal fade" id="modalDelete<?= $data['resource_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel<?= $data['resource_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalEditLabel<?= $data['resource_id'] ?>">Delete Confirmation</h1>
                                            </div>
                                            <form action="exresource_crud.php" method="POST">
                                                <input type="hidden" name="resource_id" value="<?= $data['resource_id'] ?>">
                                                <div class="modal-body">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-danger font-weight-bold text-center"><i class="fas fa-exclamation-circle text-9xl font-weight-bolder opacity-5"></i></span><br>
                                                        <span class="text-dark text-lg font-weight-bold text-center">Delete <?= $data['fullname'] ?>?</span><br>
                                                        <span class="text-secondary text-sm text-center">You won't be able to revert this data!</span><br>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-dark mb-0" name="deleteresource">Confirm Delete</button>
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
                                        <h1 class="modal-title fs-5" id="modalAddLabel">Add Member</h1>
                                    </div>
                                    <form action="exresource_crud.php" method="POST">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Full Name</label>
                                                <input type="text" class="form-control form-control-lg" name="fullname" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="text" class="form-control form-control-lg" name="email" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Phone Number</label>
                                                    <input type="text" class="form-control form-control-lg" name="phoneNo" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Role</label>
                                                    <select class="form-select form-select-lg" name="resource_ctg_id">
                                                        <option value="" selected disabled>Select Category</option>
                                                        <?php
                                                        $categories = mysqli_query($con, "SELECT * FROM resource_category");
                                                        if (mysqli_num_rows($categories) > 0) {
                                                            foreach ($categories as $category) {
                                                        ?>
                                                                <option value="<?= $category['resource_ctg_id'] ?>"><?= $category['ctg_name'] ?></option>

                                                        <?php
                                                            }
                                                        } else {
                                                            echo "No category available";
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Bank</label>
                                                    <input type="text" class="form-control form-control-lg" name="bank_name" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Account Number</label>
                                                    <input type="text" class="form-control form-control-lg" name="acc_no" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Remark (Price Rate)</label>
                                                <textarea type="text" class="form-control form-control-lg" name="remark" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-sm btn-dark mb-0" name="addresource">Save</button>
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
        <!-- end resource table -->
    </div>
</div>

<!-- end external resources -->





<?php include('includes/footer.php') ?>