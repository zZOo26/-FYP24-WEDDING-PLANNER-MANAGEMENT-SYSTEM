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

$page_title = 'Catering menus';
include('includes/header.php');

function getAllActive($table, $category_id = null)
{
    global $con;
    $query = "SELECT * FROM $table INNER JOIN menu_category ON $table.menu_ctg_id = menu_category.menu_ctg_id";
    if ($category_id) {
        $escaped_category_id = mysqli_real_escape_string($con, $category_id);
        $query .= " WHERE $table.menu_ctg_id = '$escaped_category_id'";
    }
    $query .= " ORDER BY $table.created_at";
    $query_run = mysqli_query($con, $query);
    return $query_run;
}

function getAllCategories()
{
    global $con;
    $query = "SELECT * FROM menu_category ORDER BY ctg_name";
    $query_run = mysqli_query($con, $query);
    return $query_run;
}

$selected_category = isset($_GET['category_id']) ? $_GET['category_id'] : null;
$menu = getAllActive("menus", $selected_category);

?>


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
                    <div class=" d-sm-flex align-items-center">
                        <form class="d-flex align-items-center flex-grow-1" action="menu_crud.php" method="POST">
                            <input type="text" class="form-control form-control-sm me-2" name="ctg_name" required placeholder="Add New Category">

                            <button type="submit" class="btn btn-sm btn-dark mb-0" name="addctg">Save</button>
                        </form>
                    </div><br>


                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">

                            <table class=" border-top table align-items-center mb-0">
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
                                $query = mysqli_query($con, "SELECT * FROM menu_category");
                                while ($datactg = mysqli_fetch_array($query)) :
                                ?>

                                    <tr>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= $no++ ?></span>
                                        </td>
                                        <td class="align-middle justify-content-start">
                                            <p class="text-xs font-weight-bold mb-0"><?= $datactg['ctg_name'] ?></p>
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



        <!-- menu table -->
        <div class="col-12 col-xl mb-4">

            <div class="card border shadow-xs mb-4">
                <div class="card-header border-bottom pb-0">
                    <div class="d-sm-flex align-items-center">
                        <div>
                            <h6 class="font-weight-semibold text-lg mb-0">Catering Menu</h6>
                            <p class="text-sm">See information about all menus</p>
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
                                                $selected = $category['menu_ctg_id'] == $selected_category ? 'selected' : '';
                                                echo "<option value='{$category['menu_ctg_id']}' $selected>{$category['ctg_name']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </form>
                            </div>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                <span class="btn-inner--icon"><i class="fas fa-bowl-food d-block me-2"></i></span>
                                <span class="btn-inner--text">New Menu</span>
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
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Menu</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price/pax</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</span>
                                    </th>
                                </tr>
                            </thead>
                            <?php
                            $no = 1;
                            while ($data = mysqli_fetch_array($menu)) :
                            ?>

                                <tr>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold"><?= $no++ ?></span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex align-items-center">
                                                <img class="avatar avatar-sm me-2" src="./uploads/<?= $data['menu_img'] ?>" />
                                            </div>
                                            <div class="align-middle justify-content-start ms-1">
                                                <h6 class="mb-0 text-sm font-weight-semibold"><?= $data['menu_name'] ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold"><?= $data['price_per_pax'] ?></span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold"><?= $data['ctg_name'] ?></span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#modalView<?= $data['menu_id'] ?>"><i class="fas fa-eye text-sm opacity-5"></i></a>
                                        <span style="margin: 0 10px;"></span>
                                        <a href="#" class="text-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['menu_id'] ?>"><i class="fas fa-edit text-sm opacity-5"></i></a>
                                        <span style="margin: 0 10px;"></span>
                                        <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $data['menu_id'] ?>"><i class="fas fa-trash text-sm opacity-5"></i></a>
                                    </td>
                                </tr>

                                <!-- Modal View -->
                                <div class="modal fade modal-lg" id="modalView<?= $data['menu_id'] ?>" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditLabel<?= $data['menu_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="modalEditLabel<?= $data['menu_id'] ?>">Menu Details</h1>
                                            </div>
                                            <form action=".php" method="POST">
                                                <input type="hidden" name="menu_id" value="<?= $data['menu_id'] ?>">
                                                <div class="modal-body">
                                                    <div class="mb-3 text-center">
                                                        <img class="w-30" src="./uploads/<?= $data['menu_img'] ?>" />
                                                    </div>
                                                    <span>
                                                        <div class="mb-3">
                                                            <label class="form-label">Menu</label>
                                                            <input type="text" class="form-control form-control-lg" name="menu_name" value="<?= $data['menu_name'] ?>" readonly>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Category</label>
                                                                <input type="text" class="form-control form-control-lg" name="ctg_name" value="<?= $data['ctg_name'] ?>" readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Price/pax</label>
                                                                <input type="text" class="form-control form-control-lg" name="price_per_pax" value="<?= $data['price_per_pax'] ?>" readonly>
                                                            </div>
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
                                                        <div class="mb-3">
                                                            <label class="form-label">Description</label>
                                                            <textarea class="form-control form-control-lg" name="description" readonly> <?= $data['description'] ?> </textarea>
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
                                <div class="modal fade modal-lg" id="modalEdit<?= $data['menu_id'] ?>" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditLabel<?= $data['menu_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="modalEditLabel<?= $data['menu_id'] ?>">Edit menu</h1>
                                            </div>
                                            <form action="menu_crud.php" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="menu_id" value="<?= $data['menu_id'] ?>">
                                                <div class="modal-body">
                                                    <div class="mb-3 text-center">
                                                        <input type="hidden" name="old_img" value="<?= $data['menu_img'] ?>">
                                                        <img class="w-30" src="./uploads/<?= $data['menu_img'] ?>" /><br><br>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">New Image</label><br>
                                                        <input type="file" class="form-control form-control-lg" name="menu_img">
                                                    </div>
                                                    <span>
                                                        <div class="mb-3">
                                                            <label class="form-label">Name</label>
                                                            <input type="text" class="form-control form-control-lg" name="menu_name" value="<?= $data['menu_name'] ?>" required>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Price/pax</label>
                                                                <input type="text" class="form-control form-control-lg" name="price_per_pax" value="<?= $data['price_per_pax'] ?>" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Category</label>
                                                                <select class="form-select form-select-lg" name="menu_ctg_id">
                                                                    <option value="" selected disabled>Select Category</option>
                                                                    <?php
                                                                    $categories = mysqli_query($con, "SELECT * FROM menu_category");
                                                                    if (mysqli_num_rows($categories) > 0) {
                                                                        foreach ($categories as $category) {
                                                                    ?>
                                                                            <option value="<?= $category['menu_ctg_id'] ?>" <?= $data['menu_ctg_id'] == $category['menu_ctg_id'] ? 'selected' : '' ?>><?= $category['ctg_name'] ?></option>

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
                                                            <textarea class="form-control form-control-lg" name="description" required> <?= $data['description'] ?> </textarea>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-dark mb-0" name="editmenu">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal Edit -->

                                <!-- Modal Delete -->
                                <div class="modal fade" id="modalDelete<?= $data['menu_id'] ?>" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel<?= $data['menu_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalDeleteLabel<?= $data['menu_id'] ?>">Delete Confirmation</h1>
                                            </div>
                                            <form action="menu_crud.php" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="menu_id" value="<?= $data['menu_id'] ?>">
                                                <div class="modal-body">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-danger font-weight-bold text-center"><i class="fas fa-exclamation-circle text-9xl font-weight-bolder opacity-5"></i></span><br>
                                                        <span class="text-dark text-lg font-weight-bold text-center">Delete <?= $data['menu_name'] ?>?</span><br>
                                                        <span class="text-secondary text-sm text-center">You won't be able to revert this data!</span><br>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-dark mb-0" name="deletemenu">Confirm Delete</button>
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
                                        <h1 class="modal-title fs-5" id="modalAddLabel">Add Menu</h1>
                                    </div>
                                    <form action="menu_crud.php" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Upload Image</label>
                                                <input type="file" class="form-control form-control-lg" name="menu_img">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" class="form-control form-control-lg" name="menu_name" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Price/pax</label>
                                                    <input type="text" class="form-control form-control-lg" name="price_per_pax" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Category</label>
                                                    <select class="form-select form-select-lg" name="menu_ctg_id">
                                                        <option value="" selected disabled>Select Category</option>
                                                        <?php
                                                        $categories = mysqli_query($con, "SELECT * FROM menu_category");
                                                        if (mysqli_num_rows($categories) > 0) {
                                                            foreach ($categories as $category) {
                                                        ?>
                                                                <option value="<?= $category['menu_ctg_id'] ?>"><?= $category['ctg_name'] ?></option>

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
                                                <textarea class="form-control form-control-lg" name="description" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-sm btn-dark mb-0" name="addmenu">Save</button>
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
        <!-- end table menus -->
    </div>
</div>







<?php include('includes/footer.php') ?>