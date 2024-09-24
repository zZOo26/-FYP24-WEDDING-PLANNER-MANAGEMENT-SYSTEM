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

$page_title = 'Bridal attire';
include('includes/header.php');
?>
<!-- Bridal attires table -->
<div class="container">
    <div class="row">
        <div class="row-md-12">
            <div class="col-12">
                <div class="card border shadow-xs mb-4">
                    <div class="card-header border-bottom pb-0">
                        <div class="d-sm-flex align-items-center">
                            <div>
                                <h6 class="font-weight-semibold text-lg mb-0">Bridal Attires</h6>
                                <p class="text-sm">See information about all attires</p>
                            </div>
                            <div class="ms-auto d-flex justify-content-end">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                    <span class="btn-inner--icon"><i class="fas fa-add d-block me-2"></i></span>
                                    </span><span class="btn-inner--text">New attire</span>
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
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Attire</span>
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
                                $query = mysqli_query($con, "SELECT * FROM bridal_attire ORDER BY updated_at");
                                while ($data = mysqli_fetch_array($query)) :
                                ?>

                                    <tr>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= $no++ ?></span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div>
                                                <img class="avatar avatar-sm" src="./uploads/<?= $data['attire_img'] ?>" />
                                            </div>
                                            <span>
                                                <div>
                                                    <h6 class="mb-0 text-sm"><?= $data['attire_name'] ?></h6>
                                                </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= $data['attire_type'] ?></span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= $data['min_size'] ?> - <?= $data['max_size'] ?></span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= $data['attire_price'] ?></span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= $data['promo_id'] ?></span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#modalView<?= $data['attire_id'] ?>"><i class="fas fa-eye text-sm opacity-5"></i></a>
                                            <span style="margin: 0 10px;"></span>
                                            <a href="#" class="text-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['attire_id'] ?>"><i class="fas fa-edit text-sm opacity-5"></i></a>
                                            <span style="margin: 0 10px;"></span>
                                            <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $data['attire_id'] ?>"><i class="fas fa-trash text-sm opacity-5"></i></a>
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
                                                            <img class="w-30" src="./uploads/<?= $data['attire_img'] ?>" />
                                                        </div>
                                                        <span>
                                                            <div class="mb-3">
                                                                <label class="form-label">Attire Name</label>
                                                                <input type="text" class="form-control form-control-lg" name="attire_name" value="<?= $data['attire_name'] ?>" readonly>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Attire Type</label>
                                                                    <input type="text" class="form-control form-control-lg" name="attire_type" value="<?= $data['attire_type'] ?>" readonly>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Attire Size</label>
                                                                    <input type="text" class="form-control form-control-lg" name="size" value="<?= $data['min_size'] ?> - <?= $data['max_size']?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Attire Price</label>
                                                                    <input type="text" class="form-control form-control-lg" name="attire_price" value="<?= $data['attire_price'] ?>" readonly>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Promotion Code</label>
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
                                                                <textarea class="form-control form-control-lg" name="attire_desc" readonly> <?= $data['attire_desc'] ?> </textarea>
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
                                                    <h1 class="modal-title fs-5" id="modalEditLabel<?= $data['attire_id'] ?>">Edit Attire</h1>
                                                </div>
                                                <form action="attire_crud.php" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="attire_id" value="<?= $data['attire_id'] ?>">
                                                    <div class="modal-body">
                                                        <div class="mb-3 text-center">
                                                            <input type="hidden" name="old_img" value="<?= $data['attire_img'] ?>">
                                                            <img class="w-30" src="./uploads/<?= $data['attire_img'] ?>" /><br><br>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">New Image</label><br>
                                                            <input type="file" class="form-control form-control-lg" name="attire_img">
                                                        </div>
                                                        <span>
                                                            <div class="mb-3">
                                                                <label class="form-label">Attire Name</label>
                                                                <input type="text" class="form-control form-control-lg" name="attire_name" value="<?= $data['attire_name'] ?>" required>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Attire Type</label>
                                                                    <input type="text" class="form-control form-control-lg" name="attire_type" value="<?= $data['attire_type'] ?>" required>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label class="form-label">Minimum Size</label>
                                                                        <select class="form-select form-select-lg" name="min_size">
                                                                            <option value="" selected disabled>Select size</option>
                                                                            <option value="1">XXS</option>
                                                                            <option value="2">XS</option>
                                                                            <option value="3">S</option>
                                                                            <option value="4">M</option>
                                                                            <option value="5">L</option>
                                                                            <option value="6">XL</option>
                                                                            <option value="7">2XL</option>
                                                                            <option value="7">3XL</option>
                                                                            <option value="7">4XL</option>
                                                                            <option value="7">5XL</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label class="form-label">Maximum Size</label>
                                                                        <select class="form-select form-select-lg" name="max_size">
                                                                            <option value="" selected disabled>Select size</option>
                                                                            <option value="1">XXS</option>
                                                                            <option value="2">XS</option>
                                                                            <option value="3">S</option>
                                                                            <option value="4">M</option>
                                                                            <option value="5">L</option>
                                                                            <option value="6">XL</option>
                                                                            <option value="7">2XL</option>
                                                                            <option value="7">3XL</option>
                                                                            <option value="7">4XL</option>
                                                                            <option value="7">5XL</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Attire Price</label>
                                                                    <input type="text" class="form-control form-control-lg" name="attire_price" value="<?= $data['attire_price'] ?>" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Promotion Code</label>
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
                                                                <textarea class="form-control form-control-lg" name="attire_desc" required> <?= $data['attire_desc'] ?> </textarea>
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
                                                <form action="attire_crud.php" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="attire_id" value="<?= $data['attire_id'] ?>">
                                                    <div class="modal-body">
                                                        <div class="d-flex flex-column">
                                                            <span class="text-danger font-weight-bold text-center"><i class="fas fa-exclamation-circle text-9xl font-weight-bolder opacity-5"></i></span><br>
                                                            <span class="text-dark text-lg font-weight-bold text-center">Delete <?= $data['attire_name'] ?>?</span><br>
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
                                            <h1 class="modal-title fs-5" id="modalAddLabel">Add attire</h1>
                                        </div>
                                        <form action="attire_crud.php" method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Upload Attire Image</label>
                                                    <input type="file" class="form-control form-control-lg" name="attire_img">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Attire Name</label>
                                                    <input type="text" class="form-control form-control-lg" name="attire_name" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Attire Type</label>
                                                        <input type="text" class="form-control form-control-lg" name="attire_type" required>
                                                    </div>
                                                    <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label class="form-label">Minimum Size</label>
                                                                        <select class="form-select form-select-lg" name="min_size">
                                                                            <option value="" selected disabled>Select size</option>
                                                                            <option value="1">XXS</option>
                                                                            <option value="2">XS</option>
                                                                            <option value="3">S</option>
                                                                            <option value="4">M</option>
                                                                            <option value="5">L</option>
                                                                            <option value="6">XL</option>
                                                                            <option value="7">2XL</option>
                                                                            <option value="7">3XL</option>
                                                                            <option value="7">4XL</option>
                                                                            <option value="7">5XL</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label class="form-label">Maximum Size</label>
                                                                        <select class="form-select form-select-lg" name="max_size">
                                                                            <option value="" selected disabled>Select size</option>
                                                                            <option value="1">XXS</option>
                                                                            <option value="2">XS</option>
                                                                            <option value="3">S</option>
                                                                            <option value="4">M</option>
                                                                            <option value="5">L</option>
                                                                            <option value="6">XL</option>
                                                                            <option value="7">2XL</option>
                                                                            <option value="7">3XL</option>
                                                                            <option value="7">4XL</option>
                                                                            <option value="7">5XL</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Attire Price</label>
                                                        <input type="text" class="form-control form-control-lg" name="attire_price" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Promotion Code</label>
                                                        <select class="form-select form-select-lg" name="promo_id">
                                                            <option value="" selected disabled>Select Promotion Code</option>
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
        </div>
    </div>
</div>
<!-- end promotion categories table -->

<?php include('includes/footer.php') ?>