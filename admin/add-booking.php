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

$page_title = 'Add New Booking';
include('includes/header.php');
?>

<section>
    <div class="container">
        <div class="row">
            <div class="card border shadow-lg mb-4">
                <div class="card-header border-bottom pb-3">
                    <div class="d-sm-flex align-items-center">
                        <div>
                            <h6 class="font-weight-semibold text-lg mb-0">New Booking</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body px-5">
                    <form action="booking_crud.php" method="POST">
                        <div class="mb-3 mt-3 border-top border-bottom px-2 py-2">
                            <h4>Booking Details:</h4>
                        </div>
                        <div class="mb-3">
                            <label for="customer" class="form-label">Customer</label>
                            <select class="form-select" name="cust_id">
                                <option value="" selected disabled>Select customer</option>
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
                        <!-- Other form fields -->
                        <div class=" mb-3">
                            <label for="item" class="form-label">Package</label>
                            <select class="form-select" name="pkg_id">
                                <option value="" selected disabled>Select Package</option>
                                <?php
                                // Update the query to retrieve pkg_name and ctg_name from the packages table
                                $packages_query = "SELECT a.pkg_id , a.pkg_name, ctg_name FROM packages a INNER JOIN package_category b ON a.pkg_ctg_id=b.pkg_ctg_id ORDER BY b.pkg_ctg_id";
                                $packages_query_run = mysqli_query($con, $packages_query);
                                while ($package = mysqli_fetch_assoc($packages_query_run)) {
                                    echo "<option value='{$package['pkg_id']}'>{$package['pkg_name']} ({$package['ctg_name']})</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="event_date" class="form-label">Event Date</label>
                                <input type="date" class="form-control" name="event_date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="event_slot" class="form-label">Event Slot</label>
                                <select class="form-select" name="event_slot">
                                    <option value="" selected disabled>Select One</option>
                                    <?php
                                    $slot_query = "SELECT * FROM event_slot";
                                    $slot_query_run = mysqli_query($con, $slot_query);
                                    while ($slot = mysqli_fetch_assoc($slot_query_run)) {
                                        echo "<option value='{$slot['slot_id']}'>{$slot['slot']} - {$slot['start_time']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class=" mb-3">
                            <label for="event_loc" class="form-label">Event Location</label>
                            <input type="text" class="form-control" name="event_loc" required>
                        </div>
                        <div class=" mb-3">
                            <label for="total_pax" class="form-label">Total Pax</label>
                            <input type="text" class="form-control" name="total_pax" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bride_name" class="form-label">Bride Name</label>
                                <input type="text" class="form-control" name="bride_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="groom_name" class="form-label">Groom Name</label>
                                <input type="text" class="form-control" name="groom_name" required>
                            </div>
                        </div>
                        <div class="mb-3 mt-5 border-top border-bottom px-2 py-2">
                            <h4>Payment Details:</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="total_payment" class="form-label">Total Payment (RM)</label>
                                <input type="number" class="form-control " name="total_payment" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="deposit" class="form-label">Deposit (RM)</label>
                                <input type="number" class="form-control" name="deposit" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="deposit_status" class="form-label">Deposit Status</label>
                                <select class="form-select" name="deposit_status">
                                    <option value="0">Pending</option>
                                    <option value="1">Paid</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payment_bal" class="form-label">Payment Balance (RM)</label>
                                <input type="number" class="form-control" name="payment_bal" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="full_payment_status" class="form-label">Full Payment Status</label>
                                <select class="form-select" name="full_payment_status">
                                    <option value="0">Pending</option>
                                    <option value="1">Paid</option>
                                </select>
                            </div>
                        </div> -->
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
                            <label for="remarks" class="form-label">Remark</label>
                            <textarea class="form-control form-control-lg" name="remarks" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" name="booking_status">
                                <option value="0">Upcoming</option>
                                <option value="1">Completed</option>
                                <option value="2">Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3 mt-5 border-top border-bottom px-2 py-2">
                            <h4>Assign External Resources (if included):</h4>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Makeup Artist</label>
                                <select class="form-select" name="makeup_artist">
                                    <option value="" selected disabled>Select One</option>
                                    <?php
                                    $mua_query = "SELECT a.*, b.* FROM ex_resources a INNER JOIN resource_category b ON a.resource_ctg_id=b.resource_ctg_id WHERE b.ctg_name ='Makeup Artist'";
                                    $mua_query_run = mysqli_query($con, $mua_query);
                                    while ($mua = mysqli_fetch_assoc($mua_query_run)) {
                                        echo "<option value='{$mua['resource_id']}'>{$mua['fullname']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Event Host</label>
                                <select class="form-select" name="event_host">
                                    <option value="" selected disabled>Select One</option>
                                    <?php
                                    $host_query = "SELECT a.*, b.* FROM ex_resources a INNER JOIN resource_category b ON a.resource_ctg_id=b.resource_ctg_id WHERE b.ctg_name ='Event Host'";
                                    $host_query_run = mysqli_query($con, $host_query);
                                    while ($host = mysqli_fetch_assoc($host_query_run)) {
                                        echo "<option value='{$host['resource_id']}'>{$host['fullname']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Photographer</label>
                                <select class="form-select" name="photographer">
                                    <option value="" selected disabled>Select One</option>
                                    <?php
                                    $photographer_query = "SELECT a.*, b.* FROM ex_resources a INNER JOIN resource_category b ON a.resource_ctg_id=b.resource_ctg_id WHERE b.ctg_name ='Photographer'";
                                    $photographer_query_run = mysqli_query($con, $photographer_query);
                                    while ($photographer = mysqli_fetch_assoc($photographer_query_run)) {
                                        echo "<option value='{$photographer['resource_id']}'>{$photographer['fullname']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 mt-5 border-top border-bottom px-2 py-2">
                            <h4>Bridal Dais and Attire (If included):</h4>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Bridal Dais</label>
                                <select class="form-select" name="dais_id">
                                    <option value="" selected disabled>Select One</option>
                                    <?php
                                    $dais_query = "SELECT * FROM bridal_dais";
                                    $dais_query_run = mysqli_query($con, $dais_query);
                                    while ($dais = mysqli_fetch_assoc($dais_query_run)) {
                                        echo "<option value='{$dais['dais_id']}'>{$dais['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Bridal Attire</label>
                                <select class="form-select" name="attire_id">
                                    <option value="" selected disabled>Select One</option>
                                    <?php
                                    $attire_query = "SELECT * FROM bridal_attire";
                                    $attire_query_run = mysqli_query($con, $attire_query);
                                    while ($attire = mysqli_fetch_assoc($attire_query_run)) {
                                        echo "<option value='{$attire['attire_id']}'>{$attire['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer mt-5 text-end">
                            <a href="pkg_bookings.php" type="button" class="btn btn-sm btn-outline-secondary mb-0">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-dark mb-0" name="addbooking">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>


<?php include('includes/footer.php'); ?>