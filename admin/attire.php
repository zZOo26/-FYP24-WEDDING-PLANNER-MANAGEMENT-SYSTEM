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

$page_title = 'Bridal Attires';
include('includes/header.php');

// Fetch unique category from bridal_attire table
$category_query = "SELECT DISTINCT category FROM bridal_attire";
$category_result = mysqli_query($con, $category_query);

$categories = [];
if (mysqli_num_rows($category_result) > 0) {
    while ($category_row = mysqli_fetch_assoc($category_result)) {
        $categories[] = $category_row['category'];
    }
}
?>
<!-- Bridal Attires -->
<div class="container">
    <div class="row">

        <!-- attires table -->
        <div class="col-12 col-xl mb-4">

            <div class="card border shadow-xs mb-4">
                <div class="card-header border-bottom pb-0">
                    <div class="d-sm-flex align-items-center">
                        <div>
                            <h6 class="font-weight-semibold text-lg mb-0">Bridal Attires</h6>
                            <p class="text-sm">See information about all attires</p>
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
                                    <i class="fas fa-person-half-dress d-block me-2"></i>
                                </span>
                                <span class="btn-inner--text">New attire</span>
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
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Attires</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Size</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Color</span>
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
                            $query_string = "SELECT * FROM bridal_attire";

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
                                        <p class="text-sm text-dark font-weight-semibold mb-0"><?= $data['min_size'] ?> - <?= $data['max_size'] ?></p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-sm text-dark font-weight-semibold mb-0"><?= $data['color'] ?></p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex flex-column justify-content-start ms-1">
                                            <h6 class="mb-0 text-sm font-weight-semibold">RM<?= $data['normal_price'] ?></h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#modalView<?= $data['attire_id'] ?>"><i class="fas fa-eye text-sm font-weight-bolder opacity-5"></i></a>
                                        <span style="margin: 0 10px;"></span>
                                        <a href="#" class="text-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['attire_id'] ?>"><i class="fas fa-edit text-sm font-weight-bolder opacity-5"></i></a>
                                        <span style="margin: 0 10px;"></span>
                                        <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $data['attire_id'] ?>"><i class="fas fa-trash text-sm font-weight-bolder opacity-5"></i></a>
                                    </td>
                                </tr>

                                <!-- Modal View -->
                                <div class="modal fade modal-lg" id="modalView<?= $data['attire_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditLabel<?= $data['attire_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="modalEditLabel<?= $data['attire_id'] ?>">Attire Details</h1>
                                            </div>
                                            <form>
                                                <input type="hidden" name="attire_id" value="<?= $data['attire_id'] ?>">
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
                                                            <div class="col-md-4 mb-3">
                                                                <label class="form-label">Type</label>
                                                                <input type="text" class="form-control form-control-lg" name="category" value="<?= $data['category'] ?>" readonly>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label class="form-label">Size</label>
                                                                <input type="text" class="form-control form-control-lg" name="size" value="<?= $data['min_size'] ?> - <?= $data['max_size'] ?>" readonly>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label class="form-label">Color</label>
                                                                <input type="text" class="form-control form-control-lg" name="color" value="<?= $data['color'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Normal Price (RM)</label>
                                                                <input type="text" class="form-control form-control-lg" name="normal_price" value="<?= $data['normal_price'] ?>" readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Deposit (RM)</label>
                                                                <input type="text" class="form-control form-control-lg" name="deposit" value="<?= $data['deposit'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Description</label>
                                                            <textarea class="form-control form-control-lg" name="desc" readonly> <?= $data['attire_desc'] ?> </textarea>
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
                                <div class="modal fade modal-lg" id="modalEdit<?= $data['attire_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditLabel<?= $data['attire_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="modalEditLabel<?= $data['attire_id'] ?>">Edit Details</h1>
                                            </div>
                                            <form action="attire_crud.php" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="attire_id" value="<?= $data['attire_id'] ?>">
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
                                                        $existing_min_size = $data['min_size'];
                                                        $existing_max_size = $data['max_size'];
                                                        $existing_type = $data['category'];

                                                        // Define an array of sizes
                                                        $sizes = ['XXS', 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL'];

                                                        // Define an array of sizes
                                                        $types = ['Songket', 'Non-songket', 'Mixed-songket'];
                                                        ?>

                                                        <div class="col-md-3 mb-3">
                                                            <label class="form-label">Minimum Size</label>
                                                            <select class="form-select form-select-lg" name="min_size">
                                                                <option value="N/A" disabled>Select size</option>
                                                                <?php foreach ($sizes as $size) : ?>
                                                                    <option value="<?= htmlspecialchars($size) ?>" <?= $existing_min_size == $size ? 'selected' : '' ?>>
                                                                        <?= htmlspecialchars($size) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label class="form-label">Maximum Size</label>
                                                            <select class="form-select form-select-lg" name="max_size">
                                                                <option value="N/A" disabled>Select size</option>
                                                                <?php foreach ($sizes as $size) : ?>
                                                                    <option value="<?= htmlspecialchars($size) ?>" <?= $existing_max_size == $size ? 'selected' : '' ?>>
                                                                        <?= htmlspecialchars($size) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
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
                                                        <div class="col-md-3 mb-3">
                                                            <label class="form-label">Color</label>
                                                            <input type="text" class="form-control form-control-lg" name="color" value="<?= $data['color'] ?>" required>
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
                                                        <label class="form-label">Accessories</label>
                                                        <textarea class="form-control form-control-lg" name="accessories" required><?= $data['accessories'] ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Description</label>
                                                        <textarea class="form-control form-control-lg" name="attire_desc" required><?= $data['attire_desc'] ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-dark mb-0" name="editattire">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal Edit -->

                                <!-- Modal Delete -->
                                <div class="modal fade" id="modalDelete<?= $data['attire_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel<?= $data['attire_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalEditLabel<?= $data['attire_id'] ?>">Delete Confirmation</h1>
                                            </div>
                                            <form action="attire_crud.php" method="POST">
                                                <input type="hidden" name="attire_id" value="<?= $data['attire_id'] ?>">
                                                <div class="modal-body">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-danger font-weight-bold text-center"><i class="fas fa-exclamation-circle text-9xl font-weight-bolder opacity-5"></i></span><br>
                                                        <span class="text-dark text-lg font-weight-bold text-center">Delete <?= $data['name'] ?>?</span><br>
                                                        <span class="text-secondary text-sm text-center">You won't be able to revert this data!</span><br>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-dark mb-0" name="deleteattire">Confirm Delete</button>
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
                                        <h1 class="modal-title fs-5" id="modalAddLabel">New Attire</h1>
                                    </div>
                                    <form action="attire_crud.php" method="POST" enctype="multipart/form-data">
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
                                                $sizes = ['XXS', 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL'];

                                                // Define an array of sizes
                                                $types = ['Songket', 'Non-songket', 'Mixed-songket'];
                                                ?>

                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Minimum Size</label>
                                                    <select class="form-select form-select-lg" name="min_size">
                                                        <option value="N/A" select disabled>Select size</option>
                                                        <?php foreach ($sizes as $size) : ?>
                                                            <option value="<?= htmlspecialchars($size) ?>">
                                                                <?= htmlspecialchars($size) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Maximum Size</label>
                                                    <select class="form-select form-select-lg" name="max_size">
                                                        <option value="N/A" select disabled>Select size</option>
                                                        <?php foreach ($sizes as $size) : ?>
                                                            <option value="<?= htmlspecialchars($size) ?>">
                                                                <?= htmlspecialchars($size) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-3">
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
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Color</label>
                                                    <input type="text" class="form-control form-control-lg" name="color" required>
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
                                                <label class="form-label">Accessories (Separate item with coma ',')</label>
                                                <textarea class="form-control form-control-lg" name="accessories" placeholder="Separate items with a coma ','" required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control form-control-lg" name="attire_desc" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-sm btn-dark mb-0" name="addattire">Save</button>
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
        <!-- end attires table -->
    </div>
</div>

<!-- end Bridal Attires -->




<?php include('includes/footer.php') ?>