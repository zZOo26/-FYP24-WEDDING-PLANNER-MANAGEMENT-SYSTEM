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

$page_title = 'Add New Rental';
include('includes/header.php');
?>


<div class="container">
    <div class="row">
        <div class="col-12 col-xl mb-4">

            <div class="card border shadow-xs mb-4">
                <div class="card-header border-bottom pb-3">
                    <div class="d-sm-flex align-items-center">
                        <div>
                            <h6 class="font-weight-semibold text-lg mb-0">New Rental</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body px-5">

                    <form action="rental_crud.php" method="POST">
                        <input type="hidden" name="rental_type" value="<?php echo $_POST['rental_type']; ?>">

                        <div class="mb-3">
                            <label for="customer" class="form-label">Customer</label>
                            <select class="form-select" name="cust_id">
                                <option value="" selected disabled>Select Customer</option>
                                <!-- Populate with customer data -->
                                <?php
                                $customer_query = "SELECT cust_id, firstname, lastname FROM customers";
                                $customer_query_run = mysqli_query($con, $customer_query);
                                while ($customer = mysqli_fetch_assoc($customer_query_run)) {
                                    echo "<option value='{$customer['cust_id']}'>{$customer['firstname']} {$customer['lastname']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="item" class="form-label">Item</label>
                            <select class="form-select" name="item_id" id="itemSelect">
                                <option value="" selected disabled>Select Item</option>
                                <?php
                                if ($_POST['rental_type'] == 'dais') {
                                    $dais_query = "SELECT dais_id AS item_id, name FROM bridal_dais";
                                    $dais_query_run = mysqli_query($con, $dais_query);
                                    while ($dais = mysqli_fetch_assoc($dais_query_run)) {
                                        echo "<option value='{$dais['item_id']}'>{$dais['name']} (Dais)</option>";
                                    }
                                } elseif ($_POST['rental_type'] == 'attire') {
                                    $attire_query = "SELECT attire_id AS item_id, name FROM bridal_attire";
                                    $attire_query_run = mysqli_query($con, $attire_query);
                                    while ($attire = mysqli_fetch_assoc($attire_query_run)) {
                                        echo "<option value='{$attire['item_id']}'>{$attire['name']} (Attire)</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Rest of the form fields... -->

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="event_date" class="form-label">Event Date</label>
                                <input type="date" class="form-control" name="event_date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="return_date" class="form-label">Return Date</label>
                                <input type="date" class="form-control" name="return_date" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="event_loc" class="form-label">Event Location</label>
                            <input type="text" class="form-control" name="event_loc" required>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="total_payment" class="form-label">Total Payment (RM)</label>
                                <input type="number" class="form-control" name="total_payment" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="deposit" class="form-label">Deposit (RM)</label>
                                <input type="number" class="form-control" name="deposit" value="100" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="deposit_status" class="form-label">Deposit Status</label>
                                <select class="form-select" name="deposit_status">
                                    <option value="0">Pending</option>
                                    <option value="1">Paid</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="full_payment_date" class="form-label">Full Payment Date</label>
                                <input type="date" class="form-control" name="full_payment_date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="promo_code" class="form-label">Promotion Code</label>
                                <input type="text" class="form-control" name="promo_code">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="remark" class="form-label">Remark</label>
                            <textarea class="form-control form-control-lg" name="remark" required></textarea>
                        </div>
                        <div class="card-footer text-end">
                            <a href="rentals.php" type="button" class="btn btn-sm btn-outline-secondary mb-0" >Cancel</a>
                            <button type="submit" class="btn btn-sm btn-dark mb-0" name="addrental">Add Rental</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>