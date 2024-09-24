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

$page_title = 'Gallery';
include('includes/header.php');
?>
<!-- datation categories table -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Gallery</h2>
            <div class="d-flex justify-content-end mb-3">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalAdd">
                    <span class="btn-inner--icon"><i class="fas fa-image d-block me-2"></i></span>
                    <span class="btn-inner--text">New image</span>
                </button>
            </div>
            <div class="row">
                <?php
                $no = 1;
                $query = mysqli_query($con, "SELECT * FROM gallery ORDER BY created_at DESC");
                while ($data = mysqli_fetch_array($query)) :
                ?>
                    <div class="col-md-3 mb-2">
                        <div class="position-relative">
                            <div class="card shadow-lg min-height-250 max-height-300 text-white" style="background-image: url('./uploads/<?= htmlspecialchars($data['img_file']); ?>'); background-size: cover; background-position: center;">
                                <div class="position-absolute top-0 end-0 m-2">
                                    <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $data['img_id'] ?>"><i class="fas fa-trash text-sm opacity-8"></i></a>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-end">
                                    <p class="card-title mt-3 bg-light opacity-8 text-center text-dark"><?= htmlspecialchars($data['description']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal Delete -->
                    <div class="modal fade" id="modalDelete<?= $data['img_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel<?= $data['img_id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalEditLabel<?= $data['img_id'] ?>">Delete Confirmation</h1>
                                </div>
                                <form action="gallery_crud.php" method="POST">
                                    <input type="hidden" name="img_id" value="<?= $data['img_id'] ?>">
                                    <div class="modal-body">
                                        <div class="d-flex flex-column">
                                            <span class="text-dark-lg font-weight-bold text-center"><img class="avatar avatar-xl sm-3" src="./uploads/<?= $data['img_file'] ?>" /></span><br>
                                            <span class="text-dark text-lg font-weight-bold text-center">Are you sure?</span><br>
                                            <span class="text-secondary text-sm text-center">You won't be able to revert this data!</span><br>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-sm btn-dark mb-0" name="deleteimg">Confirm Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal Delete -->
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>
<!-- end datation categories table -->

<!-- Modal Add -->
<div class="modal fade" id="modalAdd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAddLabel">Add Image</h1>
            </div>
            <form action="gallery_crud.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Upload Image</label>
                        <input type="file" class="form-control form-control-lg" name="img_file" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" class="form-control form-control-lg" name="description" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-dark mb-0" name="addimg">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Add -->

<?php include('includes/footer.php') ?>