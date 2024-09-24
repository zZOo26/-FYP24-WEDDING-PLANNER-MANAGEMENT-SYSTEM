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

$page_title = 'Bridal Daises';
include('includes/header.php');

// Fetch unique category from bridal_dais table
$category_query = "SELECT DISTINCT category FROM bridal_dais";
$category_result = mysqli_query($con, $category_query);

$categories = [];
if (mysqli_num_rows($category_result) > 0) {
    while ($category_row = mysqli_fetch_assoc($category_result)) {
        $categories[] = $category_row['category'];
    }
}

?>
<!-- Bridal Daises -->
<div class="container">
    <div class="row">

        <!-- daises table -->
        <div class="col-12 col-xl mb-4">

            <div class="card border shadow-xs mb-4">
                <div class="card-header border-bottom pb-0">
                    <div class="d-sm-flex align-items-center">
                        <div>
                            <h6 class="font-weight-semibold text-lg mb-0">Bridal Daises</h6>
                            <p class="text-sm">See information about all daises</p>
                        </div>
                        <div class="ms-auto d-flex justify-content-end">
                            <div class="me-2">
                                <!-- Filter form -->
                                <form action="" method="GET" class="mb-3 ms-3">
                                    <select class="form-select px-5" name="category_filter" onchange="this.form.submit()">
                                        <option value="all" <?= isset($_GET['category_filter']) && $_GET['category_filter'] == 'all' ? 'selected' : '' ?>>All Types</option>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?= $category ?>" <?= isset($_GET['category_filter']) && $_GET['category_filter'] == $category ? 'selected' : '' ?>><?= $category ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </div>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                <span class="btn-inner--icon">
                                    <i class="fas fa-hand-holding-heart d-block me-2"></i>
                                </span>
                                <span class="btn-inner--text">New dais</span>
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
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Daises</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Size (FT)</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</span>
                                    </th>
                                </tr>
                            </thead>
                            <?php
                            $no = 1;
                            $category_filter = isset($_GET['category_filter']) ? $_GET['category_filter'] : 'all';
                            $query_string = "SELECT * FROM bridal_dais";

                            if ($category_filter != 'all') {
                                $escaped_category_filter = mysqli_real_escape_string($con, $category_filter);
                                $query_string .= " WHERE category = '$escaped_category_filter'";
                            }
                            $query_string .= " ORDER BY created_at DESC";
                            $query = mysqli_query($con, $query_string);
                            if (!$query) {
                                die('Query Error: ' . mysqli_error($con));
                            }
                            while ($data = mysqli_fetch_array($query)) :
                            ?>

                                <tr>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold"><?= $no++ ?></span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex align-items-center">
                                                <img class="avatar avatar-sm me-2" src="./uploads/<?= $data['image'] ?>" />
                                            </div>
                                            <div class="d-flex flex-column justify-content-start ms-1">
                                                <h6 class="mb-0 text-sm font-weight-semibold"><?= $data['name'] ?></h6>
                                                <p class="text-sm text-secondary mb-0"><?= $data['category'] ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-sm text-dark font-weight-semibold mb-0"><?= $data['dais_size'] ?> </p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex flex-column justify-content-start ms-1">
                                            <h6 class="mb-0 text-sm font-weight-semibold">RM<?= $data['normal_price'] ?></h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#modalView<?= $data['dais_id'] ?>"><i class="fas fa-eye text-sm font-weight-bolder opacity-5"></i></a>
                                        <span style="margin: 0 10px;"></span>
                                        <a href="#" class="text-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['dais_id'] ?>"><i class="fas fa-edit text-sm font-weight-bolder opacity-5"></i></a>
                                        <span style="margin: 0 10px;"></span>
                                        <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $data['dais_id'] ?>"><i class="fas fa-trash text-sm font-weight-bolder opacity-5"></i></a>
                                    </td>
                                </tr>

                                <!-- Modal View -->
                                <div class="modal fade modal-lg" id="modalView<?= $data['dais_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditLabel<?= $data['dais_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="modalEditLabel<?= $data['dais_id'] ?>">Dais Details</h1>
                                            </div>
                                            <form>
                                                <input type="hidden" name="dais_id" value="<?= $data['dais_id'] ?>">
                                                <div class="modal-body">
                                                    <div class="mb-3 text-center">
                                                        <img class="w-30" src="./uploads/<?= $data['image'] ?>" />
                                                    </div>
                                                    <span>
                                                        <div class="mb-3">
                                                            <label class="form-label">Name</label>
                                                            <input type="text" class="form-control form-control-lg" name="name" value="<?= $data['name'] ?>" readonly>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Type</label>
                                                                <input type="text" class="form-control form-control-lg" name="category" value="<?= $data['category'] ?>" readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Size</label>
                                                                <input type="text" class="form-control form-control-lg" name="dais_size" value="<?= $data['dais_size'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Normal Price</label>
                                                                <input type="text" class="form-control form-control-lg" name="normal_price" value="<?= $data['normal_price'] ?>" readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Deposit</label>
                                                                <input type="text" class="form-control form-control-lg" name="deposit" value="<?= $data['deposit'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Items Included</label>
                                                            <textarea class="form-control form-control-lg" name="items" readonly> <?= $data['items'] ?> </textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Description</label>
                                                            <textarea class="form-control form-control-lg" name="desc" readonly> <?= $data['dais_desc'] ?> </textarea>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Created At</label>
                                                                <input type="text" class="form-control form-control-lg" name="created_at" value="<?= $data['created_at'] ?>" readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Updated At</label>
                                                                <input type="text" class="form-control form-control-lg" name="updated_at" value="<?= $data['updated_at'] ?>" readonly>
                                                            </div>
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
                                <div class="modal fade modal-lg" id="modalEdit<?= $data['dais_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditLabel<?= $data['dais_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog ">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="modalEditLabel<?= $data['dais_id'] ?>">Edit Details</h1>
                                            </div>
                                            <form action="dais_crud.php" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="dais_id" value="<?= $data['dais_id'] ?>">
                                                <div class="modal-body">
                                                    <div class="row">

                                                        <label class="form-label">New Image</label>
                                                        <div class="col-md-1 mb-3">
                                                            <input type="hidden" name="old_img" value="<?= $data['image'] ?>">
                                                            <img class="avatar avatar-m" src="./uploads/<?= $data['image'] ?>" />
                                                        </div>
                                                        <div class="col-md-11 mb-3">
                                                            <input type="file" class="form-control form-control-lg" name="image">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Name</label>
                                                        <input type="text" class="form-control form-control-lg" name="name" value="<?= $data['name'] ?>" required>
                                                    </div>
                                                    <div class="row">
                                                        <?php
                                                        // Assume $existing_min_size contains the current value of min_size for the dais

                                                        $existing_type = $data['category'];

                                                        // Define an array of type
                                                        $types = ['Mini', 'DIY', 'Home/Rumah', 'Hall/Dewan', 'Tent/Khemah'];
                                                        ?>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Type</label>
                                                            <select class="form-select form-select-lg" name="category">
                                                                <option value="N/A" disabled>Select type</option>
                                                                <?php foreach ($types as $type) : ?>
                                                                    <option value="<?= htmlspecialchars($type) ?>" <?= $existing_type == $type ? 'selected' : '' ?>>
                                                                        <?= htmlspecialchars($type) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Size</label>
                                                            <input type="text" class="form-control form-control-lg" name="dais_size" value="<?= $data['dais_size'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Normal Price (RM)</label>
                                                            <input type="text" class="form-control form-control-lg" name="normal_price" value="<?= $data['normal_price'] ?>" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Deposit (RM)</label>
                                                            <input type="text" class="form-control form-control-lg" name="deposit" value="<?= $data['deposit'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Items included</label>
                                                        <textarea class="form-control form-control-lg" name="items" required><?= $data['items'] ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Description</label>
                                                        <textarea class="form-control form-control-lg" name="dais_desc" required><?= $data['dais_desc'] ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-dark mb-0" name="editdais">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal Edit -->

                                <!-- Modal Delete -->
                                <div class="modal fade" id="modalDelete<?= $data['dais_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel<?= $data['dais_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalEditLabel<?= $data['dais_id'] ?>">Delete Confirmation</h1>
                                            </div>
                                            <form action="dais_crud.php" method="POST">
                                                <input type="hidden" name="dais_id" value="<?= $data['dais_id'] ?>">
                                                <div class="modal-body">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-danger font-weight-bold text-center"><i class="fas fa-exclamation-circle text-9xl font-weight-bolder opacity-5"></i></span><br>
                                                        <span class="text-dark text-lg font-weight-bold text-center">Delete <?= $data['name'] ?>?</span><br>
                                                        <span class="text-secondary text-sm text-center">You won't be able to revert this data!</span><br>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-dark mb-0" name="deletedais">Confirm Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal Delete -->



                            <?php endwhile; ?>

                        </table>

                        <!-- Modal Add -->
                        <div class="modal fade modal-lg" id="modalAdd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="modalAddLabel">New Dais</h1>
                                    </div>
                                    <form action="dais_crud.php" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="row">

                                                <label class="form-label">Upload Image</label>
                                                <div class="mb-3">
                                                    <input type="file" class="form-control form-control-lg" name="image">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" class="form-control form-control-lg" name="name" required>
                                            </div>
                                            <div class="row">
                                                <?php
                                                // Define an array of sizes
                                                $types = ['Mini', 'DIY', 'Home/Rumah', 'Hall/Dewan', 'Tent/Khemah'];
                                                ?>

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Type</label>
                                                    <select class="form-select form-select-lg" name="category">
                                                        <option value="N/A" select disabled>Select type</option>
                                                        <?php foreach ($types as $type) : ?>
                                                            <option value="<?= htmlspecialchars($type) ?>">
                                                                <?= htmlspecialchars($type) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Size</label>
                                                    <input type="text" class="form-control form-control-lg" name="dais_size" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Normal Price (RM)</label>
                                                    <input type="text" class="form-control form-control-lg" name="normal_price" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Deposit (RM)</label>
                                                    <input type="text" class="form-control form-control-lg" name="deposit" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Items included</label>
                                                <textarea class="form-control form-control-lg" name="items" placeholder="Separate items with a coma ','" required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control form-control-lg" name="dais_desc" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-sm btn-dark mb-0" name="adddais">Save</button>
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
<!-- end daises table -->


<!-- end Bridal Daises -->





<?php include('includes/footer.php') ?>