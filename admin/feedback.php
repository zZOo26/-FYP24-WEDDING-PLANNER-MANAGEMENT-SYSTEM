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

$page_title = 'Customer Feedback';
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
                                <h6 class="font-weight-semibold text-lg mb-0">Customer's Feedback</h6>
                                <p class="text-sm">See information about all feedback</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <form action="feedback_crud.php" method="POST">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center">
                                                <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No.</span>
                                            </th>
                                            <th class="align-middle ">
                                                <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</span>
                                            </th>
                                            <th class="align-middle ">
                                                <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Feedback</span>
                                            </th>
                                            <th class="align-middle text-center">
                                                <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rating</span>
                                            </th>
                                            <th class="align-middle text-center">
                                                <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">View?</span>
                                            </th>
                                            <th class="align-middle text-center">
                                                <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $no = 1;
                                    $query = mysqli_query($con, "SELECT * FROM feedback f INNER JOIN customers c ON f.cust_id = c.cust_id ORDER BY f.created_at DESC");
                                    while ($data = mysqli_fetch_array($query)) :
                                    ?>
                                        <tr>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?= $no++ ?></span>
                                            </td>
                                            <td class="align-middle">
                                                <p class="text-xs font-weight-bold mb-0"><?= $data['firstname'] ?> <?= $data['lastname'] ?></p>
                                            </td>
                                            <td class="align-middle" style="max-width: 200px; word-wrap: break-word; white-space: normal; overflow: hidden;">
                                                <p class="text-xs font-weight-bold mb-0"><?= $data['feedback'] ?></p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <p class="text-xs font-weight-bold mb-0"><?= $data['rating'] ?></p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <input type="checkbox" name="view_update[]" value="<?= $data['fb_id'] ?>" <?= $data['view'] == 1 ? 'checked' : '' ?>>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $data['fb_id'] ?>"><i class="fas fa-trash text-sm opacity-5"></i></a>
                                            </td>
                                        </tr>

                                        <!-- Modal Delete -->
                                        <div class="modal fade" id="modalDelete<?= $data['fb_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel<?= $data['fb_id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalDeleteLabel<?= $data['fb_id'] ?>">Delete Confirmation</h1>
                                                    </div>
                                                    <form action="feedback_crud.php" method="POST">
                                                        <input type="hidden" name="fb_id" value="<?= $data['fb_id'] ?>">
                                                        <div class="modal-body">
                                                            <div class="d-flex flex-column">
                                                                <span class="text-danger font-weight-bold text-center"><i class="fas fa-exclamation-circle text-9xl font-weight-bolder opacity-5"></i></span><br>
                                                                <span class="text-dark text-lg font-weight-bold text-center">Delete this feedback?</span><br>
                                                                <span class="text-secondary text-sm text-center">You won't be able to revert this data!</span><br>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-sm btn-dark mb-0" name="deletefb">Confirm Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal Delete -->

                                    <?php endwhile; ?>

                                </table>
                                <div class="text-end mt-3 me-3">
                                    <button type="submit" class="btn btn-sm btn-dark mb-3">Update</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php') ?>