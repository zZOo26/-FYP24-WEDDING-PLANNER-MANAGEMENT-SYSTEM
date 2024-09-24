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

$page_title = 'Bridal Dais';
include('includes/header.php');
?>

<!-- Bridal Daises table -->
<div class="container">
    <div class="row">
        <div class="row-md-12">
            <div class="col-12">
                <div class="card border shadow-xs mb-4">
                    <div class="card-header border-bottom pb-0">
                        <div class="d-sm-flex align-items-center">
                            <div>
                                <h6 class="font-weight-semibold text-lg mb-0">Bridal Dais</h6>
                                <p class="text-sm">See information about all daises</p>
                            </div>
                            <div class="ms-auto d-flex justify-content-end">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                    <span class="btn-inner--icon"><i class="fas fa-add d-block me-2"></i></span>
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
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dais</span>
                                        </th>
                                        <th class="align-middle text-center">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</span>
                                        </th>
                                        <th class="align-middle text-center">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Size</span>
                                        </th>
                                        <th class="align-middle text-center">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price</span>
                                        </th>
                                        <th class="align-middle text-center">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Promotion ID</span>
                                        </th>
                                        <th class="align-middle text-center">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</span>
                                        </th>
                                    </tr>
                                </thead>
                                <?php
                                $no = 1;
                                $query = mysqli_query($con, "SELECT * FROM bridal_dais ORDER BY updated_at DESC");
                                while ($data = mysqli_fetch_array($query)):
                                ?>

                                    <tr>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= $no++ ?></span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div>
                                                <img class="avatar avatar-sm" src="./uploads/<?= $data['dais_img'] ?>" />
                                            </div>
                                            <span>
                                                <div>
                                                    <h6 class="mb-0 text-sm"><?= $data['dais_name'] ?></h6>
                                                </div>

                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= $data['dais_type'] ?></span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= $data['dais_size'] ?></span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= $data['dais_price'] ?></span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= $data['promo_id'] ?></span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#modalView<?= $data['dais_id'] ?>"><i class="fas fa-eye text-sm opacity-5"></i></a>
                                            <span style="margin: 0 10px;"></span>
                                            <a href="#" class="text-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['dais_id'] ?>"><i class="fas fa-edit text-sm opacity-5"></i></a>
                                            <span style="margin: 0 10px;"></span>
                                            <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $data['dais_id'] ?>"><i class="fas fa-trash text-sm opacity-5"></i></a>
                                        </td>
                                    </tr>

                                    <!-- Modal View -->
                                    <div class="modal fade modal-lg" id="modalView<?= $data['dais_id'] ?>" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditLabel<?= $data['dais_id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="modalEditLabel<?= $data['dais_id'] ?>">Dais Details</h1>
                                                </div>
                                                <form action=".php" method="POST">
                                                    <input type="hidden" name="dais_id" value="<?= $data['dais_id'] ?>">
                                                    <div class="modal-body">
                                                        <div class="mb-3 text-center">
                                                            <img class="w-30" src="./uploads/<?= $data['dais_img'] ?>" />
                                                        </div>
                                                        <span>
                                                            <div class="mb-3">
                                                                <label class="form-label">Dais Name</label>
                                                                <input type="text" class="form-control form-control-lg" name="dais_name" value="<?= $data['dais_name'] ?>" readonly>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Dais Type</label>
                                                                    <input type="text" class="form-control form-control-lg" name="dais_type" value="<?= $data['dais_type'] ?>" readonly>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Dais Size</label>
                                                                    <input type="text" class="form-control form-control-lg" name="dais_size" value="<?= $data['dais_size'] ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Dais Price</label>
                                                                    <input type="text" class="form-control form-control-lg" name="dais_price" value="<?= $data['dais_price'] ?>" readonly>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Promotion ID</label>
                                                                    <select class="form-select form-select-lg" name="promo_id">
                                                                        <option value="<?= $data['promo_id'] ?>" selected disabled></option>
                                                                    </select>
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
                                                                <textarea class="form-control form-control-lg" name="dais_desc" readonly> <?= $data['dais_desc'] ?> </textarea>
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
                                    <div class="modal fade  modal-lg" id="modalEdit<?= $data['dais_id'] ?>" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditLabel<?= $data['dais_id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="modalEditLabel<?= $data['dais_id'] ?>">Edit dais</h1>
                                                </div>
                                                <form action="dais_crud.php" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="dais_id" value="<?= $data['dais_id'] ?>">
                                                    <div class="modal-body">
                                                        <div class="mb-3 text-center">
                                                            <input type="hidden" name="old_img" value="<?= $data['dais_img'] ?>">
                                                            <img class="w-30" src="./uploads/<?= $data['dais_img'] ?>" /><br><br>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">New Image</label><br>
                                                            <input type="file" class="form-control form-control-lg" name="dais_img">
                                                        </div>
                                                        <span>
                                                            <div class="mb-3">
                                                                <label class="form-label">Dais Name</label>
                                                                <input type="text" class="form-control form-control-lg" name="dais_name" value="<?= $data['dais_name'] ?>" required>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Dais Type</label>
                                                                    <input type="text" class="form-control form-control-lg" name="dais_type" value="<?= $data['dais_type'] ?>" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Dais Size</label>
                                                                    <input type="text" class="form-control form-control-lg" name="dais_size" value="<?= $data['dais_size'] ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Dais Price</label>
                                                                    <input type="text" class="form-control form-control-lg" name="dais_price" value="<?= $data['dais_price'] ?>" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Promotion ID</label>
                                                                    <select class="form-select form-select-lg" name="promo_id">
                                                                        <option value="<?= $data['promo_id'] ?>" selected disabled>Select Promotion ID</option>
                                                                        <?php
                                                                        $promoQuery = mysqli_query($con, "SELECT promo_id, promo_name FROM promotions");
                                                                        while ($promoData = mysqli_fetch_assoc($promoQuery)) {
                                                                            echo "<option value=\"{$promoData['promo_id']}\">{$promoData['promo_id']} - {$promoData['promo_name']}</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Description</label>
                                                                <textarea class="form-control form-control-lg" name="dais_desc" required> <?= $data['dais_desc'] ?> </textarea>
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
                                    <div class="modal fade" id="modalDelete<?= $data['dais_id'] ?>" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel<?= $data['dais_id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalDeleteLabel<?= $data['dais_id'] ?>">Delete Confirmation</h1>
                                                </div>
                                                <form action="dais_crud.php" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="dais_id" value="<?= $data['dais_id'] ?>">
                                                    <div class="modal-body">
                                                        <div class="d-flex flex-column">
                                                            <span class="text-danger font-weight-bold text-center"><i class="fas fa-exclamation-circle text-9xl font-weight-bolder opacity-5"></i></span><br>
                                                            <span class="text-dark text-lg font-weight-bold text-center">Delete <?= $data['dais_name'] ?>?</span><br>
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
                            <div class="modal fade modal-lg" id="modalAdd" data-bs-backdrop="static" role="dialog" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="modalAddLabel">Add dais</h1>
                                        </div>
                                        <form action="dais_crud.php" method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Upload Dais Image</label>
                                                    <input type="file" class="form-control form-control-lg" name="dais_img">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Dais Name</label>
                                                    <input type="text" class="form-control form-control-lg" name="dais_name" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Dais Type</label>
                                                        <input type="text" class="form-control form-control-lg" name="dais_type" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Dais Size</label>
                                                        <input type="text" class="form-control form-control-lg" name="dais_size" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Dais Price</label>
                                                        <input type="text" class="form-control form-control-lg" name="dais_price" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Promotion ID</label>
                                                        <select class="form-select form-select-lg" name="promo_id">
                                                            <option value="" selected disabled>Select Promotion ID</option>
                                                            <?php
                                                            $promoQuery = mysqli_query($con, "SELECT promo_id, promo_name FROM promotions");
                                                            while ($promoData = mysqli_fetch_assoc($promoQuery)) {
                                                                echo "<option value=\"{$promoData['promo_id']}\">{$promoData['promo_id']} - {$promoData['promo_name']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
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
</div>
<!-- end promotion categories table -->

<?php include('includes/footer.php') ?>