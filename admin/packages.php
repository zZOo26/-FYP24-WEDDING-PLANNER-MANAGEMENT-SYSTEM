<?php
session_start();
include('../dbcon.php');
include('functions.php');

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page if not logged in
    header("Location: admin_login.php");
    die;
}

$user_data = check_login($con);

$page_title = 'Packages';
include('includes/header.php');

function getAllActive($table, $category_id = null)
{
    global $con;
    $query = "SELECT * FROM $table";
    if ($category_id) {
        $query .= " WHERE pkg_ctg_id = $category_id";
    }
    $query .= " ORDER BY created_at DESC";
    $query_run = mysqli_query($con, $query);
    return $query_run;
}

function getAllCategories()
{
    global $con;
    $query = "SELECT * FROM package_category ORDER BY ctg_name";
    $query_run = mysqli_query($con, $query);
    return $query_run;
}

$selected_category = isset($_GET['category_id']) ? $_GET['category_id'] : null;
$package = getAllActive("packages", $selected_category);

?>

<div class="container">
    <div class="d-sm-flex align-items-center">
        <div>
            <h6 class="font-weight-semibold text-lg mb-0"><?= $page_title ?></h6>
            <p class="text-sm">See information about all packages and categories</p>
        </div>
        <div class="ms-auto d-flex justify-content-end">
            <!-- Category Filter -->
            <form action="" method="GET" class="d-flex align-items-center">
                <select name="category_id" class="form-select form-select px-5 mb-3 me-2" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php
                    $categories = getAllCategories();
                    if (mysqli_num_rows($categories) > 0) {
                        foreach ($categories as $category) {
                            $selected = $category['pkg_ctg_id'] == $selected_category ? 'selected' : '';
                            echo "<option value='{$category['pkg_ctg_id']}' $selected>{$category['ctg_name']}</option>";
                        }
                    }
                    ?>
                </select>
            </form> 
            <!-- Button trigger modal for new category -->
            <button type="button" class="btn btn-sm btn-outline-dark btn-icon d-flex align-items-center me-2" data-bs-toggle="modal" data-bs-target="#modalAddCategory">
                <span class="btn-inner--icon">
                    <i class="fas fa-plus d-block me-2"></i>
                </span>
                <span class="btn-inner--text">New category</span>
            </button>
            <!-- Button trigger modal for new package -->
            <a href="pkg-add.php" type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2">
                <span class="btn-inner--icon">
                    <i class="fas fa-box d-block me-2"></i>
                </span>
                <span class="btn-inner--text">New package</span>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="row mt-3">
            <?php
            if (mysqli_num_rows($package) > 0) {
                foreach ($package as $item) {
            ?>
                    <div class="col-md-4 mt-md-0">
                        <a href="package_detail.php?pkg_id=<?= urlencode($item['pkg_id']) ?>">
                            <div class="card shadow-lg move-on-hover min-height-250 max-height-250">
                                <img class="w-100 my-auto text-secondary" src="uploads/<?= $item['pkg_img'] ?>" alt="image">
                                <div class="position-absolute top-0 end-0 m-2">
                                    <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $item['pkg_id'] ?>"><i class="fas fa-trash text-sm opacity-8"></i></a>
                                </div>
                            </div>
                            <div class="mt-2 ms-2">
                                <p class="text-gradient text-dark mb-2 text-sm"> • <?= $item['duration'] ?> Hours </p>
                                <h6 class="mb-0"><?= $item['pkg_name'] ?></h6>
                                <p class="text-secondary text-sm"><?= $item['pkg_desc'] ?></p>
                                <p class="text-secondary text-sm">RM<?= $item['pkg_price'] ?></p>
                            </div>
                        </a>
                    </div>
                    <!-- Modal Delete -->
                    <div class="modal fade" id="modalDelete<?= $item['pkg_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel<?= $item['pkg_id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalEditLabel<?= $item['pkg_id'] ?>">Delete Confirmation</h1>
                                </div>
                                <form action="package_crud.php" method="POST">
                                    <input type="hidden" name="pkg_id" value="<?= $item['pkg_id'] ?>">
                                    <div class="modal-body">
                                        <div class="d-flex flex-column">
                                            <span class="text-dark-lg font-weight-bold text-center"><img class="avatar avatar-xl sm-3" src="./uploads/<?= $item['pkg_img'] ?>" /></span><br>
                                            <span class="text-dark text-lg font-weight-bold text-center">Are you sure?</span><br>
                                            <span class="text-secondary text-sm text-center">You won't be able to revert this data!</span><br>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-sm btn-dark mb-0" name="deletepkg">Confirm Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal Delete -->
            <?php
                }
            } else {
                echo "No data available";
            }
            ?>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="row mt-5">
        <div class="col-md-12">
            <h6 class="font-weight-semibold text-lg mb-0">Categories</h6>
            <p class="text-sm">List of all categories</p>
            <div class="col-md-4">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Category Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $categories = getAllCategories();

                            if (mysqli_num_rows($categories) > 0) {
                                foreach ($categories as $category) {
                            ?>
                                    <tr>
                                        <th scope="row"><?= $no++ ?></th>
                                        <td><?= $category['ctg_name'] ?></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='2' class='text-center'>No categories available</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Add Category -->
<div class="modal fade" id="modalAddCategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAddCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalAddCategoryLabel">Add New Category</h1>
            </div>
            <form action="package_crud.php" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ctg_name" class="form-label">Category Name</label>
                        <input type="text" name="ctg_name" id="ctg_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-dark mb-0" name="addCategory">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Add Category -->

<?php include('includes/footer.php') ?>
